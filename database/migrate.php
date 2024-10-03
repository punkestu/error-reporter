<?php

require_once __DIR__ . '/../utils/db.php';

function migrateCreate($name)
{
    echo "Creating migration " . $name . ":\n";
    $path = __DIR__ . '/migrations';
    $up = $path . '/' . date('YmdHis') . '_' . $name . '-up.sql';
    $down = $path . '/' . date('YmdHis') . '_' . $name . '-down.sql';
    file_put_contents($up, '');
    file_put_contents($down, '');
}

function migrateUp()
{
    $db = db_connect();

    $res = $db->query('SHOW TABLES LIKE "migrations"');
    if ($res->num_rows === 0) {
        $db->query('CREATE TABLE migrations (id INT PRIMARY KEY AUTO_INCREMENT, name VARCHAR(255), stage INT DEFAULT 0)');
        $lastStage = 0;
    } else {
        $res = $db->query('SELECT stage FROM migrations ORDER BY stage DESC');
        $lastStage = $res->fetch_assoc();
        $lastStage = $lastStage ? $lastStage['stage'] : 0;
        $res = $db->query('SELECT name FROM migrations');
        $migrated = $res->fetch_all(MYSQLI_ASSOC);
        $migrated = array_map(function ($item) {
            return $item['name'];
        }, $migrated);
    }

    $path    = __DIR__ . '/migrations';
    $files = scandir($path);
    $files = array_diff($files, array('.', '..'));
    $files = array_filter($files, function ($file) {
        return preg_match('/\-up\.sql$/', $file);
    });

    $migrateBatch = [];
    foreach ($files as $file) {
        if (array_search($file, $migrated) !== false) {
            continue;
        }
        echo "Migrating " . $file . ":\n";
        $sql = file_get_contents($path . '/' . $file);
        $db->query($sql);
        $migrateBatch[] = "('" . $file . "', " . $lastStage + 1 . ")";
    }

    if (empty($migrateBatch)) {
        echo "Nothing to migrate\n";
        return;
    }
    $db->query('INSERT INTO migrations (name, stage) VALUES ' . join(",", $migrateBatch) . ';');
}

function migrateDown()
{
    $db = db_connect();

    $res = $db->query('SHOW TABLES LIKE "migrations"');
    if ($res->num_rows === 0) {
        echo "Nothing to rollback\n";
        return;
    } else {
        $res = $db->query('SELECT name FROM migrations');
        $migrated = $res->fetch_all(MYSQLI_ASSOC);
        $migrated = array_map(function ($item) {
            return str_replace("-up.sql", "-down.sql", $item['name']);
        }, $migrated);
        $migrated = array_reverse($migrated);
    }

    $path    = __DIR__ . '/migrations';
    foreach ($migrated as $file) {
        echo "Migrating " . $file . ":\n";
        $sql = file_get_contents($path . '/' . $file);
        $db->query($sql);
    }

    $db->query('TRUNCATE TABLE migrations');
}
