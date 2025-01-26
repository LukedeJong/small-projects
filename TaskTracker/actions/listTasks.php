<?php

require_once 'getTasks.php';

function listTasks()
{
    $tasks = getTasks();
    if (empty($tasks)) {
        echo "\nNo tasks available.\n";

        return;
    }

    echo "\nTasks:\n";
    foreach ($tasks as $index => $task) {
        $status = $task['completed'] ? '[Completed]' : '';
        echo ($index + 1).". $status ".$task['task']."\n";
    }
}
