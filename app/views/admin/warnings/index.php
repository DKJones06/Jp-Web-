<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="card">
    <h1>Advarsler</h1>

    <?php if (isset($_GET['user'])): ?>
        <p>
            Viser advarsler for bruger-ID: <strong><?= (int)$_GET['user'] ?></strong>
        </p>
    <?php endif; ?>

    <a href="/admin/warnings/create" class="shortcut-btn" style="margin-bottom:20px; display:inline-block;">
        ➕ Opret ny advarsel
    </a>

    <table class="clean-table">
        <tr>
            <th>ID</th>
            <th>Bruger</th>
            <th>Admin</th>
            <th>Tekst</th>
            <th>Oprettet</th>
            <th>Handling</th>
        </tr>

        <?php foreach ($warnings as $w): ?>
            <tr>
                <td><?= $w['id'] ?></td>
                <td><?= e($w['user_name']) ?></td>
                <td><?= e($w['admin_name']) ?></td>
                <td><?= e($w['warning_text']) ?></td>
                <td><?= date("d/m/Y H:i", strtotime($w['created_at'])) ?></td>

                <td>
                    <form method="post" action="/admin/warnings/delete" style="display:inline;">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= $w['id'] ?>">
                        <button onclick="return confirm('Slet denne advarsel?')" style="background:#7f1d1d;">
                            Slet
                        </button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
