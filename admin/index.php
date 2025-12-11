<?php
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/core/auth.php';
require_once __DIR__ . '/../app/core/helpers.php';
require_once __DIR__ . '/../app/core/permissions.php';

<link rel="stylesheet" href="/public/assets/css/admin.css">

requireLogin();

// Tjek adgang
if (
    !hasPermission('manage_notes') &&
    !hasPermission('manage_users') &&
    !hasPermission('manage_roles') &&
    !hasPermission('moderate_comments')
) {
    echo "<div class='card'><p>Ingen adgang til admin-panel.</p></div>";
    exit;
}

// Overordnet stats
$userCount     = $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
$noteCount     = $db->query("SELECT COUNT(*) FROM notes")->fetchColumn();
$commentCount  = $db->query("SELECT COUNT(*) FROM comments")->fetchColumn();
$uploadCount   = $db->query("SELECT COUNT(*) FROM uploads")->fetchColumn();

// Noter pr. kategori (til doughnut-chart)
$catStmt = $db->query("
    SELECT COALESCE(c.name, 'Uden kategori') AS name, COUNT(n.id) AS cnt
    FROM notes n
    LEFT JOIN categories c ON n.category_id = c.id
    GROUP BY c.id, c.name
    ORDER BY cnt DESC
");
$catDataRaw = $catStmt->fetchAll();

$catLabels = [];
$catValues = [];
foreach ($catDataRaw as $row) {
    $catLabels[] = $row['name'];
    $catValues[] = (int)$row['cnt'];
}

// Noter pr. måned (sidste 6 måneder) til line-chart
$monthStmt = $db->query("
    SELECT DATE_FORMAT(created_at, '%Y-%m') AS ym, COUNT(*) AS cnt
    FROM notes
    GROUP BY ym
    ORDER BY ym ASC
");
$monthRaw = $monthStmt->fetchAll();

$monthLabels = [];
$monthValues = [];
foreach ($monthRaw as $row) {
    $monthLabels[] = $row['ym'];
    $monthValues[] = (int)$row['cnt'];
}

// Seneste aktivitet (noter + kommentarer)
$recentNotes = $db->query("
    SELECT n.id, n.title, u.username, n.created_at
    FROM notes n
    JOIN users u ON n.author_id = u.id
    ORDER BY n.created_at DESC
    LIMIT 5
")->fetchAll();

$recentComments = $db->query("
    SELECT c.id, c.content, u.username, n.title AS note_title, c.created_at
    FROM comments c
    JOIN users u ON c.user_id = u.id
    JOIN notes n ON c.note_id = n.id
    ORDER BY c.created_at DESC
    LIMIT 5
")->fetchAll();

require_once __DIR__ . '/../app/views/partials/header.php';
?>

<div class="admin-layout">

    <div class="admin-header">
        <div>
            <h1>JP Admin Dashboard</h1>
            <p>Overblik over brugere, noter, kommentarer og filer.</p>
        </div>
        <div class="admin-user">
            <span>Logget ind som</span>
            <strong><?= e(currentUser()['username']) ?></strong>
        </div>
    </div>

    <!-- Stat cards -->
    <div class="dashboard-grid">
        <div class="stat-card stat-users">
            <div class="stat-label">Brugere</div>
            <div class="stat-value"><?= $userCount ?></div>
            <div class="stat-sub">Registrerede brugere</div>
        </div>

        <div class="stat-card stat-notes">
            <div class="stat-label">Noter</div>
            <div class="stat-value"><?= $noteCount ?></div>
            <div class="stat-sub">Matematiske / EL-noter</div>
        </div>

        <div class="stat-card stat-comments">
            <div class="stat-label">Kommentarer</div>
            <div class="stat-value"><?= $commentCount ?></div>
            <div class="stat-sub">Diskussioner & spørgsmål</div>
        </div>

        <div class="stat-card stat-uploads">
            <div class="stat-label">Uploads</div>
            <div class="stat-value"><?= $uploadCount ?></div>
            <div class="stat-sub">Diagrammer og billeder</div>
        </div>
    </div>

    <!-- Charts -->
    <div class="dashboard-charts">
        <div class="chart-card">
            <h2>Noter pr. kategori</h2>
            <canvas id="categoryChart"></canvas>
        </div>

        <div class="chart-card">
            <h2>Noter over tid</h2>
            <canvas id="notesOverTimeChart"></canvas>
        </div>
    </div>

    <!-- Aktivitet -->
    <div class="dashboard-activity">
        <div class="card activity-card">
            <h2>Seneste noter</h2>
            <table class="clean-table">
                <thead>
                    <tr>
                        <th>Titel</th>
                        <th>Forfatter</th>
                        <th>Dato</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($recentNotes as $n): ?>
                    <tr>
                        <td>
                            <a href="/public/note.php?id=<?= $n['id'] ?>">
                                <?= e($n['title']) ?>
                            </a>
                        </td>
                        <td><?= e($n['username']) ?></td>
                        <td><?= e($n['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="card activity-card">
            <h2>Seneste kommentarer</h2>
            <table class="clean-table">
                <thead>
                    <tr>
                        <th>Bruger</th>
                        <th>Note</th>
                        <th>Kommentar</th>
                        <th>Dato</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($recentComments as $c): ?>
                    <tr>
                        <td><?= e($c['username']) ?></td>
                        <td><?= e($c['note_title']) ?></td>
                        <td><?= e(mb_substr($c['content'], 0, 50)) ?><?= mb_strlen($c['content']) > 50 ? '…' : '' ?></td>
                        <td><?= e($c['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Genveje -->
    <div class="card admin-shortcuts">
        <h2>Genveje</h2>
        <div class="shortcut-grid">
            <a href="/admin/notes.php" class="shortcut-btn">📘 Administrer noter</a>
            <a href="/admin/categories.php" class="shortcut-btn">🏷 Kategorier</a>
            <a href="/admin/users.php" class="shortcut-btn">👤 Brugere & roller</a>
            <a href="/admin/comments.php" class="shortcut-btn">💬 Moderér kommentarer</a>
            <a href="/admin/uploads.php" class="shortcut-btn">🖼 Uploads</a>
            <a href="/" class="shortcut-btn">🏠 Til forsiden</a>
        </div>
    </div>
</div>

<?php
// Data til JavaScript grafer
$catLabelsJson   = json_encode($catLabels);
$catValuesJson   = json_encode($catValues);
$monthLabelsJson = json_encode($monthLabels);
$monthValuesJson = json_encode($monthValues);
?>

<!-- Chart.js (kun her på admin-dashboard) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const catCtx = document.getElementById('categoryChart').getContext('2d');
const catChart = new Chart(catCtx, {
    type: 'doughnut',
    data: {
        labels: <?= $catLabelsJson ?>,
        datasets: [{
            data: <?= $catValuesJson ?>,
        }]
    },
    options: {
        plugins: {
            legend: { position: 'bottom' }
        }
    }
});

const timeCtx = document.getElementById('notesOverTimeChart').getContext('2d');
const timeChart = new Chart(timeCtx, {
    type: 'line',
    data: {
        labels: <?= $monthLabelsJson ?>,
        datasets: [{
            label: 'Noter oprettet',
            data: <?= $monthValuesJson ?>,
            tension: 0.3
        }]
    },
    options: {
        scales: {
            y: { beginAtZero: true, ticks: { precision: 0 } }
        }
    }
});
</script>

<?php require_once __DIR__ . '/../app/views/partials/footer.php'; ?>
