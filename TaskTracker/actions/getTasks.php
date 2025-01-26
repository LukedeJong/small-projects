<?php

function getTasks()
{
    $tasks_file_path = __DIR__.'/../tasks.json';

    return json_decode(file_get_contents($tasks_file_path), true);
}
