<?php

class Notification
{
    // Opret notifikation
    public static function create($userId, $message, $link = null)
    {
        global $db;

        $stmt = $db->prepare("
            INSERT INTO notifications (user_id, message, link)
            VALUES (?, ?, ?)
        ");

        return $stmt->execute([$userId, $message, $link]);
    }

    // Hent alle notifikationer for bruger
    public static function forUser($userId)
    {
        global $db;

        $stmt = $db->prepare("
            SELECT *
            FROM notifications
            WHERE user_id = ?
            ORDER BY created_at DESC
        ");

        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }

    // Hent antal ulæste (til badge)
    public static function unreadCount($userId)
    {
        global $db;

        $stmt = $db->prepare("
            SELECT COUNT(*) 
            FROM notifications
            WHERE user_id = ? AND is_read = 0
        ");
        $stmt->execute([$userId]);

        return $stmt->fetchColumn();
    }

    // Marker som læst
    public static function markAsRead($userId)
    {
        global $db;

        $stmt = $db->prepare("
            UPDATE notifications
            SET is_read = 1
            WHERE user_id = ?
        ");

        $stmt->execute([$userId]);
    }
}
