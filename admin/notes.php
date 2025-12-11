<?php
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/core/auth.php';
require_once __DIR__ . '/../app/core/helpers.php';
require_once __DIR__ . '/../app/core/permissions.php';

<link rel="stylesheet" href="/public/assets/css/admin.css">

requireLogin();

if (!hasPermission('manage_notes')) {
    echo "<div class='card'>Ingen adgang.</div>";
    exit;
}

$action = $_GET['action'] ?? 'list';
$noteId = $_GET['id'] ?? null;
$cats = $db->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();

// opret/rediger
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = ($action === 'edit')
        ? $db->prepare("UPDATE notes SET title=?, content=?, category_id=?, updated_at=NOW() WHERE id=?")
        : $db->prepare("INSERT INTO notes (title, content, category_id, author_id) VALUES (?, ?, ?, ?)");

    $params = [
        $_POST['title'],
        $_POST['content'],
        $_POST['category_id'] ?: null
    ];

    if ($action === 'edit') {
        $params[] = $noteId;
    } else {
        $params[] = $_SESSION['user_id'];
    }

    $stmt->execute($params);
    redirect('/admin/notes.php');
}

// slet
if ($action === 'delete') {
    $stmt = $db->prepare("DELETE FROM notes WHERE id = ?");
    $stmt->execute([$noteId]);
    redirect('/admin/notes.php');
}

require_once __DIR__ . '/../app/views/partials/header.php';
?>

<?php if ($action === 'new' || $action === 'edit'): ?>

    <?php
    $note = ['title'=>'','content'=>'','category_id'=>null];

    if ($action === 'edit') {
        $stmt = $db->prepare("SELECT * FROM notes WHERE id=?");
        $stmt->execute([$noteId]);
        $note = $stmt->fetch();
    }
    ?>

    <div class="card">
        <h1><?= $action === 'edit' ? 'Rediger note' : 'Ny note' ?></h1>

        <form method="post">
            <label>Titel</label><br>
            <input type="text" name="title" value="<?= e($note['title']) ?>" required><br><br>

            <label>Kategori</label><br>
            <select name="category_id">
                <option value="">Ingen</option>
                <?php foreach ($cats as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= $note['category_id']==$c['id']?'selected':'' ?>>
                        <?= e($c['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select><br><br>

            <label>Indhold</label><br>
            <textarea name="content" rows="10"><?= e($note['content']) ?></textarea><br><br>

            <button type="submit">Gem</button>
        </form>
    </div>

<?php else: ?>

    <?php
    $notes = $db->query("
        SELECT n.*, u.username, c.name AS category_name
        FROM notes n
        JOIN users u ON n.author_id = u.id
        LEFT JOIN categories c ON n.category_id = c.id
        ORDER BY n.created_at DESC
    ")->fetchAll();
    ?>

    <div class="card">
        <h1>Noter</h1>
        <a href="/admin/notes.php?action=new">+ Ny note</a>

        <table>
            <tr>
                <th>ID</th>
                <th>Titel</th>
                <th>Forfatter</th>
                <th>Kategori</th>
                <th>Handling</th>
            </tr>

            <?php foreach ($notes as $n): ?>
                <tr>
                    <td><?= $n['id'] ?></td>
                    <td><?= e($n['title']) ?></td>
                    <td><?= e($n['username']) ?></td>
                    <td><?= e($n['category_name'] ?? '') ?></td>
                    <td>
                        <a href="/public/note.php?id=<?= $n['id'] ?>">Se</a> ·
                        <a href="/admin/notes.php?action=edit&id=<?= $n['id'] ?>">Rediger</a> ·
                        <a href="/admin/notes.php?action=delete&id=<?= $n['id'] ?>">Slet</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

<?php endif; ?>

<?php require_once __DIR__ . '/../app/views/partials/footer.php'; ?>
