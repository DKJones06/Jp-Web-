<?php
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/core/helpers.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $pass     = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    try {
        $stmt->execute([$username, $email, $pass]);
        redirect('/public/login.php');
    } catch (Exception $e) {
        $error = "Emailen er allerede registreret.";
    }
}

require_once __DIR__ . '/../app/views/partials/header.php';
?>

<h1>Registrering</h1>

<form method="post">
    <input type="text" name="username" placeholder="Brugernavn" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Kodeord" required>
    <button type="submit">Opret konto</button>
</form>

<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
<?php require_once __DIR__ . '/../app/views/partials/footer.php'; ?>
