<?php

require_once __DIR__ . '/../models/User.php';

class UserController extends Controller
{
    public function profile()
    {
        if (!isLoggedIn()) {
            redirect('/login');
        }

        global $db;

        $user = User::find($_SESSION['user_id']);

        return $this->view('user/profile', [
            'user' => $user,
        ]);
    }

    public function avatarPost()
    {
        if (!isLoggedIn()) {
            redirect('/login');
        }

        global $db;

        $user = User::find($_SESSION['user_id']);
        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['avatar'])) {

            if (!is_dir(AVATAR_DIR)) {
                mkdir(AVATAR_DIR, 0775, true);
            }

            $file    = $_FILES['avatar'];
            $name    = $file['name'];
            $tmpName = $file['tmp_name'];
            $size    = $file['size'];
            $err     = $file['error'];

            $allowedExt = ['png', 'jpg', 'jpeg', 'gif', 'webp'];
            $maxSize    = 2 * 1024 * 1024; // 2 MB

            if ($err !== UPLOAD_ERR_OK) {
                $error = 'Fejl ved upload (kode: '.$err.')';
            } else {
                $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));

                if (!in_array($ext, $allowedExt)) {
                    $error = 'Ugyldig filtype. Brug png/jpg/jpeg/gif/webp.';
                } elseif ($size > $maxSize) {
                    $error = 'Filen er for stor (max 2MB).';
                } else {
                    $newName = uniqid('avatar_', true).'.'.$ext;
                    $dest    = AVATAR_DIR . $newName;

                    if (move_uploaded_file($tmpName, $dest)) {
                        // slet gammel avatar hvis der var en
                        if (!empty($user['avatar'])) {
                            $old = AVATAR_DIR . $user['avatar'];
                            if (is_file($old)) {
                                @unlink($old);
                            }
                        }

                        User::updateAvatar($user['id'], $newName);
                        $success = 'Avatar opdateret!';

                        // hent opdateret user
                        $user = User::find($user['id']);
                    } else {
                        $error = 'Kunne ikke gemme filen.';
                    }
                }
            }
        }

        return $this->view('user/profile', [
            'user'    => $user,
            'error'   => $error,
            'success' => $success,
        ]);
    }
}
