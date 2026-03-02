<?php
$dbHost = getenv('DB_HOST') ?: 'db';
$dbName = getenv('DB_NAME') ?: 'app';
$dbUser = getenv('DB_USER') ?: 'root';
$dbPass = getenv('DB_PASSWORD') ?: 'root';

$pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $task = trim($_POST['task'] ?? '');
        if ($task !== '') {
            $stmt = $pdo->prepare('INSERT INTO todos (task) VALUES (?)');
            $stmt->execute([$task]);
        }
    } elseif (isset($_POST['toggle'])) {
        $stmt = $pdo->prepare('UPDATE todos SET done = NOT done WHERE id = ?');
        $stmt->execute([$_POST['toggle']]);
    } elseif (isset($_POST['delete'])) {
        $stmt = $pdo->prepare('DELETE FROM todos WHERE id = ?');
        $stmt->execute([$_POST['delete']]);
    }
    header('Location: /');
    exit;
}

$todos = $pdo->query('SELECT * FROM todos ORDER BY created_at DESC')->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo App</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: system-ui, sans-serif; background: #f5f5f5; display: flex; justify-content: center; padding: 40px 16px; }
        .container { width: 100%; max-width: 500px; }
        h1 { margin-bottom: 20px; color: #333; }
        .add-form { display: flex; gap: 8px; margin-bottom: 24px; }
        .add-form input { flex: 1; padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 16px; }
        .add-form button { padding: 10px 20px; background: #d94a4a; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-size: 16px; }
        .add-form button:hover { background: #357abd; }
        .todo { display: flex; align-items: center; gap: 10px; background: #fff; padding: 12px 16px; border-radius: 6px; margin-bottom: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
        .todo.done span { text-decoration: line-through; color: #999; }
        .todo span { flex: 1; }
        .todo button { background: none; border: none; cursor: pointer; font-size: 18px; padding: 4px 8px; }
        .empty { color: #999; text-align: center; margin-top: 40px; }
    </style>
</head>
<body>
<div class="container">
    <h1>Mes tâches</h1>
    <form class="add-form" method="post">
        <input type="text" name="task" placeholder="Nouvelle tâche..." required>
        <button type="submit" name="add" value="1">Ajouter</button>
    </form>
    <?php if (empty($todos)): ?>
        <p class="empty">Aucune tâche pour le moment.</p>
    <?php endif; ?>
    <?php foreach ($todos as $todo): ?>
        <div class="todo <?= $todo['done'] ? 'done' : '' ?>">
            <form method="post" style="display:contents">
                <button name="toggle" value="<?= $todo['id'] ?>"><?= $todo['done'] ? '✅' : '⬜' ?></button>
            </form>
            <span><?= htmlspecialchars($todo['task']) ?></span>
            <form method="post" style="display:contents">
                <button name="delete" value="<?= $todo['id'] ?>">🗑</button>
            </form>
        </div>
    <?php endforeach; ?>
</div>
</body>
</html>
