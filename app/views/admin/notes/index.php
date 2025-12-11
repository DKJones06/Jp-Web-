<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="card">
    <h1>Noter</h1>

    <table class="clean-table">
        <tr>
            <th>ID</th>
            <th>Titel</th>
            <th>Forfatter</th>
            <th>Kategori</th>
            <th>Oprettet</th>
            <th>Handling</th>
        </tr>

        <?php foreach ($notes as $n): ?>
            <tr>
                <td><?= $n['id'] ?></td>

                <td>
                    <a href="/note?id=<?= $n['id'] ?>" target="_blank">
                        <?= e($n['title']) ?>
                    </a>
                </td>

                <td><?= e($n['username']) ?></td>

                <td><?= e($n['category_name'] ?? 'Ingen kategori') ?></td>

                <td><?= date("d/m/Y H:i", strtotime($n['created_at'])) ?></td>

                <td>
                    <a href="/note/edit?id=<?= $n['id'] ?>" class="btn-small edit-btn">
                        Rediger
                    </a>

                    <form method="post" action="/admin/notes/delete" style="display:inline;">
                        <?= csrf_field() ?> <!-- CSRF BESKYTTELSE -->
                        <input type="hidden" name="id" value="<?= $n['id'] ?>">
                        <button class="btn-small delete-btn" onclick="return confirm('Slet note?')">
                            Slet
                        </button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>

    </table>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
