<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="card">
    <h1>Kommentar-moderation</h1>

    <table class="clean-table">
        <tr>
            <th>Bruger</th>
            <th>Kommentar</th>
            <th>Note</th>
            <th>Oprettet</th>
            <th>Status</th>
            <th>Handling</th>
        </tr>

        <?php foreach ($comments as $c): ?>
        <tr style="<?= $c['is_deleted'] ? 'opacity:0.5;' : '' ?>">

            <!-- Avatar -->
            <td>
                <?php if (!empty($c['avatar'])): ?>
                    <img src="<?= AVATAR_URL . e($c['avatar']) ?>"
                        style="width:32px;height:32px;border-radius:50%;object-fit:cover;">
                <?php else: ?>
                    <div style="width:32px;height:32px;border-radius:50%;background:#1e293b;display:flex;align-items:center;justify-content:center;">
                        <?= strtoupper(substr($c['username'], 0, 1)) ?>
                    </div>
                <?php endif; ?>

                <?= e($c['username']) ?>
            </td>

            <!-- Indhold -->
            <td><?= nl2br(e($c['content'])) ?></td>

            <!-- Note -->
            <td><?= e($c['note_title']) ?></td>

            <!-- Timestamp -->
            <td><?= date("d/m/Y H:i", strtotime($c['created_at'])) ?></td>

            <!-- Status -->
            <td>
                <?= $c['is_deleted'] ? '<span style="color:#fca5a5;">Slettet</span>' : '<span style="color:#4ade80;">Aktiv</span>' ?>
            </td>

            <!-- Handlinger -->
            <td>
                <?php if (!$c['is_deleted']): ?>
                    <form method="post" action="/admin/comments/delete" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $c['id'] ?>">
                        <button style="background:#7f1d1d;">Slet</button>
                    </form>
                <?php else: ?>
                    <form method="post" action="/admin/comments/restore" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $c['id'] ?>">
                        <button style="background:#14532d;">Gendan</button>
                    </form>
                <?php endif; ?>
            </td>

        </tr>
        <?php endforeach; ?>

    </table>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
