<?php

class AuthController extends Controller {

    public function login() {
        return $this->view('auth/login');
    }

    public function loginPost() {
        global $db;

        $email = $_POST['email'];
        $pass  = $_POST['password'];

        $stmt = $db->prepare("SELECT * FROM users WHERE email=?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($pass, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            redirect('/');
        }

        return $this->view('auth/login', [
            'error' => 'Forkert email eller kodeord.'
        ]);
    }

    public function register() {
        return $this->view('auth/register');
    }

    public function registerPost() {
        global $db;

        $username = $_POST['username'];
        $email = $_POST['email'];
        $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);

        try {
            $stmt = $db->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $pass]);
            redirect('/login');
        } catch (Exception $e) {
            return $this->view('auth/register', [
                'error' => 'Emailen er allerede i brug.'
            ]);
        }
    }

    public function logout() {
        session_destroy();
        redirect('/login');
    }
}
