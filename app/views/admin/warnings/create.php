<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="card form-wrapper">
    <h1>Opret advarsel</h1>

    <?php if (!empty($error)): ?>
        <p style="color:#fca5a5;"><?= e($error) ?></p>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <p style="color:#4ade80;"><?= e($success) ?></p>
    <?php endif; ?>

    <form method="post" action="/admin/warnings/create">
        <?= csrf_field() ?>

        <label for="user_id">Bruger</label>
        <select name="user_id" required>
            <?php foreach ($users as $u): ?>
                <option value="<?= $u['id'] ?>">
                    <?= e($u['username']) ?> (ID: <?= $u['id'] ?>)
                </option>
            <?php endforeach; ?>
        </select>

        <label for="warning_text">Advarselstekst</label>
        <textarea name="warning_text" rows="5" required></textarea>

        <button type="submit">Opret advarsel</button>
    </form>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
