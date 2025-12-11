<?php
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/core/auth.php';
require_once __DIR__ . '/../app/core/helpers.php';
require_once __DIR__ . '/../app/core/permissions.php';

<link rel="stylesheet" href="/public/assets/css/admin.css">

requireLogin();

if (!hasPermission('manage_categories')) {
    echo "<div class='card'>Ingen adgang.</div>";
    exit;
}

// opret
if (isset($_POST['name'])) {
    $name = trim($_POST['name']);
    $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $name));

    $stmt = $db->prepare("INSERT INTO categories (name, slug) VALUES (?, ?)");
    $stmt->execute([$name, $slug]);
}

// slet
if (isset($_GET['delete'])) {
    $stmt = $db->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
}

$cats = $db->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();
?>

<?php require_once __DIR__ . '/../app/views/partials/header.php'; ?>

<div class="card">
    <h1>Kategorier</h1>

    <form method="post">
        <?= csrf_field() ?>
        <input type="text" name="name" placeholder="Kategori navn" required>
        <button type="submit">Opret</button>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Navn</th>
            <th>Slug</th>
            <th>Handling</th>
        </tr>
        <?php foreach ($cats as $c): ?>
            <tr>
                <td><?= $c['id'] ?></td>
                <td><?= e($c['name']) ?></td>
                <td><?= e($c['slug']) ?></td>
                <td><a href="?delete=<?= $c['id'] ?>">Slet</a></td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php require_once __DIR__ . '/../app/views/partials/footer.php'; ?>
