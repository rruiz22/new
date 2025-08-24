<?php

/**
 * Script de debug para crear usuario administrador
 */

echo "=== INICIANDO SCRIPT ===\n";

// Configuración de la base de datos
$host = 'localhost';
$dbname = 'mda_nuevo';
$username = 'root';
$password = '';

echo "Intentando conectar a la base de datos...\n";
echo "Host: $host\n";
echo "Database: $dbname\n";
echo "Username: $username\n";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✓ Conexión exitosa a la base de datos\n\n";
    
    // Verificar que las tablas existan
    echo "Verificando tablas...\n";
    
    $tables = ['users', 'auth_identities', 'auth_groups_users', 'custom_roles'];
    foreach ($tables as $table) {
        try {
            $stmt = $pdo->query("SELECT COUNT(*) FROM $table");
            $count = $stmt->fetchColumn();
            echo "✓ Tabla '$table' existe con $count registros\n";
        } catch (Exception $e) {
            echo "❌ Error con tabla '$table': " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n=== CREANDO USUARIO ADMINISTRADOR ===\n";
    
    // Datos del administrador (usando los datos que modificaste)
    $adminData = [
        'username' => 'rruiz',
        'email' => 'admin@mda.com',
        'password' => 'lalinha01?',
        'first_name' => 'Administrator',
        'last_name' => 'System'
    ];
    
    echo "Datos del usuario:\n";
    foreach ($adminData as $key => $value) {
        if ($key !== 'password') {
            echo "- $key: $value\n";
        } else {
            echo "- password: " . str_repeat('*', strlen($value)) . "\n";
        }
    }
    
    // Verificar si el usuario ya existe
    echo "\nVerificando si el usuario ya existe...\n";
    $checkUser = $pdo->prepare("
        SELECT u.id, u.username, ai.secret as email 
        FROM users u 
        LEFT JOIN auth_identities ai ON ai.user_id = u.id AND ai.type = 'email_password'
        WHERE u.username = ? OR ai.secret = ?
    ");
    $checkUser->execute([$adminData['username'], $adminData['email']]);
    $existingUser = $checkUser->fetch(PDO::FETCH_ASSOC);
    
    if ($existingUser) {
        echo "❌ ERROR: Ya existe un usuario:\n";
        echo "   ID: {$existingUser['id']}\n";
        echo "   Username: {$existingUser['username']}\n";
        echo "   Email: {$existingUser['email']}\n";
        echo "\n¿Deseas continuar y actualizar este usuario? (y/n): ";
        $handle = fopen("php://stdin", "r");
        $response = trim(fgets($handle));
        fclose($handle);
        
        if (strtolower($response) !== 'y') {
            echo "Operación cancelada.\n";
            exit(0);
        }
        
        $userId = $existingUser['id'];
        echo "✓ Actualizando usuario existente con ID: $userId\n";
        
        // Actualizar usuario existente
        $updateUser = $pdo->prepare("
            UPDATE users 
            SET first_name = ?, last_name = ?, user_type = 'staff', active = 1, updated_at = NOW()
            WHERE id = ?
        ");
        $updateUser->execute([$adminData['first_name'], $adminData['last_name'], $userId]);
        
        // Actualizar password
        $hashedPassword = password_hash($adminData['password'], PASSWORD_DEFAULT);
        $updatePassword = $pdo->prepare("
            UPDATE auth_identities 
            SET secret2 = ?, updated_at = NOW()
            WHERE user_id = ? AND type = 'email_password'
        ");
        $updatePassword->execute([$hashedPassword, $userId]);
        echo "✓ Password actualizado\n";
        
    } else {
        echo "✓ Usuario no existe, creando nuevo...\n";
        
        $pdo->beginTransaction();
        
        // Crear nuevo usuario
        $insertUser = $pdo->prepare("
            INSERT INTO users (username, first_name, last_name, user_type, active, created_at, updated_at)
            VALUES (?, ?, ?, 'staff', 1, NOW(), NOW())
        ");
        
        $insertUser->execute([
            $adminData['username'],
            $adminData['first_name'],
            $adminData['last_name']
        ]);
        
        $userId = $pdo->lastInsertId();
        echo "✓ Usuario creado con ID: $userId\n";
        
        // Crear identidad
        $hashedPassword = password_hash($adminData['password'], PASSWORD_DEFAULT);
        $insertIdentity = $pdo->prepare("
            INSERT INTO auth_identities (user_id, type, name, secret, secret2, expires, extra, force_reset, last_used_at, created_at, updated_at)
            VALUES (?, 'email_password', NULL, ?, ?, NULL, NULL, 0, NULL, NOW(), NOW())
        ");
        
        $insertIdentity->execute([$userId, $adminData['email'], $hashedPassword]);
        echo "✓ Identidad creada\n";
        
        $pdo->commit();
    }
    
    // Asignar grupos (siempre)
    echo "\nAsignando grupos...\n";
    $groups = ['admin', 'superadmin'];
    
    foreach ($groups as $group) {
        // Verificar si ya tiene el grupo
        $checkGroup = $pdo->prepare("SELECT id FROM auth_groups_users WHERE user_id = ? AND `group` = ?");
        $checkGroup->execute([$userId, $group]);
        
        if (!$checkGroup->fetch()) {
            $insertGroup = $pdo->prepare("
                INSERT INTO auth_groups_users (user_id, `group`, created_at)
                VALUES (?, ?, NOW())
            ");
            $insertGroup->execute([$userId, $group]);
            echo "✓ Asignado al grupo: $group\n";
        } else {
            echo "- Ya pertenece al grupo: $group\n";
        }
    }
    
    // Asignar rol personalizado
    echo "\nAsignando rol personalizado...\n";
    $adminRole = $pdo->prepare("SELECT id, title FROM custom_roles WHERE name = 'admin' AND is_active = 1");
    $adminRole->execute();
    $role = $adminRole->fetch(PDO::FETCH_ASSOC);
    
    if ($role) {
        $updateRole = $pdo->prepare("UPDATE users SET role_id = ? WHERE id = ?");
        $updateRole->execute([$role['id'], $userId]);
        echo "✓ Rol asignado: {$role['title']} (ID: {$role['id']})\n";
    } else {
        echo "⚠️  No se encontró rol 'admin' en custom_roles\n";
    }
    
    echo "\n=== ✅ PROCESO COMPLETADO ===\n";
    echo "ID de Usuario: $userId\n";
    echo "Username: {$adminData['username']}\n";
    echo "Email: {$adminData['email']}\n";
    echo "Grupos: " . implode(', ', $groups) . "\n";
    
    if ($role) {
        echo "Rol: {$role['title']}\n";
    }
    
    echo "\n🚀 Puedes iniciar sesión con:\n";
    echo "   Username: {$adminData['username']}\n";
    echo "   Password: {$adminData['password']}\n";
    
} catch (PDOException $e) {
    echo "❌ ERROR de base de datos: " . $e->getMessage() . "\n";
    echo "Código de error: " . $e->getCode() . "\n";
    exit(1);
} catch (Exception $e) {
    echo "❌ ERROR general: " . $e->getMessage() . "\n";
    exit(1);
}

