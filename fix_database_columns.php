<?php

try {
    $pdo = new PDO('mysql:host=localhost;dbname=mda', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "=== FIXING DATABASE COLUMNS ===\n\n";
    
    // Check and add avatar column to users table
    echo "1. Checking users.avatar column...\n";
    $stmt = $pdo->query('SHOW COLUMNS FROM users LIKE "avatar"');
    if ($stmt->rowCount() == 0) {
        echo "   Adding avatar column to users table...\n";
        $pdo->exec('ALTER TABLE users ADD COLUMN avatar VARCHAR(255) NULL AFTER phone');
        echo "   ✅ Avatar column added successfully\n";
    } else {
        echo "   ✅ Avatar column already exists\n";
    }
    
    // Check and add avatar_style column to users table
    echo "\n2. Checking users.avatar_style column...\n";
    $stmt = $pdo->query('SHOW COLUMNS FROM users LIKE "avatar_style"');
    if ($stmt->rowCount() == 0) {
        echo "   Adding avatar_style column to users table...\n";
        $pdo->exec('ALTER TABLE users ADD COLUMN avatar_style VARCHAR(50) DEFAULT "initials" AFTER avatar');
        echo "   ✅ Avatar_style column added successfully\n";
    } else {
        echo "   ✅ Avatar_style column already exists\n";
    }
    
    // Check if internal_notes table exists
    echo "\n3. Checking internal_notes table...\n";
    $stmt = $pdo->query('SHOW TABLES LIKE "internal_notes"');
    if ($stmt->rowCount() == 0) {
        echo "   Creating internal_notes table...\n";
        $sql = "
        CREATE TABLE internal_notes (
            id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            order_id INT(11) UNSIGNED NOT NULL,
            author_id INT(11) UNSIGNED NOT NULL,
            content TEXT NOT NULL,
            mentions JSON NULL COMMENT 'Array of mentioned user IDs',
            attachments JSON NULL COMMENT 'Array of attachment file paths',
            parent_note_id INT(11) UNSIGNED NULL,
            created_at TIMESTAMP NULL DEFAULT NULL,
            updated_at TIMESTAMP NULL DEFAULT NULL,
            deleted_at TIMESTAMP NULL DEFAULT NULL,
            PRIMARY KEY (id),
            KEY idx_order_id (order_id),
            KEY idx_author_id (author_id),
            KEY idx_created_at (created_at),
            KEY idx_parent_note_id (parent_note_id),
            FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        $pdo->exec($sql);
        echo "   ✅ Internal_notes table created successfully\n";
    } else {
        echo "   ✅ Internal_notes table already exists\n";
        
        // Check if mentions column exists in internal_notes
        echo "   Checking mentions column in internal_notes...\n";
        $stmt = $pdo->query('SHOW COLUMNS FROM internal_notes LIKE "mentions"');
        if ($stmt->rowCount() == 0) {
            echo "   Adding mentions column to internal_notes table...\n";
            $pdo->exec('ALTER TABLE internal_notes ADD COLUMN mentions JSON NULL COMMENT "Array of mentioned user IDs" AFTER content');
            echo "   ✅ Mentions column added successfully\n";
        } else {
            echo "   ✅ Mentions column already exists\n";
        }
        
        // Check if parent_note_id column exists in internal_notes
        echo "   Checking parent_note_id column in internal_notes...\n";
        $stmt = $pdo->query('SHOW COLUMNS FROM internal_notes LIKE "parent_note_id"');
        if ($stmt->rowCount() == 0) {
            echo "   Adding parent_note_id column to internal_notes table...\n";
            $pdo->exec('ALTER TABLE internal_notes ADD COLUMN parent_note_id INT(11) UNSIGNED NULL AFTER attachments');
            echo "   ✅ Parent_note_id column added successfully\n";
        } else {
            echo "   ✅ Parent_note_id column already exists\n";
        }
    }
    
    // Check if note_mentions table exists
    echo "\n4. Checking note_mentions table...\n";
    $stmt = $pdo->query('SHOW TABLES LIKE "note_mentions"');
    if ($stmt->rowCount() == 0) {
        echo "   Creating note_mentions table...\n";
        $sql = "
        CREATE TABLE note_mentions (
            id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            note_id INT(11) UNSIGNED NOT NULL,
            mentioned_user_id INT(11) UNSIGNED NOT NULL,
            read_at TIMESTAMP NULL DEFAULT NULL,
            created_at TIMESTAMP NULL DEFAULT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY unique_mention (note_id, mentioned_user_id),
            FOREIGN KEY (note_id) REFERENCES internal_notes(id) ON DELETE CASCADE ON UPDATE CASCADE,
            FOREIGN KEY (mentioned_user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
        $pdo->exec($sql);
        echo "   ✅ Note_mentions table created successfully\n";
    } else {
        echo "   ✅ Note_mentions table already exists\n";
    }
    
    echo "\n=== DATABASE FIXES COMPLETED ===\n";
    echo "All missing columns and tables have been checked and added if necessary.\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    exit(1);
} 