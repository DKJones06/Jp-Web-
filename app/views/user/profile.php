<div class="card">
    <h1>Min profil</h1>

    <div style="display:flex; gap:20px; align-items:flex-start; flex-wrap:wrap;">
        <div>
            <h3>Avatar</h3>
            <?php if (!empty($user['avatar'])): ?>
                <img src="<?= AVATAR_URL . e($user['avatar']) ?>"
                     alt="Avatar"
                     style="width:120px;height:120px;border-radius:50%;object-fit:cover;border:2px solid #3b82f6;">
            <?php else: ?>
                <div style="width:120px;height:120px;border-radius:50%;background:#1e293b;display:flex;align-items:center;justify-content:center;font-size:2rem;">
                    <?= strtoupper(substr($user['username'], 0, 1)) ?>
                </div>
            <?php endif; ?>
        </div>

        <div style="flex:1;min-width:260px;">
            <h3>Bruger-info</h3>
            <p><strong>Brugernavn:</strong> <?= e($user['username']) ?></p>
            <p><strong>Email:</strong> <?= e($user['email']) ?></p>
        </div>
    </div>
</div>

<div class="card form-wrapper">
    <h2>Opdater avatar</h2>

    <?php if (!empty($error)): ?>
        <p style="color:#fca5a5;"><?= e($error) ?></p>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <p style="color:#4ade80;"><?= e($success) ?></p>
    <?php endif; ?>

    <form method="post" action="/profile/avatar" enctype="multipart/form-data">
         <?= csrf_field() ?>
         
        <label for="avatar">Vælg billede (max 2MB)</label>
        <input type="file" name="avatar" id="avatar" required>
        <button type="submit">Gem avatar</button>
    </form>
</div>
