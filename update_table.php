<?php
$db = new mysqli('localhost', 'root', '', 'mda');
if ($db->connect_error) {
    die('Error: ' . $db->connect_error);
}

echo "Actualizando estructura de la tabla recon_notes...\n";

// 1. Renombrar columna 'note' a 'content'
$result = $db->query("ALTER TABLE recon_notes CHANGE COLUMN note content TEXT NOT NULL");
if ($result) {
    echo "✓ Columna 'note' renombrada a 'content'\n";
} else {
    echo "✗ Error al renombrar columna: " . $db->error . "\n";
}

// 2. Agregar columna parent_id
$result = $db->query("ALTER TABLE recon_notes ADD COLUMN parent_id INT(11) UNSIGNED NULL AFTER user_id");
if ($result) {
    echo "✓ Columna 'parent_id' agregada\n";
} else {
    echo "✗ Error al agregar parent_id: " . $db->error . "\n";
}

// 3. Agregar columna attachments
$result = $db->query("ALTER TABLE recon_notes ADD COLUMN attachments TEXT NULL AFTER mentions");
if ($result) {
    echo "✓ Columna 'attachments' agregada\n";
} else {
    echo "✗ Error al agregar attachments: " . $db->error . "\n";
}

// 4. Agregar columna metadata
$result = $db->query("ALTER TABLE recon_notes ADD COLUMN metadata TEXT NULL AFTER attachments");
if ($result) {
    echo "✓ Columna 'metadata' agregada\n";
} else {
    echo "✗ Error al agregar metadata: " . $db->error . "\n";
}

// 5. Agregar columna is_edited
$result = $db->query("ALTER TABLE recon_notes ADD COLUMN is_edited TINYINT(1) DEFAULT 0 AFTER metadata");
if ($result) {
    echo "✓ Columna 'is_edited' agregada\n";
} else {
    echo "✗ Error al agregar is_edited: " . $db->error . "\n";
}

// 6. Agregar índices
$result = $db->query("ALTER TABLE recon_notes ADD INDEX idx_parent_id (parent_id)");
if ($result) {
    echo "✓ Índice para parent_id agregado\n";
} else {
    echo "✗ Error al agregar índice parent_id: " . $db->error . "\n";
}

$result = $db->query("ALTER TABLE recon_notes ADD INDEX idx_order_parent (order_id, parent_id)");
if ($result) {
    echo "✓ Índice compuesto order_id, parent_id agregado\n";
} else {
    echo "✗ Error al agregar índice compuesto: " . $db->error . "\n";
}

echo "\nEstructura actualizada. Verificando...\n";
$result = $db->query('DESCRIBE recon_notes');
echo "Field | Type | Null | Key\n";
echo "------|------|------|----\n";
while ($row = $result->fetch_assoc()) {
    echo $row['Field'] . ' | ' . $row['Type'] . ' | ' . $row['Null'] . ' | ' . $row['Key'] . "\n";
}

$db->close();
?> 