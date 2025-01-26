<?php

require_once 'getTasks.php';

function deleteTask()
{
    $tasks = getTasks();
    if (empty($tasks)) {
        echo "\nNo tasks to delete.\n";

        return;
    }

    $currentSelection = 0;
    while (true) {
        system('clear');
        echo "Select a task to delete:\n";

        foreach ($tasks as $index => $task) {
            $prefix = ($index === $currentSelection) ? '> ' : '  ';
            echo "$prefix".($index + 1).'. '.$task['task']."\n";
        }

        echo "\nUse the up/down arrows to navigate, and press spacebar to select.\n";

        $input = ord(fgetc(STDIN));

        switch ($input) {
            case 65: // Up arrow key
                $currentSelection = ($currentSelection > 0) ? $currentSelection - 1 : count($tasks) - 1;
                break;
            case 66: // Down arrow key
                $currentSelection = ($currentSelection < count($tasks) - 1) ? $currentSelection + 1 : 0;
                break;
            case 32: // Spacebar
                unset($tasks[$currentSelection]);
                saveTasks(array_values($tasks));
                echo "\nTask deleted successfully!\n";

                return;
        }
    }
}
