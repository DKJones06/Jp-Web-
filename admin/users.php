<?php
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/core/auth.php';
require_once __DIR__ . '/../app/core/helpers.php';
require_once __DIR__ . '/../app/core/permissions.php';

<link rel="stylesheet" href="/public/assets/css/admin.css">

requireLogin();

if (!hasPermission('manage_users')) {
    echo "<div class='card'>Ingen adgang.</div>";
    exit;
}

// rolle-opdatering
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['role_id'])) {
    $stmt = $db->prepare("UPDATE users SET role_id = ? WHERE id = ?");
    $stmt->execute([$_POST['role_id'] ?: null, $_POST['user_id']]);
}

// hent data
$roles = $db->query("SELECT * FROM roles ORDER BY role_name ASC")->fetchAll();
$users = $db->query("
    SELECT u.*, r.role_name 
    FROM users u 
    LEFT JOIN roles r ON u.role_id = r.id
    ORDER BY u.id DESC
")->fetchAll();
?>

<?php require_once __DIR__ . '/../app/views/partials/header.php'; ?>

<div class="card">
    <h1>Brugeradministration</h1>

    <table>
        <tr>
            <th>ID</th>
            <th>Brugernavn</th>
            <th>Email</th>
            <th>Rolle</th>
            <th>Handling</th>
        </tr>

        <?php foreach ($users as $u): ?>
            <tr>
                <td><?= $u['id'] ?></td>
                <td><a href="/admin/profile.php?id=<?= $u['id'] ?>"><?= e($u['username']) ?></a></td>
                <td><?= e($u['email']) ?></td>
                <td><?= e($u['role_name'] ?? 'Ingen') ?></td>
                <td>
                    <form method="post">
                        <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                        <select name="role_id">
                            <option value="">Ingen</option>
                            <?php foreach ($roles as $r): ?>
                                <option value="<?= $r['id'] ?>" <?= $u['role_id'] == $r['id'] ? 'selected' : '' ?>>
                                    <?= e($r['role_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit">Gem</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php require_once __DIR__ . '/../app/views/partials/footer.php'; ?>
