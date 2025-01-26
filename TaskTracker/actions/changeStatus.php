<?php

require_once 'getTasks.php';

function changeStatus()
{
    $tasks = getTasks();
    if (empty($tasks)) {
        echo "\nNo tasks to mark as completed.\n";

        return;
    }

    $currentSelection = 0;
    while (true) {
        system('clear');
        echo "Select a task to toggle its status:\n";

        foreach ($tasks as $index => $task) {
            $prefix = ($index === $currentSelection) ? '> ' : '  ';
            $status = $task['completed'] ? '[Completed]' : '';
            echo "$prefix".($index + 1).". $status ".$task['task']."\n";
        }

        echo "\nUse the up/down arrows to navigate, and press spacebar to toggle status.\n";

        $input = ord(fgetc(STDIN));

        switch ($input) {
            case 65: // Up arrow key
                $currentSelection = ($currentSelection > 0) ? $currentSelection - 1 : count($tasks) - 1;
                break;
            case 66: // Down arrow key
                $currentSelection = ($currentSelection < count($tasks) - 1) ? $currentSelection + 1 : 0;
                break;
            case 32: // Spacebar
                $tasks[$currentSelection]['completed'] = ! $tasks[$currentSelection]['completed'];
                saveTasks($tasks);
                echo "\nTask status toggled!\n";

                return;
        }
    }
}
