<?php
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/core/auth.php';
require_once __DIR__ . '/../app/core/helpers.php';
require_once __DIR__ . '/../app/core/permissions.php';

require_once __DIR__ . '/../app/models/Warning.php';
require_once __DIR__ . '/../app/models/Timeout.php';

<link rel="stylesheet" href="/public/assets/css/admin.css">

requireLogin();

if (!hasPermission('moderate_comments')) {
    echo "<div class='card'>Ingen adgang.</div>";
    exit;
}

// handlinger
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if ($_POST['action'] === 'delete') {
        $stmt = $db->prepare("UPDATE comments SET is_deleted = 1 WHERE id = ?");
        $stmt->execute([$_POST['comment_id']]);
    }

    if ($_POST['action'] === 'warn' && hasPermission('warn_user')) {
        WarningModel::giveWarning($_POST['user_id'], $_SESSION['user_id'], $_POST['reason']);
    }

    if ($_POST['action'] === 'timeout' && hasPermission('timeout_user')) {
        TimeoutModel::giveTimeout($_POST['user_id'], $_SESSION['user_id'], $_POST['reason'], $_POST['minutes']);
    }

    redirect('/admin/comments.php');
}

$comments = $db->query("
    SELECT c.*, u.username, n.title AS note_title
    FROM comments c
    JOIN users u ON c.user_id = u.id
    JOIN notes n ON c.note_id = n.id
    ORDER BY c.created_at DESC
    LIMIT 100
")->fetchAll();
?>

<?php require_once __DIR__ . '/../app/views/partials/header.php'; ?>

<div class="card">
    <h1>Kommentar-moderation</h1>

    <table>
        <tr>
            <th>ID</th>
            <th>Bruger</th>
            <th>Note</th>
            <th>Kommentar</th>
            <th>Handlinger</th>
        </tr>

        <?php foreach ($comments as $c): ?>
            <tr>
                <td><?= $c['id'] ?></td>
                <td><a href="/admin/profile.php?id=<?= $c['user_id'] ?>"><?= e($c['username']) ?></a></td>
                <td><?= e($c['note_title']) ?></td>
                <td><?= nl2br(e($c['content'])) ?></td>

                <td>

                    <!-- Slet -->
                    <form method="post">
                        <input type="hidden" name="comment_id" value="<?= $c['id'] ?>">
                        <button name="action" value="delete">Slet</button>
                    </form>

                    <!-- Advar -->
                    <?php if (hasPermission('warn_user')): ?>
                        <form method="post">
                            <input type="hidden" name="user_id" value="<?= $c['user_id'] ?>">
                            <textarea name="reason" placeholder="Advarsel..." required></textarea>
                            <button name="action" value="warn">Advar</button>
                        </form>
                    <?php endif; ?>

                    <!-- Timeout -->
                    <?php if (hasPermission('timeout_user')): ?>
                        <form method="post">
                            <input type="hidden" name="user_id" value="<?= $c['user_id'] ?>">
                            <input type="number" name="minutes" placeholder="Minutter" required>
                            <textarea name="reason" placeholder="Grund..." required></textarea>
                            <button name="action" value="timeout">Timeout</button>
                        </form>
                    <?php endif; ?>

                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php require_once __DIR__ . '/../app/views/partials/footer.php'; ?>
