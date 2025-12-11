<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="card">
    <h1>Kategorier</h1>

    <a href="/admin/categories/create" class="shortcut-btn" style="margin-bottom:20px; display:inline-block;">
        ➕ Ny kategori
    </a>

    <table class="clean-table">
        <tr>
            <th>Navn</th>
            <th>Slug</th>
            <th>Handling</th>
        </tr>

        <?php foreach ($categories as $c): ?>
        <tr>
            <td><?= e($c['name']) ?></td>
            <td><?= e($c['slug']) ?></td>
            <td>
                <a href="/admin/categories/edit?id=<?= $c['id'] ?>">Rediger</a>
                <form action="/admin/categories/delete" method="post" style="display:inline;">
                    <?= csrf_field() ?>
                    <input type="hidden" name="id" value="<?= $c['id'] ?>">
                    <button onclick="return confirm('Slet kategori?');" style="background:#7f1d1d;">
                        Slet
                    </button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
