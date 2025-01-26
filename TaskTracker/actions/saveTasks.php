<?php

function saveTasks($tasks)
{
    $tasks_file_path = __DIR__.'/../tasks.json';
    file_put_contents($tasks_file_path, json_encode($tasks, JSON_PRETTY_PRINT));
}
