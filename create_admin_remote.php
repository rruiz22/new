<?php

/**
 * Script para crear usuario administrador con base de datos remota
 * Usa las credenciales del archivo .env
 */

echo "=== CREAR USUARIO ADMINISTRADOR (Base de Datos Remota) ===\n\n";

// Leer configuración del archivo .env
function loadEnvConfig($envFile = '.env') {
    if (!file_exists($envFile)) {
        throw new Exception("Archivo .env no encontrado");
    }
    
    $config = [];
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue; // Saltar comentarios
        }
        
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            $config[$key] = $value;
        }
    }
    
    return $config;
}

try {
    // Cargar configuración
    echo "📋 Cargando configuración desde .env...\n";
    $config = loadEnvConfig();
    
    // Extraer credenciales de base de datos
    $host = $config['database.default.hostname'] ?? '';
    $database = $config['database.default.database'] ?? '';
    $username = $config['database.default.username'] ?? '';
    $password = $config['database.default.password'] ?? '';
    $port = $config['database.default.port'] ?? '3306';
    
    if (empty($host) || empty($database) || empty($username)) {
        throw new Exception("Credenciales de base de datos incompletas en .env");
    }
    
    echo "✓ Configuración cargada:\n";
    echo "  - Host: $host:$port\n";
    echo "  - Database: $database\n";
    echo "  - Username: $username\n";
    echo "  - Password: " . str_repeat('*', strlen($password)) . "\n\n";
    
    // Conectar a la base de datos remota
    echo "🔌 Conectando a la base de datos remota...\n";
    $dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8";
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
    ]);
    
    echo "✅ Conexión exitosa a la base de datos remota!\n\n";
    
    // Verificar tablas necesarias
    echo "🔍 Verificando estructura de base de datos...\n";
    $requiredTables = ['users', 'auth_identities', 'auth_groups_users', 'custom_roles'];
    
    foreach ($requiredTables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() === 0) {
            throw new Exception("Tabla requerida '$table' no existe");
        }
        $countStmt = $pdo->query("SELECT COUNT(*) as count FROM $table");
        $count = $countStmt->fetch()['count'];
        echo "  ✓ $table ($count registros)\n";
    }
    
    echo "\n=== CONFIGURACIÓN DEL USUARIO ADMINISTRADOR ===\n";
    
    // Datos del administrador (usando los datos modificados del archivo)
    $adminData = [
        'username' => 'rruiz',
        'email' => 'admin@mda.com',
        'password' => 'lalinha01?',
        'first_name' => 'Administrator',
        'last_name' => 'System'
    ];
    
    echo "📝 Datos del usuario administrador:\n";
    foreach ($adminData as $key => $value) {
        if ($key !== 'password') {
            echo "  - " . ucfirst(str_replace('_', ' ', $key)) . ": $value\n";
        } else {
            echo "  - Password: " . str_repeat('*', strlen($value)) . "\n";
        }
    }
    
    echo "\n¿Proceder con la creación? (y/n): ";
    $handle = fopen("php://stdin", "r");
    $proceed = trim(fgets($handle));
    fclose($handle);
    
    if (strtolower($proceed) !== 'y') {
        echo "❌ Operación cancelada por el usuario.\n";
        exit(0);
    }
    
    echo "\n🚀 Iniciando proceso de creación...\n";
    
    // Verificar si el usuario ya existe
    $checkUserStmt = $pdo->prepare("
        SELECT u.id, u.username, ai.secret as email 
        FROM users u 
        LEFT JOIN auth_identities ai ON ai.user_id = u.id AND ai.type = 'email_password'
        WHERE u.username = ? OR ai.secret = ?
    ");
    $checkUserStmt->execute([$adminData['username'], $adminData['email']]);
    $existingUser = $checkUserStmt->fetch();
    
    if ($existingUser) {
        echo "⚠️  Usuario existente encontrado:\n";
        echo "   ID: {$existingUser['id']}\n";
        echo "   Username: {$existingUser['username']}\n";
        echo "   Email: {$existingUser['email']}\n";
        echo "\n¿Actualizar usuario existente? (y/n): ";
        $handle = fopen("php://stdin", "r");
        $update = trim(fgets($handle));
        fclose($handle);
        
        if (strtolower($update) !== 'y') {
            echo "❌ Operación cancelada.\n";
            exit(0);
        }
        
        $userId = $existingUser['id'];
        echo "🔄 Actualizando usuario existente...\n";
        
        // Actualizar datos del usuario
        $updateUserStmt = $pdo->prepare("
            UPDATE users 
            SET first_name = ?, last_name = ?, user_type = 'staff', active = 1, updated_at = NOW()
            WHERE id = ?
        ");
        $updateUserStmt->execute([$adminData['first_name'], $adminData['last_name'], $userId]);
        echo "  ✓ Datos de usuario actualizados\n";
        
        // Actualizar password
        $hashedPassword = password_hash($adminData['password'], PASSWORD_DEFAULT);
        $updatePasswordStmt = $pdo->prepare("
            UPDATE auth_identities 
            SET secret2 = ?, updated_at = NOW()
            WHERE user_id = ? AND type = 'email_password'
        ");
        $updatePasswordStmt->execute([$hashedPassword, $userId]);
        echo "  ✓ Password actualizado\n";
        
    } else {
        echo "👤 Creando nuevo usuario...\n";
        
        $pdo->beginTransaction();
        
        try {
            // Crear usuario
            $insertUserStmt = $pdo->prepare("
                INSERT INTO users (username, first_name, last_name, user_type, active, created_at, updated_at)
                VALUES (?, ?, ?, 'staff', 1, NOW(), NOW())
            ");
            $insertUserStmt->execute([
                $adminData['username'],
                $adminData['first_name'],
                $adminData['last_name']
            ]);
            
            $userId = $pdo->lastInsertId();
            echo "  ✓ Usuario creado con ID: $userId\n";
            
            // Crear identidad de autenticación
            $hashedPassword = password_hash($adminData['password'], PASSWORD_DEFAULT);
            $insertIdentityStmt = $pdo->prepare("
                INSERT INTO auth_identities (user_id, type, name, secret, secret2, expires, extra, force_reset, last_used_at, created_at, updated_at)
                VALUES (?, 'email_password', NULL, ?, ?, NULL, NULL, 0, NULL, NOW(), NOW())
            ");
            $insertIdentityStmt->execute([$userId, $adminData['email'], $hashedPassword]);
            echo "  ✓ Identidad de autenticación creada\n";
            
            $pdo->commit();
            
        } catch (Exception $e) {
            $pdo->rollback();
            throw $e;
        }
    }
    
    // Asignar grupos de administrador
    echo "\n🔐 Asignando grupos de administrador...\n";
    $groups = ['admin', 'superadmin'];
    
    foreach ($groups as $group) {
        // Verificar si ya tiene el grupo
        $checkGroupStmt = $pdo->prepare("
            SELECT id FROM auth_groups_users 
            WHERE user_id = ? AND `group` = ?
        ");
        $checkGroupStmt->execute([$userId, $group]);
        
        if (!$checkGroupStmt->fetch()) {
            $insertGroupStmt = $pdo->prepare("
                INSERT INTO auth_groups_users (user_id, `group`, created_at)
                VALUES (?, ?, NOW())
            ");
            $insertGroupStmt->execute([$userId, $group]);
            echo "  ✓ Asignado al grupo: $group\n";
        } else {
            echo "  - Ya pertenece al grupo: $group\n";
        }
    }
    
    // Asignar rol personalizado
    echo "\n👑 Asignando rol personalizado...\n";
    $adminRoleStmt = $pdo->prepare("
        SELECT id, title FROM custom_roles 
        WHERE title LIKE '%Admin%' AND is_active = 1 
        ORDER BY id ASC LIMIT 1
    ");
    $adminRoleStmt->execute();
    $role = $adminRoleStmt->fetch();
    
    if ($role) {
        $updateRoleStmt = $pdo->prepare("UPDATE users SET role_id = ? WHERE id = ?");
        $updateRoleStmt->execute([$role['id'], $userId]);
        echo "  ✓ Rol asignado: {$role['title']} (ID: {$role['id']})\n";
    } else {
        echo "  ⚠️  No se encontró rol 'admin' en custom_roles\n";
    }
    
    echo "\n" . str_repeat("=", 60) . "\n";
    echo "🎉 ¡USUARIO ADMINISTRADOR CREADO EXITOSAMENTE!\n";
    echo str_repeat("=", 60) . "\n";
    echo "📊 Resumen:\n";
    echo "  🆔 ID de Usuario: $userId\n";
    echo "  👤 Username: {$adminData['username']}\n";
    echo "  📧 Email: {$adminData['email']}\n";
    echo "  👨‍💼 Nombre: {$adminData['first_name']} {$adminData['last_name']}\n";
    echo "  🔐 Grupos: " . implode(', ', $groups) . "\n";
    
    if ($role) {
        echo "  👑 Rol: {$role['title']}\n";
    }
    
    echo "\n🌐 Información de acceso:\n";
    echo "  🔗 URL: " . ($config['app.baseURL'] ?? 'http://localhost/mda_nuevo/') . "\n";
    echo "  👤 Username: {$adminData['username']}\n";
    echo "  🔑 Password: {$adminData['password']}\n";
    
    echo "\n✅ ¡Listo! Ahora puedes iniciar sesión en el sistema.\n";
    
} catch (PDOException $e) {
    echo "\n❌ ERROR DE BASE DE DATOS:\n";
    echo "Código: " . $e->getCode() . "\n";
    echo "Mensaje: " . $e->getMessage() . "\n";
    echo "\n🔍 Posibles soluciones:\n";
    echo "- Verificar que el servidor de BD esté accesible\n";
    echo "- Comprobar credenciales en .env\n";
    echo "- Verificar conectividad de red\n";
    exit(1);
} catch (Exception $e) {
    echo "\n❌ ERROR GENERAL: " . $e->getMessage() . "\n";
    exit(1);
}
