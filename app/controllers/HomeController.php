<?php

require_once __DIR__ . '/../models/Note.php';
require_once __DIR__ . '/../models/Category.php';

class HomeController extends Controller
{
    public function index()
    {
        $search = $_GET['search'] ?? '';
        $category = $_GET['category'] ?? '';

        $notes = Note::search($search, $category);
        $categories = Category::all();

        return $this->view('home/index', [
            'notes' => $notes,
            'categories' => $categories,
            'search' => $search,
            'category' => $category
        ]);
    }
}
