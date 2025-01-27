<?php

function listTasks($pdo)
{
    $stmt = $pdo->query('SELECT * FROM tasks');
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "{$row['id']}: {$row['title']} [{$row['status']}]\n";
    }
}
