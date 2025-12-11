<?php

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Role.php';
require_once __DIR__ . '/../core/permissions.php';

class AdminUserController extends Controller
{
    public function index()
    {
        requirePermission('manage_users');

        $users = User::all();
        $roles = Role::all();

        return $this->view('admin/users/index', [
            'users' => $users,
            'roles' => $roles
        ]);
    }

    public function updateRole()
    {
        requirePermission('manage_roles');

        User::updateRole($_POST['user_id'], $_POST['role_id']);

        redirect('/admin/users');
    }
}
