<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Todo App</title>
    <link rel="stylesheet" href="/templates/style.css">
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
