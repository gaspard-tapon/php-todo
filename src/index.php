<?php

require_once __DIR__ . '/vendor/autoload.php';

use App\Database;
use App\TodoRepository;

$pdo = Database::connect();
$repo = new TodoRepository($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $repo->add($_POST['task'] ?? '');
    } elseif (isset($_POST['toggle'])) {
        $repo->toggle((int) $_POST['toggle']);
    } elseif (isset($_POST['delete'])) {
        $repo->delete((int) $_POST['delete']);
    }
    header('Location: /');
    exit;
}

$todos = $repo->getAll();
require __DIR__ . '/templates/todos.php';
