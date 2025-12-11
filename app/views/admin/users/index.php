<?php require_once __DIR__ . '/../partials/header.php'; ?>

<h1>Brugeradministration</h1>

<div class="card">

<table class="clean-table">
    <tr>
        <th>Avatar</th>
        <th>Brugernavn</th>
        <th>Email</th>
        <th>Rolle</th>
        <th>Handlinger</th>
    </tr>

<?php foreach ($users as $u): ?>
    <tr>

        <td>
            <?php if (!empty($u['avatar'])): ?>
                <img src="/avatars/<?= e($u['avatar']) ?>"
                     style="width:42px; height:42px; border-radius:50%; object-fit:cover;">
            <?php else: ?>
                <div style="
                    width:42px;height:42px;border-radius:50%;
                    background:#1e293b;display:flex;align-items:center;
                    justify-content:center;">
                    <?= strtoupper(substr($u['username'],0,1)) ?>
                </div>
            <?php endif; ?>
        </td>

        <td><?= e($u['username']) ?></td>
        <td><?= e($u['email']) ?></td>

        <td>
            <form method="post" action="/admin/users/update-role" style="display:flex; gap:8px;">
                <?= csrf_field() ?> 
                <input type="hidden" name="user_id" value="<?= $u['id'] ?>">
                <select name="role_id">
                    <?php foreach ($roles as $r): ?>
                        <option value="<?= $r['id'] ?>" <?= $u['role_id']==$r['id']?'selected':'' ?>>
                            <?= e($r['role_name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button>Opdater</button>
            </form>
        </td>

        <td>
            <a href="/admin/warnings?user=<?= $u['id'] ?>">Advarsler</a> |
            <a href="/admin/timeouts?user=<?= $u['id'] ?>">Timeouts</a>
        </td>

    </tr>
<?php endforeach; ?>

</table>

</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
