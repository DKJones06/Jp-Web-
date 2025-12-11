<?php require_once __DIR__ . '/../partials/header.php'; ?>

<h1>Audit Log</h1>

<div class="card">
    <table class="clean-table">
        <tr>
            <th>Bruger</th>
            <th>Handling</th>
            <th>Detaljer</th>
            <th>Tidspunkt</th>
        </tr>

        <?php foreach ($logs as $log): ?>
        <tr>
            <td><?= e($log['username']) ?></td>
            <td><?= e($log['action']) ?></td>
            <td><?= nl2br(e($log['details'])) ?></td>
            <td><?= $log['created_at'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
