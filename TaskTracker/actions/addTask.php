<?php

function addTask()
{
    system('clear');
    system('stty -cbreak echo');

    echo "Write the task you want to add and press Enter:\n";

    echo "\nTask: ";
    $task = trim(fgets(STDIN));
    $tasks = getTasks();
    $tasks[] = ['task' => $task, 'completed' => false];
    saveTasks($tasks);
    echo "\033[32m\nTask added successfully!\033[0m\n";
}
