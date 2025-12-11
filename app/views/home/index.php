<?php require_once __DIR__ . '/../partials/header.php'; ?>

<!-- HERO SEKTION -->
<div class="hero">
    <h1>JP's Noter</h1>
    <p>Din samling af matematik-, fysik- og programmeringsnoter.</p>

    <form class="hero-search" action="/" method="get">
        <input type="text" name="search" placeholder="Søg i noter..." value="<?= e($search) ?>">
        <button>Søg</button>
    </form>

    <!-- Kategori chips -->
    <div class="category-chips">
        <a href="/" class="<?= empty($category) ? 'chip-active' : 'chip' ?>">Alle</a>

        <?php foreach ($categories as $c): ?>
            <a href="/?category=<?= $c['id'] ?>" 
               class="<?= $category == $c['id'] ? 'chip-active' : 'chip' ?>">
                <?= e($c['name']) ?>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<!-- NOTE GRID -->
<div class="note-grid">

<?php if (empty($notes)): ?>
    <p style="opacity:0.7;">Ingen noter fundet.</p>
<?php endif; ?>

<?php foreach ($notes as $n): ?>
    <a href="/note?id=<?= $n['id'] ?>" class="note-card">
        
        <h3><?= e($n['title']) ?></h3>

        <p class="note-preview">
            <?= e(substr(strip_tags($n['content']), 0, 140)) ?>...
        </p>

        <div class="note-meta">
            <span class="chip"><?= e($n['category_name'] ?? 'Kategori?') ?></span>

            <span class="note-date">
                <?= date("d/m/Y", strtotime($n['created_at'])) ?>
            </span>
        </div>

    </a>
<?php endforeach; ?>

</div>

<div class="tag-list">
    <?php foreach (NoteTag::getTagsForNote($n['id']) as $t): ?>
        <span class="tag-chip" style="background: <?= $t['color'] ?>;">
            <?= e($t['name']) ?>
        </span>
    <?php endforeach; ?>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
