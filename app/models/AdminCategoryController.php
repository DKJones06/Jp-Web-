<?php

require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../core/permissions.php';

class AdminCategoryController extends Controller
{
    public function index()
    {
        requirePermission('manage_categories');

        $categories = Category::all();

        return $this->view('admin/categories/index', [
            'categories' => $categories
        ]);
    }

    public function create()
    {
        requirePermission('manage_categories');
        return $this->view('admin/categories/create');
    }

    public function createPost()
    {
        requirePermission('manage_categories');

        $name = trim($_POST['name']);
        $slug = strtolower(trim($_POST['slug']));

        if ($name == '' || $slug == '') {
            return $this->view('admin/categories/create', [
                'error' => "Alle felter skal udfyldes"
            ]);
        }

        Category::create($name, $slug);
        redirect('/admin/categories');
    }

    public function edit()
    {
        requirePermission('manage_categories');

        $id = $_GET['id'] ?? null;
        $category = Category::find($id);

        return $this->view('admin/categories/edit', [
            'category' => $category
        ]);
    }

    public function editPost()
    {
        requirePermission('manage_categories');

        $id = $_POST['id'];
        $name = trim($_POST['name']);
        $slug = strtolower(trim($_POST['slug']));

        if ($name == '' || $slug == '') {
            return $this->view('admin/categories/edit', [
                'error' => "Felter må ikke være tomme",
                'category' => Category::find($id)
            ]);
        }

        Category::update($id, $name, $slug);
        redirect('/admin/categories');
    }

    public function delete()
    {
        requirePermission('manage_categories');

        $id = $_POST['id'];
        Category::delete($id);

        redirect('/admin/categories');
    }
}
