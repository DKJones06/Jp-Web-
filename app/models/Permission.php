<?php

class Permission
{
    // Hent alle permissions
    public static function all()
    {
        global $db;

        return $db->query("SELECT * FROM permissions ORDER BY id ASC")->fetchAll();
    }

    // Find permission
    public static function find($id)
    {
        global $db;

        $stmt = $db->prepare("SELECT * FROM permissions WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Hent permissions for specifik rolle
    public static function forRole($roleId)
    {
        global $db;

        $stmt = $db->prepare("
            SELECT permissions.*
            FROM role_permissions
            JOIN permissions ON role_permissions.permission_id = permissions.id
            WHERE role_permissions.role_id = ?
        ");
        $stmt->execute([$roleId]);

        return $stmt->fetchAll();
    }

    // Tilføj permission til rolle
    public static function assignToRole($roleId, $permissionId)
    {
        global $db;

        $stmt = $db->prepare("
            INSERT INTO role_permissions (role_id, permission_id)
            VALUES (?, ?)
        ");

        return $stmt->execute([$roleId, $permissionId]);
    }

    // Fjern permission fra rolle
    public static function removeFromRole($roleId, $permissionId)
    {
        global $db;

        $stmt = $db->prepare("
            DELETE FROM role_permissions
            WHERE role_id = ? AND permission_id = ?
        ");

        return $stmt->execute([$roleId, $permissionId]);
    }
}
