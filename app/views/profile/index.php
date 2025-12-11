<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="card">
    <h1>Min Profil</h1>

    <div style="display:flex; align-items:center; gap:20px;">

        <!-- Avatar -->
        <?php if (!empty($user['avatar'])): ?>
            <img src="/avatars/<?= e($user['avatar']) ?>"
                 style="width:120px;height:120px;border-radius:50%;object-fit:cover;">
        <?php else: ?>
            <div style="
                width:120px; height:120px; border-radius:50%;
                background:#1e293b; display:flex; align-items:center;
                justify-content:center; font-size:3rem; color:#93c5fd;">
                <?= strtoupper(substr($user['username'], 0, 1)) ?>
            </div>
        <?php endif; ?>

        <div>
            <p><strong>Brugernavn:</strong> <?= e($user['username']) ?></p>
            <p><strong>Email:</strong> <?= e($user['email']) ?></p>
        </div>

    </div>

    <hr>

    <h2>Skift avatar</h2>

    <form method="post" action="/profile/upload-avatar" enctype="multipart/form-data">
         <?= csrf_field() ?>
        <input type="file" name="avatar" required>
        <button type="submit">Upload Avatar</button>
    </form>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
