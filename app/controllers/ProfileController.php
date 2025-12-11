<?php

require_once __DIR__ . '/../models/User.php';

class ProfileController extends Controller
{
    public function index()
    {
        if (!isLoggedIn()) {
            redirect('/login');
        }

        $user = User::find($_SESSION['user_id']);

        return $this->view('profile/index', [
            'user' => $user
        ]);
    }

    public function uploadAvatar()
    {
        if (!isLoggedIn()) redirect('/login');

        if (!isset($_FILES['avatar']) || $_FILES['avatar']['error'] !== 0) {
            die("Filupload fejlede.");
        }

        $file = $_FILES['avatar'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, ['jpg','jpeg','png','webp'])) {
            die("Kun JPG, PNG eller WEBP");
        }

        $newName = uniqid() . "." . $ext;
        $path = __DIR__ . '/../../public/avatars/' . $newName;

        move_uploaded_file($file['tmp_name'], $path);

        User::updateAvatar($_SESSION['user_id'], $newName);

        redirect('/profile');
    }
}
