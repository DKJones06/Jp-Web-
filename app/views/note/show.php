<?php require_once __DIR__ . '/../partials/header.php'; ?>
<?php require_once __DIR__ . '/../../core/markdown.php'; ?>

<div class="card">
    <h1><?= e($note['title']) ?></h1>

    <p style="opacity:0.8;">
        <strong>Kategori:</strong>
        <?= e($note['category_name'] ?? 'Ingen kategori') ?><br>

        <strong>Forfatter:</strong>
        <?= e($note['username']) ?><br>

        <strong>Oprettet:</strong>
        <?= date("d/m/Y H:i", strtotime($note['created_at'])) ?>
    </p>

    <!-- TAGS -->
    <?php $tags = NoteTag::getTagsForNote($note['id']); ?>
    <?php if (!empty($tags)): ?>
        <div style="margin:10px 0 20px;">
            <?php foreach ($tags as $t): ?>
                <span class="tag-chip" style="background: <?= $t['color'] ?>;">
                    <?= e($t['name']) ?>
                </span>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <hr>

    <!-- NOTE INDHOLD -->
    <div id="note-content">
        <?= md($note['content']) ?>
    </div>

    <script>
        if (window.MathJax && MathJax.typesetPromise) {
            MathJax.typesetPromise();
        }
    </script>
</div>

<!-- KOMMENTARER ---------------------------------------------------------------- -->
<div class="card">
    <h2>Kommentarer</h2>

    <?php foreach ($comments as $c): ?>
        <div class="comment"
            style="display:flex; gap:12px; margin-bottom:18px; <?= $c['is_deleted'] ? 'opacity:0.5;' : '' ?>">

            <!-- Avatar -->
            <?php if (!empty($c['avatar'])): ?>
                <img src="<?= AVATAR_URL . e($c['avatar']) ?>"
                     style="width:42px;height:42px;border-radius:50%;object-fit:cover;">
            <?php else: ?>
                <div style="
                    width:42px;height:42px;border-radius:50%;
                    background:#1e293b; display:flex;
                    align-items:center; justify-content:center;
                    font-size:1.1rem;">
                    <?= strtoupper(substr($c['username'], 0, 1)) ?>
                </div>
            <?php endif; ?>

            <!-- Indhold -->
            <div style="flex:1;">
                <strong><?= e($c['username']) ?></strong><br>

                <?php if ($c['is_deleted']): ?>
                    <em style="color:#fca5a5;">Denne kommentar er slettet af en moderator</em>
                <?php else: ?>
                    <?= nl2br(e($c['content'])) ?>
                <?php endif; ?>

                <div style="margin-top:6px; font-size:0.8rem; opacity:0.7;">
                    <?= date("d/m/Y H:i", strtotime($c['created_at'])) ?>
                </div>
            </div>
        </div>
        <hr>
    <?php endforeach; ?>

    <?php if (empty($comments)): ?>
        <p>Ingen kommentarer endnu.</p>
    <?php endif; ?>
</div>

<!-- NY KOMMENTAR ---------------------------------------------------------------- -->
<?php if (isLoggedIn()): ?>
<div class="card">
    <h2>Skriv en kommentar</h2>

    <form method="post" action="/note/comment">
         <?= csrf_field() ?>
        <input type="hidden" name="note_id" value="<?= $note['id'] ?>">

        <textarea name="content" rows="5" required></textarea>

        <button type="submit">Send kommentar</button>
    </form>
</div>
<?php else: ?>
<div class="card">
    <p>Du skal være <a href="/login">logget ind</a> for at kommentere.</p>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
