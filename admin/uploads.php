<?php
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/core/auth.php';
require_once __DIR__ . '/../app/core/helpers.php';
require_once __DIR__ . '/../app/core/permissions.php';

<link rel="stylesheet" href="/public/assets/css/admin.css">

requireLogin();

// Kun folk der må styre noter kan uploade (du kan ændre til anden permission)
if (!hasPermission('manage_notes')) {
    echo "<div class='card'>Ingen adgang.</div>";
    exit;
}

// Håndter upload
$uploadError = '';
$uploadSuccess = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {

    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0775, true);
    }

    $file     = $_FILES['file'];
    $name     = $file['name'];
    $tmpName  = $file['tmp_name'];
    $size     = $file['size'];
    $error    = $file['error'];

    $allowedExt = ['png', 'jpg', 'jpeg', 'gif', 'webp', 'svg'];
    $maxSize    = 5 * 1024 * 1024; // 5MB

    if ($error !== UPLOAD_ERR_OK) {
        $uploadError = 'Fejl ved upload (kode: ' . $error . ')';
    } else {
        $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowedExt)) {
            $uploadError = 'Filtypen er ikke tilladt. Brug png, jpg, jpeg, gif, webp eller svg.';
        } elseif ($size > $maxSize) {
            $uploadError = 'Filen er for stor (max 5MB).';
        } else {
            // Generér unikt filnavn
            $newName = uniqid('note_', true) . '.' . $ext;
            $dest    = UPLOAD_DIR . $newName;

            if (move_uploaded_file($tmpName, $dest)) {
                // Gem i DB
                $stmt = $db->prepare("
                    INSERT INTO uploads (filename, original_name, uploaded_by)
                    VALUES (?, ?, ?)
                ");
                $stmt->execute([$newName, $name, $_SESSION['user_id']]);

                $uploadSuccess = 'Filen er uploadet!';
            } else {
                $uploadError = 'Kunne ikke flytte filen.';
            }
        }
    }
}

// Hent liste over uploads
$stmt = $db->query("
    SELECT u.*, usr.username 
    FROM uploads u
    JOIN users usr ON usr.id = u.uploaded_by
    ORDER BY u.created_at DESC
    LIMIT 100
");
$files = $stmt->fetchAll();

require_once __DIR__ . '/../app/views/partials/header.php';
?>

<div class="card">
    <h1>Upload diagram / billede</h1>

    <?php if ($uploadError): ?>
        <p style="color:red;"><?= e($uploadError) ?></p>
    <?php endif; ?>

    <?php if ($uploadSuccess): ?>
        <p style="color:green;"><?= e($uploadSuccess) ?></p>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <label>Vælg fil:</label><br>
        <input type="file" name="file" required><br><br>
        <button type="submit">Upload</button>
    </form>

    <p style="margin-top:10px;">
        Efter upload kan du indsætte billedet i en note med:<br>
        <code>&lt;img src="URLEN" alt=""&gt;</code>
    </p>
</div>

<div class="card">
    <h2>Seneste uploads</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Filnavn</th>
            <th>Uploadet af</th>
            <th>Dato</th>
            <th>URL</th>
            <th>Preview</th>
        </tr>

        <?php foreach ($files as $f): 
            $url = UPLOAD_URL . $f['filename'];
        ?>
            <tr>
                <td><?= $f['id'] ?></td>
                <td><?= e($f['original_name']) ?></td>
                <td><?= e($f['username']) ?></td>
                <td><?= e($f['created_at']) ?></td>
                <td>
                    <input type="text" value="<?= e($url) ?>" readonly
                           onclick="this.select();" style="width:100%;">
                </td>
                <td>
                    <img src="<?= e($url) ?>" alt="" style="max-height:60px;">
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<?php require_once __DIR__ . '/../app/views/partials/footer.php'; ?>
