<?php
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/core/auth.php';
require_once __DIR__ . '/../app/core/helpers.php';
require_once __DIR__ . '/../app/core/permissions.php';

require_once __DIR__ . '/../app/models/Warning.php';
require_once __DIR__ . '/../app/models/Timeout.php';

<link rel="stylesheet" href="/public/assets/css/admin.css">

requireLogin();

$userId = $_GET['id'];

$stmt = $db->prepare("
    SELECT u.*, r.role_name
    FROM users u
    LEFT JOIN roles r ON u.role_id = r.id
    WHERE u.id = ?
");
$stmt->execute([$userId]);
$user = $stmt->fetch();

$warnings = WarningModel::getWarnings($userId);
$timeout  = TimeoutModel::getTimeout($userId);
?>

<?php require_once __DIR__ . '/../app/views/partials/header.php'; ?>

<div class="card">
    <h1>Profil: <?= e($user['username']) ?></h1>

    <p>Email: <?= e($user['email']) ?></p>
    <p>Rolle: <?= e($user['role_name'] ?? 'Ingen') ?></p>

    <?php if ($timeout && strtotime($timeout['expires_at']) > time()): ?>
        <p style="color:red;">
            Aktiv timeout indtil: <?= e($timeout['expires_at']) ?><br>
            Grund: <?= e($timeout['reason']) ?>
        </p>
    <?php endif; ?>
</div>

<div class="card">
    <h2>Advarsler</h2>

    <?php if (!$warnings): ?>
        <p>Ingen advarsler.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($warnings as $w): ?>
                <li>
                    <?= e($w['created_at']) ?> – 
                    <?= nl2br(e($w['warning_text'])) ?>  
                    (af: <?= e($w['by_admin']) ?>)
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../app/views/partials/footer.php'; ?>
