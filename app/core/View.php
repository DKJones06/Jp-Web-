<?php

class View {
    public static function render($file, $data = []) {
        extract($data);

        require __DIR__ . '/../views/partials/header.php';
        require __DIR__ . '/../views/' . $file . '.php';
        require __DIR__ . '/../views/partials/footer.php';
    }
}
