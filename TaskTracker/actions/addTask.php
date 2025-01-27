<?php

function addTask($pdo, $title)
{
    $stmt = $pdo->prepare('INSERT INTO tasks (title) VALUES (:title)');
    $stmt->execute([':title' => $title]);
    echo "Task added: $title\n";
}
