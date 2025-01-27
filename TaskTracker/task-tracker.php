#!/usr/bin/env php
<?php

// Include necessary action files
require_once __DIR__.'/actions/addTask.php';
require_once __DIR__.'/actions/listTasks.php';

// Initialize SQLite Database
function initializeDatabase()
{
    $dbPath = __DIR__.'/task-tracker.db'; // Use __DIR__ to get the absolute path to the database
    if (! file_exists($dbPath)) {
        touch($dbPath);
    }

    $pdo = new PDO('sqlite:'.$dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create the tasks table if it doesn't exist
    $pdo->exec("CREATE TABLE IF NOT EXISTS tasks (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        title TEXT NOT NULL,
        status TEXT NOT NULL DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    return $pdo;
}

// Connect to the database
$pdo = initializeDatabase();

// CLI Menu
$options = ['Add Task', 'List Tasks', 'Exit'];
$currentSelection = 0;

while (true) {
    system('clear');
    system('stty cbreak -echo');

    echo "Task Tracker Menu\n";
    foreach ($options as $index => $option) {
        echo ($index === $currentSelection ? '> ' : '  ').$option."\n";
    }

    $input = ord(fgetc(STDIN));
    switch ($input) {
        case 65: // Up arrow
            $currentSelection = ($currentSelection > 0) ? $currentSelection - 1 : count($options) - 1;
            break;
        case 66: // Down arrow
            $currentSelection = ($currentSelection < count($options) - 1) ? $currentSelection + 1 : 0;
            break;
        case 10: // Enter
            if ($currentSelection === 0) {
                system('stty -cbreak echo');

                echo 'Enter task title: ';
                $title = trim(fgets(STDIN));
                addTask($pdo, $title); // Call addTask() from actions/addTask.php
            } elseif ($currentSelection === 1) {
                listTasks($pdo); // Call listTasks() from actions/getTasks.php
            } elseif ($currentSelection === 2) {
                echo "Exiting Task Tracker. Goodbye!\n";
                exit;
            }
            echo "\nPress Enter to continue...";
            fgets(STDIN);
            break;
    }
}
