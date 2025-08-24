<?php

/**
 * Script simple para crear usuario administrador
 * Ejecutar: php create_simple_admin.php
 */

// Configuración de la base de datos (ajustar según tu configuración)
$host = 'localhost';
$dbname = 'mda_nuevo'; // Ajustar nombre de la base de datos
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== CREAR USUARIO ADMINISTRADOR ===\n\n";
    
    // Datos del administrador
    $adminData = [
        'username' => 'admin',
        'email' => 'admin@mda.com',
        'password' => 'admin123456',
        'first_name' => 'Administrator',
        'last_name' => 'System'
    ];
    
    echo "Datos del administrador:\n";
    echo "Username: {$adminData['username']}\n";
    echo "Email: {$adminData['email']}\n";
    echo "Password: {$adminData['password']}\n";
    echo "Nombre: {$adminData['first_name']} {$adminData['last_name']}\n\n";
    
    // Verificar si el usuario ya existe
    $checkUser = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email IN (SELECT secret FROM auth_identities WHERE secret = ?)");
    $checkUser->execute([$adminData['username'], $adminData['email']]);
    
    if ($checkUser->fetch()) {
        echo "❌ ERROR: Ya existe un usuario con ese username o email\n";
        exit(1);
    }
    
    $pdo->beginTransaction();
    
    // 1. Crear el usuario en la tabla users
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
    
    // 2. Crear la identidad de email/password en auth_identities
    $hashedPassword = password_hash($adminData['password'], PASSWORD_DEFAULT);
    $insertIdentity = $pdo->prepare("
        INSERT INTO auth_identities (user_id, type, name, secret, secret2, expires, extra, force_reset, last_used_at, created_at, updated_at)
        VALUES (?, 'email_password', NULL, ?, ?, NULL, NULL, 0, NULL, NOW(), NOW())
    ");
    
    $insertIdentity->execute([$userId, $adminData['email'], $hashedPassword]);
    echo "✓ Identidad de email/password creada\n";
    
    // 3. Asignar grupos de administrador
    $groups = ['admin', 'superadmin'];
    
    foreach ($groups as $group) {
        $insertGroup = $pdo->prepare("
            INSERT INTO auth_groups_users (user_id, `group`, created_at)
            VALUES (?, ?, NOW())
        ");
        $insertGroup->execute([$userId, $group]);
        echo "✓ Asignado al grupo: $group\n";
    }
    
    // 4. Buscar y asignar rol personalizado de admin
    $adminRole = $pdo->prepare("SELECT id, title FROM custom_roles WHERE name = 'admin' AND is_active = 1");
    $adminRole->execute();
    $role = $adminRole->fetch(PDO::FETCH_ASSOC);
    
    if ($role) {
        $updateRole = $pdo->prepare("UPDATE users SET role_id = ? WHERE id = ?");
        $updateRole->execute([$role['id'], $userId]);
        echo "✓ Rol personalizado asignado: {$role['title']} (ID: {$role['id']})\n";
    } else {
        echo "⚠️  Advertencia: No se encontró el rol 'admin' en custom_roles\n";
    }
    
    $pdo->commit();
    
    echo "\n=== ✅ USUARIO ADMINISTRADOR CREADO EXITOSAMENTE ===\n";
    echo "ID: $userId\n";
    echo "Username: {$adminData['username']}\n";
    echo "Email: {$adminData['email']}\n";
    echo "Password: {$adminData['password']}\n";
    echo "Grupos: " . implode(', ', $groups) . "\n";
    
    if ($role) {
        echo "Rol: {$role['title']}\n";
    }
    
    echo "\n🚀 Ahora puedes iniciar sesión en el sistema con estas credenciales.\n";
    
} catch (PDOException $e) {
    if (isset($pdo)) {
        $pdo->rollback();
    }
    echo "❌ ERROR de base de datos: " . $e->getMessage() . "\n";
    exit(1);
} catch (Exception $e) {
    if (isset($pdo)) {
        $pdo->rollback();
    }
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    exit(1);
}
