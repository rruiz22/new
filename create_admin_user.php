<?php

/**
 * Script para crear un usuario administrador
 * Ejecutar desde la raíz del proyecto: php create_admin_user.php
 */

// Definir constantes necesarias
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);

// Cargar el framework
require_once 'vendor/autoload.php';

// Bootstrap CodeIgniter
$paths = new \Config\Paths();
$bootstrap = rtrim(realpath(FCPATH . '../'), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
$app = new \CodeIgniter\CodeIgniter(new \Config\App());
$app->initialize();

use App\Models\CustomUserModel;
use App\Models\CustomRoleModel;

echo "=== CREAR USUARIO ADMINISTRADOR ===\n\n";

// Datos del administrador por defecto
$defaultData = [
    'username' => 'rruiz',
    'email' => 'admin@mda.com',
    'password' => 'lalinha01?',
    'first_name' => 'Administrator',
    'last_name' => 'System',
];

echo "Datos por defecto:\n";
foreach ($defaultData as $key => $value) {
    if ($key !== 'password') {
        echo "- " . ucfirst(str_replace('_', ' ', $key)) . ": $value\n";
    } else {
        echo "- Password: " . str_repeat('*', strlen($value)) . "\n";
    }
}

echo "\n¿Deseas usar estos datos? (y/n): ";
$handle = fopen("php://stdin", "r");
$useDefault = trim(fgets($handle));

$userData = $defaultData;

if (strtolower($useDefault) !== 'y') {
    echo "\nIngresa los datos del administrador:\n";
    
    echo "Username: ";
    $username = trim(fgets($handle));
    if (!empty($username)) $userData['username'] = $username;
    
    echo "Email: ";
    $email = trim(fgets($handle));
    if (!empty($email)) $userData['email'] = $email;
    
    echo "Password: ";
    $password = trim(fgets($handle));
    if (!empty($password)) $userData['password'] = $password;
    
    echo "First Name: ";
    $firstName = trim(fgets($handle));
    if (!empty($firstName)) $userData['first_name'] = $firstName;
    
    echo "Last Name: ";
    $lastName = trim(fgets($handle));
    if (!empty($lastName)) $userData['last_name'] = $lastName;
}

fclose($handle);

try {
    echo "\n=== CREANDO USUARIO ===\n";
    
    // Verificar si el usuario ya existe
    $userProvider = auth()->getProvider();
    $existingUser = $userProvider->findByCredentials(['email' => $userData['email']]);
    
    if ($existingUser) {
        echo "❌ ERROR: Ya existe un usuario con el email: {$userData['email']}\n";
        exit(1);
    }
    
    // Verificar si el username ya existe
    $existingUsername = $userProvider->where('username', $userData['username'])->first();
    if ($existingUsername) {
        echo "❌ ERROR: Ya existe un usuario con el username: {$userData['username']}\n";
        exit(1);
    }
    
    echo "✓ Validaciones pasadas\n";
    
    // Preparar datos completos del usuario
    $fullUserData = [
        'username' => $userData['username'],
        'email' => $userData['email'],
        'password' => $userData['password'],
        'first_name' => $userData['first_name'],
        'last_name' => $userData['last_name'],
        'user_type' => 'staff',
        'active' => 1,
    ];
    
    // Crear el usuario
    echo "✓ Creando usuario...\n";
    $user = $userProvider->create($fullUserData);
    
    if (!$user) {
        echo "❌ ERROR: No se pudo crear el usuario\n";
        exit(1);
    }
    
    echo "✓ Usuario creado con ID: {$user->id}\n";
    
    // Activar el usuario
    echo "✓ Activando usuario...\n";
    $user->activate();
    
    // Asignar grupos de administrador
    echo "✓ Asignando grupos de administrador...\n";
    $user->addGroup('admin');
    $user->addGroup('superadmin');
    
    // Buscar y asignar rol personalizado de admin
    $customRoleModel = new CustomRoleModel();
    $adminRole = $customRoleModel->where('name', 'admin')->first();
    
    if ($adminRole) {
        echo "✓ Asignando rol personalizado: {$adminRole['title']}\n";
        $customUserModel = new CustomUserModel();
        $customUserModel->update($user->id, ['role_id' => $adminRole['id']]);
    } else {
        echo "⚠️  Advertencia: No se encontró el rol 'admin' en custom_roles\n";
    }
    
    echo "\n=== ✅ USUARIO ADMINISTRADOR CREADO EXITOSAMENTE ===\n";
    echo "ID de Usuario: {$user->id}\n";
    echo "Username: {$userData['username']}\n";
    echo "Email: {$userData['email']}\n";
    echo "Nombre: {$userData['first_name']} {$userData['last_name']}\n";
    echo "Grupos: admin, superadmin\n";
    
    if ($adminRole) {
        echo "Rol Personalizado: {$adminRole['title']} (ID: {$adminRole['id']})\n";
    }
    
    echo "\n🚀 Ahora puedes iniciar sesión con estas credenciales.\n";
    
} catch (\Exception $e) {
    echo "\n❌ ERROR: " . $e->getMessage() . "\n";
    echo "Detalles del error:\n";
    echo $e->getTraceAsString() . "\n";
    exit(1);
}
