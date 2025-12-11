<?php

// Sørg for at session allerede er startet i dit bootstrap/index.php

function csrf_token()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field()
{
    $token = csrf_token();
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
}

function verify_csrf()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return; // Kun POST requests tjekkes
    }

    // Nogle endpoints kan du evt. undtage (f.eks. AJAX preview)
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $skip = ['/preview-markdown'];

    if (in_array($uri, $skip, true)) {
        return;
    }

    if (empty($_POST['csrf_token']) || empty($_SESSION['csrf_token'])) {
        http_response_code(403);
        die('CSRF token mangler.');
    }

    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        http_response_code(403);
        die('Ugyldigt CSRF token.');
    }
}
