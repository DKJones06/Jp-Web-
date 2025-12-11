<?php

require_once __DIR__ . '/../models/Note.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Tag.php';
require_once __DIR__ . '/../core/permissions.php';

class AdminNoteController extends Controller
{
    // --------------------------------------------------
    // LISTE + SØGNING + FILTER
    // --------------------------------------------------
    public function index()
    {
        requirePermission('manage_notes');

        $search = $_GET['search'] ?? '';
        $category = $_GET['category'] ?? '';
        $tag = $_GET['tag'] ?? '';

        // Avanceret søgning (vi lavede dette i Note::adminSearch)
        $notes = Note::adminSearch($search, $category, $tag);

        // Data til dropdowns
        $categories = Category::all();
        $tags = Tag::all();

        return $this->view('admin/notes/index', [
            'notes'      => $notes,
            'categories' => $categories,
            'tags'       => $tags,
            'search'     => $search,
            'category'   => $category,
            'tag'        => $tag
        ]);
    }

    // --------------------------------------------------
    // SLET NOTE
    // --------------------------------------------------
   
public function delete()
{
    requirePermission('manage_notes');

    global $db;

    $stmt = $db->prepare("DELETE FROM notes WHERE id = ?");
    $stmt->execute([$_POST['id']]);

    audit("Slettede note", "Note ID: {$_POST['id']}");

    redirect('/admin/notes');
}

