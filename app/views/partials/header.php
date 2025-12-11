<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <title>JP's Noter</title>

    <!-- Frontend styles -->
    <link rel="stylesheet" href="/assets/css/style.css">

    <!-- Admin styles (kun brugt i admin panel) -->
    <link rel="stylesheet" href="/assets/css/admin.css">

    <!-- MathJax Loader -->
    <script>
    window.MathJax = {
        tex: {
            inlineMath: [['\\(', '\\)'], ['$', '$']],
            displayMath: [['\\[', '\\]'], ['$$', '$$']]
        },
        svg: { fontCache: 'global' }
    };
    </script>

    <script src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-svg.js"></script>
</head>

<body>

<!-- Navigation -->
<nav class="card" style="margin-bottom:25px;">
    <a href="/">Forside</a>

    <?php if (isLoggedIn()): ?>
        <a href="/profile">Min profil</a>
        <a href="/admin/index.php">Admin</a>
        <a href="/logout">Logout</a>
    <?php else: ?>
        <a href="/login">Login</a>
        <a href="/register">Registrer</a>
    <?php endif; ?>
</nav>

<div class="container">
