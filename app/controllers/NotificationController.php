<?php

require_once __DIR__ . '/../models/Notification.php';

class NotificationController extends Controller
{
    public function index()
    {
        if (!isLoggedIn()) redirect('/login');

        $notifications = Notification::forUser($_SESSION['user_id']);

        // marker alle som læst
        Notification::markAsRead($_SESSION['user_id']);

        return $this->view('notifications/index', [
            'notifications' => $notifications
        ]);
    }
}
