<?php
session_start(); // CSRF kræver session

require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/core/Router.php';
require_once __DIR__ . '/../app/core/Controller.php';
require_once __DIR__ . '/../app/core/View.php';
require_once __DIR__ . '/../app/core/auth.php';
require_once __DIR__ . '/../app/core/helpers.php';
require_once __DIR__ . '/../app/core/csrf.php'; // ← CSRF filen

// ------------------------------------------------------
// CSRF skal verificeres INDEN routeren håndterer request
// ------------------------------------------------------
verify_csrf();

$router = new Router();

// ROUTES
$router->get('/', 'HomeController@index');
$router->get('/note', 'NoteController@show');
$router->get('/login', 'AuthController@login');
$router->post('/login', 'AuthController@loginPost');
$router->get('/register', 'AuthController@register');
$router->post('/register', 'AuthController@registerPost');
$router->get('/logout', 'AuthController@logout');
$router->get('/profile', 'UserController@profile');
$router->post('/profile/avatar', 'UserController@avatarPost');
$router->get('/note/create', 'NoteController@create');
$router->post('/note/create', 'NoteController@createPost');
$router->get('/note/edit', 'NoteController@edit');
$router->post('/note/edit', 'NoteController@editPost');
$router->post('/note/comment', 'NoteController@commentPost');
$router->get('/profile', 'ProfileController@index');
$router->post('/profile/upload-avatar', 'ProfileController@uploadAvatar');

// Kategorier (admin)
$router->get('/admin/categories', 'AdminCategoryController@index');
$router->get('/admin/categories/create', 'AdminCategoryController@create');
$router->post('/admin/categories/create', 'AdminCategoryController@createPost');
$router->get('/admin/categories/edit', 'AdminCategoryController@edit');
$router->post('/admin/categories/edit', 'AdminCategoryController@editPost');
$router->post('/admin/categories/delete', 'AdminCategoryController@delete');

$router->get('/admin/comments', 'AdminCommentController@index');
$router->post('/admin/comments/delete', 'AdminCommentController@delete');
$router->post('/admin/comments/restore', 'AdminCommentController@restore');

$router->get('/admin/notes', 'AdminNoteController@index');
$router->post('/admin/notes/delete', 'AdminNoteController@delete');

$router->get('/admin/dashboard', 'AdminDashboardController@index');
$router->get('/admin/users', 'AdminUserController@index');
$router->post('/admin/users/update-role', 'AdminUserController@updateRole');

// Start router
$router->dispatch();
