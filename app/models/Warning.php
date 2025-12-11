<?php

class Warning
{
    // --------------------------------------------------
    // Hent ALLE warnings for én specifik bruger
    // Bruges på profilsiden
    // --------------------------------------------------
    public static function forUser($userId)
    {
        global $db;

        $stmt = $db->prepare("
            SELECT warnings.*, admin.username AS admin_name
            FROM warnings
            JOIN users AS admin ON warnings.created_by = admin.id
            WHERE warnings.user_id = ?
            ORDER BY warnings.created_at DESC
        ");
        $stmt->execute([$userId]);

        return $stmt->fetchAll();
    }

    // --------------------------------------------------
    // Opret en ny warning
    // Bruges af AdminWarningController
    // --------------------------------------------------
    public static function create($userId, $adminId, $text)
    {
        global $db;

        $stmt = $db->prepare("
            INSERT INTO warnings (user_id, created_by, warning_text)
            VALUES (?, ?, ?)
        ");

        return $stmt->execute([$userId, $adminId, $text]);
    }

    // --------------------------------------------------
    // Hent én warning
    // Valgfrit: bruges sjældent, men godt at have
    // --------------------------------------------------
    public static function find($id)
    {
        global $db;

        $stmt = $db->prepare("
            SELECT warnings.*, admin.username AS admin_name
            FROM warnings
            JOIN users AS admin ON warnings.created_by = admin.id
            WHERE warnings.id = ?
        ");
        $stmt->execute([$id]);

        return $stmt->fetch();
    }

    // --------------------------------------------------
    // Hent ALLE warnings
    // Bruges i admin/warnings/index.php
    // --------------------------------------------------
    public static function all()
    {
        global $db;

        return $db->query("
            SELECT warnings.*,
                   u.username AS user_name,
                   admin.username AS admin_name
            FROM warnings
            JOIN users u ON u.id = warnings.user_id
            JOIN users admin ON admin.id = warnings.created_by
            ORDER BY warnings.created_at DESC
        ")->fetchAll();
    }
}
