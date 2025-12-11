<div class="card">
    <h1>Opret kategori</h1>

    <?php if (!empty($error)): ?>
        <p style="color:#fca5a5;"><?= e($error) ?></p>
    <?php endif; ?>

    <form method="post" action="/admin/categories/create">
        <?= csrf_field() ?>
        
        <label>Navn</label>
        <input type="text" name="name">

        <label>Slug</label>
        <input type="text" name="slug" placeholder="eks: differentialregning">

        <button type="submit">Opret</button>
    </form>
</div>
