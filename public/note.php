<?php
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/core/auth.php';
require_once __DIR__ . '/../app/core/helpers.php';
require_once __DIR__ . '/../app/core/permissions.php';

require_once __DIR__ . '/../app/models/Note.php';
require_once __DIR__ . '/../app/models/Comment.php';
require_once __DIR__ . '/../app/models/Timeout.php';

$noteId = $_GET['id'];
$note = Note::find($noteId);
$comments = Note::comments($noteId);

$timeoutActive = isLoggedIn() ? isTimedOut($_SESSION['user_id']) : false;

// kommentar submit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isLoggedIn()) {

    if ($timeoutActive) {
        $err = "Du er i timeout.";
    } else {
        Comment::create($noteId, $_SESSION['user_id'], $_POST['comment']);
        redirect("/public/note.php?id=" . $noteId);
    }
}

require_once __DIR__ . '/../app/views/partials/header.php';
?>

<div class="card">
    <h1><?= e($note['title']) ?></h1>
    <p><small>Af <?= e($note['author_name']) ?></small></p>
    <div><?= nl2br(e($note['content'])) ?></div>
</div>

<div class="card">
    <h2>Kommentarer</h2>

    <?php foreach ($comments as $c): ?>
        <?php if (!$c['is_deleted']): ?>
            <div class="comment">
                <strong><?= e($c['username']) ?></strong><br>
                <?= nl2br(e($c['content'])) ?>
            </div>
            <hr>
        <?php endif; ?>
    <?php endforeach; ?>

    <?php if (isLoggedIn()): ?>
        <?php if (!$timeoutActive): ?>
            <form method="post">
                <textarea name="comment" placeholder="Skriv en kommentar..." required></textarea>
                <button type="submit">Send</button>
            </form>
        <?php else: ?>
            <p style="color:red;">Du er i timeout og kan ikke kommentere.</p>
        <?php endif; ?>
    <?php else: ?>
        <p><a href="/public/login.php">Log ind for at kommentere</a></p>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/../app/views/partials/footer.php'; ?>
