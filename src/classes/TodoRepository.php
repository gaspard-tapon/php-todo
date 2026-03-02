<?php

namespace App;

use PDO;

class TodoRepository
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll(): array
    {
        return $this->pdo->query('SELECT * FROM todos ORDER BY created_at DESC')->fetchAll();
    }

    public function add(string $task): void
    {
        $task = trim($task);
        if ($task === '') {
            return;
        }

        $stmt = $this->pdo->prepare('INSERT INTO todos (task) VALUES (?)');
        $stmt->execute([$task]);
    }

    public function toggle(int $id): void
    {
        $stmt = $this->pdo->prepare('UPDATE todos SET done = NOT done WHERE id = ?');
        $stmt->execute([$id]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare('DELETE FROM todos WHERE id = ?');
        $stmt->execute([$id]);
    }
}
