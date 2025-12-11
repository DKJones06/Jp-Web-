<?php require_once __DIR__ . '/../partials/header.php'; ?>
<?php require_once __DIR__ . '/../../core/markdown.php'; ?>

<div class="card">
    <h1>Opret ny note</h1>

    <form action="/note/create" method="post">
    <?= csrf_field() ?>
        <label>Titel</label>
        <input type="text" name="title" required>

        <label>Kategori</label>
        <select name="category_id" required>
            <option value="">Vælg kategori</option>
            <?php foreach ($categories as $c): ?>
                <option value="<?= $c['id'] ?>"><?= e($c['name']) ?></option>
            <?php endforeach; ?>
        </select>

        <label>Indhold (Markdown + LaTeX)</label>

        <!-- TOOLBAR -->
        <div class="toolbar">
            <!-- Markdown -->
            <button type="button" data-snippet="**tekst**">Bold</button>
            <button type="button" data-snippet="*tekst*">Italic</button>
            <button type="button" data-snippet="`kode`">Code</button>
            <button type="button" data-snippet="\n- punkt">Liste</button>
            <button type="button" data-snippet="\n# Overskrift">H1</button>

            <!-- LaTeX -->
            <button type="button" data-snippet="\\( x^2 \\)">Inline</button>
            <button type="button" data-snippet="\\[ x^2 \\]">Block</button>
            <button type="button" data-snippet="\\frac{a}{b}">Brøk</button>
            <button type="button" data-snippet="\\sum_{n=1}^{\\infty} ">Σ Sum</button>
            <button type="button" data-snippet="\\int_{a}^{b} f(x) dx">∫ Integral</button>
        </div>

        <textarea id="editor" name="content" rows="10" required></textarea>

        <button type="submit">Gem note</button>
    </form>
</div>

<!-- PREVIEW -->
<div class="card">
    <h2>Preview</h2>
    <div id="preview"></div>
</div>

<script>
const editor = document.getElementById("editor");
const preview = document.getElementById("preview");

function insertSnippet(text) {
    const start = editor.selectionStart;
    const end = editor.selectionEnd;
    editor.value =
        editor.value.substring(0, start) +
        text +
        editor.value.substring(end);
    editor.focus();
    updatePreview();
}

document.querySelectorAll(".toolbar button").forEach(btn => {
    btn.addEventListener("click", () => insertSnippet(btn.dataset.snippet));
});

function updatePreview() {
    fetch('/preview-markdown', {
        method: "POST",
        headers: {"Content-Type": "application/x-www-form-urlencoded"},
        body: "text=" + encodeURIComponent(editor.value)
    })
    .then(res => res.text())
    .then(html => {
        preview.innerHTML = html;
        if (window.MathJax && MathJax.typesetPromise) MathJax.typesetPromise();
    });
}

editor.addEventListener("input", updatePreview);
updatePreview();
</script>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>
