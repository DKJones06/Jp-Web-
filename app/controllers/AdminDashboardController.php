<?php

require_once __DIR__ . '/../models/Note.php';
require_once __DIR__ . '/../models/Comment.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../core/permissions.php';

class AdminDashboardController extends Controller
{
    public function index()
    {
        requirePermission('manage_notes');

        // Statistik
        $noteCount      = Note::countAll();
        $commentCount   = Comment::countAll();
        $userCount      = User::countAll();

        // Grafer
        $notesPerDay    = Note::statsPerDay();
        $commentsPerDay = Comment::statsPerDay();

        // Top brugere
        $topUsers       = User::topActiveUsers();

        return $this->view('admin/dashboard', [
            'noteCount'      => $noteCount,
            'commentCount'   => $commentCount,
            'userCount'      => $userCount,
            'notesPerDay'    => $notesPerDay,
            'commentsPerDay' => $commentsPerDay,
            'topUsers'       => $topUsers
        ]);
    }
}
