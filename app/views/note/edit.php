<?php require_once __DIR__ . '/../partials/header.php'; ?>
<?php require_once __DIR__ . '/../../core/markdown.php'; ?>

<div class="card">
    <h1>Rediger note</h1>

    <form action="/note/edit" method="post">
         <?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= $note['id'] ?>">

        <label>Titel</label>
        <input type="text" name="title" value="<?= e($note['title']) ?>" required>

        <label>Kategori</label>
        <select name="category_id" required>
            <?php foreach ($categories as $c): ?>
                <option value="<?= $c['id'] ?>" <?= $note['category_id'] == $c['id'] ? 'selected' : '' ?>>
                    <?= e($c['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label>Indhold (Markdown + LaTeX)</label>

        <!-- TOOLBAR -->
        <div class="toolbar">

    <!-- Markdown ikoner -->
    <button type="button" data-snippet="**tekst**">
        <svg viewBox="0 0 24 24">
            <text x="4" y="17" font-size="16" font-weight="bold">B</text>
        </svg>
    </button>

    <button type="button" data-snippet="*tekst*">
        <svg viewBox="0 0 24 24">
            <text x="6" y="17" font-size="16" font-style="italic">I</text>
        </svg>
    </button>

    <button type="button" data-snippet="`kode`">
        <svg viewBox="0 0 24 24">
            <polyline points="7 8 3 12 7 16" stroke-width="2" stroke="currentColor" fill="none"/>
            <polyline points="17 8 21 12 17 16" stroke-width="2" stroke="currentColor" fill="none"/>
        </svg>
    </button>

    <button type="button" data-snippet="\n- punkt">
        <svg viewBox="0 0 24 24">
            <circle cx="6" cy="12" r="2"/>
            <line x1="10" y1="12" x2="21" y2="12" stroke-width="2" stroke="currentColor"/>
        </svg>
    </button>

    <button type="button" data-snippet="\n# Overskrift">
        <svg viewBox="0 0 24 24">
            <text x="3" y="17" font-size="18" font-weight="bold">H1</text>
        </svg>
    </button>

    <!-- LaTeX ikoner -->
    <button type="button" data-snippet="\\( x^2 \\)">
        <svg viewBox="0 0 24 24">
            <text x="4" y="17" font-size="16">x²</text>
        </svg>
    </button>

    <button type="button" data-snippet="\\[ x^2 \\]">
        <svg viewBox="0 0 24 24">
            <text x="4" y="17" font-size="16">[x]</text>
        </svg>
    </button>

    <button type="button" data-snippet="\\frac{a}{b}">
        <svg viewBox="0 0 24 24">
            <line x1="4" y1="12" x2="20" y2="12" stroke-width="2" stroke="currentColor"/>
            <text x="6" y="9" font-size="12">a</text>
            <text x="6" y="20" font-size="12">b</text>
        </svg>
    </button>

    <button type="button" data-snippet="\\sum_{n=1}^{\\infty} ">
        <svg viewBox="0 0 24 24">
            <text x="3" y="20" font-size="20">∑</text>
        </svg>
    </button>

    <button type="button" data-snippet="\\int_{a}^{b} f(x) dx">
        <svg viewBox="0 0 24 24">
            <text x="6" y="19" font-size="22">∫</text>
        </svg>
    </button>

        <textarea id="editor" name="content" rows="10" required><?= e($note['content']) ?></textarea>

        <button type="submit">Gem ændringer</button>
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
