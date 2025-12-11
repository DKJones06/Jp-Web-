<?php
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/core/auth.php';
require_once __DIR__ . '/../app/core/helpers.php';
require_once __DIR__ . '/../app/core/permissions.php';

<link rel="stylesheet" href="/public/assets/css/admin.css">

requireLogin();

if (!hasPermission('manage_roles')) {
    echo "<div class='card'>Ingen adgang.</div>";
    exit;
}

// opret rolle
if (isset($_POST['new_role'])) {
    $stmt = $db->prepare("INSERT INTO roles (role_name) VALUES (?)");
    $stmt->execute([trim($_POST['new_role'])]);
}

// opdater permissions
if (isset($_POST['update_role'])) {
    $roleId = $_POST['role_id'];
    $perms  = $_POST['permissions'] ?? [];

    // slet gamle
    $stmt = $db->prepare("DELETE FROM role_permissions WHERE role_id = ?");
    $stmt->execute([$roleId]);

    // indsæt nye
    $stmt = $db->prepare("INSERT INTO role_permissions (role_id, permission_id) VALUES (?, ?)");
    foreach ($perms as $p) {
        $stmt->execute([$roleId, $p]);
    }
}

$roles = $db->query("SELECT * FROM roles ORDER BY role_name ASC")->fetchAll();
$permissions = $db->query("SELECT * FROM permissions ORDER BY permission_key ASC")->fetchAll();
?>

<?php require_once __DIR__ . '/../app/views/partials/header.php'; ?>

<div class="card">
    <h1>Roller & Rettigheder</h1>

    <h3>Opret ny rolle</h3>
    <form method="post">
        <input type="text" name="new_role" placeholder="Ny rolle" required>
        <button type="submit">Opret</button>
    </form>
</div>

<div class="card">
    <h2>Rediger rettigheder</h2>

    <?php foreach ($roles as $r): ?>
        <?php
        $stmt = $db->prepare("SELECT permission_id FROM role_permissions WHERE role_id = ?");
        $stmt->execute([$r['id']]);
        $current = $stmt->fetchAll(PDO::FETCH_COLUMN);
        ?>

        <form method="post" class="card" style="margin-bottom:15px;">
            <h3><?= e($r['role_name']) ?></h3>

            <input type="hidden" name="role_id" value="<?= $r['id'] ?>">
            <input type="hidden" name="update_role" value="1">

            <?php foreach ($permissions as $p): ?>
                <label>
                    <input type="checkbox" name="permissions[]" value="<?= $p['id'] ?>"
                        <?= in_array($p['id'], $current) ? 'checked' : '' ?>>
                    <strong><?= e($p['permission_key']) ?></strong> – <?= e($p['description']) ?>
                </label><br>
            <?php endforeach; ?>

            <button type="submit">Gem</button>
        </form>
    <?php endforeach; ?>
</div>

<?php require_once __DIR__ . '/../app/views/partials/footer.php'; ?>
