<?php

require_once __DIR__ . '/../models/Warning.php';
require_once __DIR__ . '/../models/Notification.php';
require_once __DIR__ . '/../core/permissions.php';
require_once __DIR__ . '/../core/audit.php';

class AdminWarningController extends Controller
{
    // LISTE OVER ALLE WARNINGS (ADMIN)
    public function index()
    {
        requirePermission('manage_users');

        // Hent ALLE warnings
        $warnings = Warning::all(); // Denne laver vi i Warning.php hvis ikke der findes

        return $this->view('admin/warnings/index', [
            'warnings' => $warnings
        ]);
    }

    // FORMULAR TIL AT GIVE WARNING
    public function createForm()
    {
        requirePermission('manage_users');

        return $this->view('admin/warnings/create');
    }

    // GEM WARNING
    public function create()
    {
        requirePermission('manage_users');

        $userId  = $_POST['user_id'];
        $text    = $_POST['warning_text'];
        $adminId = $_SESSION['user_id'];

        // Opret warning
        Warning::create($userId, $adminId, $text);

        // AUDIT LOG
        audit("Gav warning", "Til bruger: $userId — '$text'");

        // NOTIFIKATION TIL BRUGER
        Notification::create(
            $userId,
            "Du har fået en advarsel af en moderator.",
            "/profile"
        );

        redirect("/admin/warnings");
    }
}
