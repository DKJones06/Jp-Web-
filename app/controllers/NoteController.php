<?php

require_once __DIR__ . '/../models/Note.php';
require_once __DIR__ . '/../models/Comment.php';
require_once __DIR__ . '/../models/Notification.php';
require_once __DIR__ . '/../models/Timeout.php';

class NoteController extends Controller
{
    // --------------------------------------------------
    // VIS EN NOTE
    // --------------------------------------------------
    public function show()
    {
        $id = $_GET['id'] ?? null;

        if (!$id) {
            echo "Note ikke fundet.";
            return;
        }

        $note = Note::find($id);
        $comments = Note::comments($id);

        return $this->view('note/show', [
            'note' => $note,
            'comments' => $comments
        ]);
    }

    // --------------------------------------------------
    // OPRET KOMMENTAR
    // --------------------------------------------------
    public function commentPost()
    {
        if (!isLoggedIn()) redirect('/login');

        $noteId = $_POST['note_id'];
        $content = $_POST['content'];
        $userId = $_SESSION['user_id'];

        // TJEK TIMEOUT
        $timeout = Timeout::activeFor($userId);
        if ($timeout) {
            echo "<div style='color:red; padding:10px;'>Du er i timeout indtil: {$timeout['expires_at']}</div>";
            return;
        }

        // GEM KOMMENTAREN
        Comment::create($noteId, $userId, $content);

        // HENT NOTEINFO TIL NOTIFIKATION
        $note = Note::find($noteId);

        // OPRET NOTIFIKATION TIL NOTE-FORFATTER
        if ($note['author_id'] != $userId) {
            Notification::create(
                $note['author_id'],
                "Ny kommentar på din note: " . $note['title'],
                "/note/show?id=" . $noteId
            );
        }

        redirect("/note/show?id=" . $noteId);
    }
}
