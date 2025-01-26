<?php

require_once 'actions/addTask.php';
require_once 'actions/getTasks.php';
require_once 'actions/saveTasks.php';
require_once 'actions/deleteTask.php';
require_once 'actions/changeStatus.php';
require_once 'actions/listTasks.php';

$options = ['Add Task', 'Delete Task', 'Mark Task as completed', 'List Tasks', 'Exit'];
$currentSelection = 0;

function initializeTasksFile()
{
    $tasks_file_path = __DIR__.'/tasks.json';
    if (! file_exists($tasks_file_path)) {
        file_put_contents($tasks_file_path, json_encode([]));
    }
}

initializeTasksFile();

while (true) {
    system('clear');
    system('stty cbreak -echo');

    echo "Select an option:\n";

    // Display menu options
    foreach ($options as $index => $option) {
        echo ($index === $currentSelection) ? "> $option\n" : "  $option\n";
    }

    $input = ord(fgetc(STDIN)); // Read a single character from input

    switch ($input) {
        case 65: // Up arrow key
            $currentSelection = ($currentSelection > 0) ? $currentSelection - 1 : count($options) - 1;
            break;
        case 66: // Down arrow key
            $currentSelection = ($currentSelection < count($options) - 1) ? $currentSelection + 1 : 0;
            break;
        case 32: // Spacebar
            switch ($currentSelection) {
                case 0:
                    addTask();
                    break;
                case 1:
                    deleteTask();
                    break;
                case 2:
                    changeStatus();
                    break;
                case 3:
                    listTasks();
                    break;
                case 4:
                    exitProgram();
                    break;
            }
            echo "\nPress Enter to continue...";
            fgets(STDIN);
            break;
    }
}

function exitProgram()
{
    echo "\nExiting program. Goodbye!\n";
    system('stty -cbreak echo'); // Reset terminal settings
    exit;
}
