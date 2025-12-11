<div class="card">
    <h1>Rediger kategori</h1>

    <?php if (!empty($error)): ?>
        <p style="color:#fca5a5;"><?= e($error) ?></p>
    <?php endif; ?>

    <form method="post" action="/admin/categories/edit">
        <?= csrf_field() ?>
        
        <input type="hidden" name="id" value="<?= $category['id'] ?>">

        <label>Navn</label>
        <input type="text" name="name" value="<?= e($category['name']) ?>">

        <label>Slug</label>
        <input type="text" name="slug" value="<?= e($category['slug']) ?>">

        <button type="submit">Gem ændringer</button>
    </form>
</div>
