<?php require_once __DIR__ . '/../partials/header.php'; ?>

<h1>Admin Dashboard</h1>

<div class="card">
    <h2>Hurtigt overblik</h2>

    <p><strong>Noter:</strong> <?= $noteCount ?></p>
    <p><strong>Kommentarer:</strong> <?= $commentCount ?></p>
    <p><strong>Brugere:</strong> <?= $userCount ?></p>
</div>

<div class="card">
    <h2>Noter oprettet pr. dag</h2>
    <canvas id="notesChart" height="100"></canvas>
</div>

<div class="card">
    <h2>Kommentarer pr. dag</h2>
    <canvas id="commentsChart" height="100"></canvas>
</div>

<div class="card">
    <h2>Mest aktive brugere</h2>
    <ul>
        <?php foreach ($topUsers as $u): ?>
            <li><?= e($u['username']) ?> — <?= $u['total'] ?> kommentarer</li>
        <?php endforeach; ?>
    </ul>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
const notesData    = <?= json_encode($notesPerDay) ?>;
const commentsData = <?= json_encode($commentsPerDay) ?>;

// Noter pr dag
new Chart(document.getElementById("notesChart"), {
    type: "line",
    data: {
        labels: notesData.map(r => r.day),
        datasets: [{
            label: "Noter",
            data: notesData.map(r => r.total),
            borderColor: "#60a5fa",
            tension: 0.3
        }]
    }
});

// Kommentarer pr dag
new Chart(document.getElementById("commentsChart"), {
    type: "line",
    data: {
        labels: commentsData.map(r => r.day),
        datasets: [{
            label: "Kommentarer",
            data: commentsData.map(r => r.total),
            borderColor: "#34d399",
            tension: 0.3
        }]
    }
});
</script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
