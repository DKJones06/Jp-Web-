<?php

class AuditLog
{
    public static function add($userId, $action, $details = null)
    {
        global $db;

        $stmt = $db->prepare("
            INSERT INTO audit_log (user_id, action, details)
            VALUES (?, ?, ?)
        ");

        return $stmt->execute([$userId, $action, $details]);
    }

    public static function all()
    {
        global $db;

        return $db->query("
            SELECT audit_log.*, users.username
            FROM audit_log
            JOIN users ON users.id = audit_log.user_id
            ORDER BY audit_log.id DESC
        ")->fetchAll();
    }
}
