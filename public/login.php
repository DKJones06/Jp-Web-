<?php
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/core/helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $pass  = trim($_POST['password']);

    $stmt = $db->prepare("SELECT * FROM users WHERE email=? LIMIT 1");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($pass, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        redirect('/public/index.php');
    } else {
        $error = "Forkert email eller kodeord";
    }
}

require_once __DIR__ . '/../app/views/partials/header.php';
?>

<h1>Login</h1>

<form method="post">
    <input type="email" name="email" required placeholder="Email">
    <input type="password" name="password" required placeholder="Kodeord">
    <button type="submit">Login</button>
</form>

<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

<?php require_once __DIR__ . '/../app/views/partials/footer.php'; ?>
