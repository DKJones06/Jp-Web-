<?php

class Timeout
{
    // Hent seneste timeout for en bruger (det du allerede havde)
    public static function getTimeout($userId)
    {
        global $db;

        $stmt = $db->prepare("
            SELECT *
            FROM timeouts
            WHERE user_id = ?
            ORDER BY id DESC LIMIT 1
        ");

        $stmt->execute([$userId]);
        return $stmt->fetch();
    }

    // Tjek om timeout er aktiv lige nu
    public static function activeFor($userId)
    {
        $timeout = self::getTimeout($userId);

        if (!$timeout) return false;

        if (strtotime($timeout['expires_at']) < time()) {
            return false; // timeout udløbet
        }

        return $timeout;
    }

    // Opret timeout
    public static function give($userId, $reason, $durationMinutes, $adminId)
    {
        global $db;

        $expires = date("Y-m-d H:i:s", time() + ($durationMinutes * 60));

        $stmt = $db->prepare("
            INSERT INTO timeouts (user_id, reason, created_by, expires_at)
            VALUES (?, ?, ?, ?)
        ");

        return $stmt->execute([$userId, $reason, $adminId, $expires]);
    }

    // Historik for én bruger
    public static function history($userId)
    {
        global $db;

        $stmt = $db->prepare("
            SELECT timeouts.*, admin.username AS admin_name
            FROM timeouts
            JOIN users AS admin ON admin.id = timeouts.created_by
            WHERE timeouts.user_id = ?
            ORDER BY id DESC
        ");
        $stmt->execute([$userId]);

        return $stmt->fetchAll();
    }

    // Alle timeouts (admin panel)
    public static function all()
    {
        global $db;

        return $db->query("
            SELECT t.*, u.username, a.username AS admin_name
            FROM timeouts t
            JOIN users u ON u.id = t.user_id
            JOIN users a ON a.id = t.created_by
            ORDER BY t.id DESC
        ")->fetchAll();
    }
}
