<h1>Registrering</h1>

<?php if (!empty($error)): ?>
    <p style="color:red;"><?= e($error) ?></p>
<?php endif; ?>

<form method="post">
     <?= csrf_field() ?>
    <input type="text" name="username" placeholder="Brugernavn">
    <input type="email" name="email" placeholder="Email">
    <input type="password" name="password" placeholder="Kodeord">
    <button type="submit">Opret konto</button>
</form>
