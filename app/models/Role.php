<?php

class Role
{
    // Hent alle roller
    public static function all()
    {
        global $db;

        return $db->query("SELECT * FROM roles ORDER BY id ASC")->fetchAll();
    }

    // Find specifik rolle
    public static function find($id)
    {
        global $db;

        $stmt = $db->prepare("SELECT * FROM roles WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Opret ny rolle
    public static function create($name)
    {
        global $db;

        $stmt = $db->prepare("INSERT INTO roles (role_name) VALUES (?)");
        return $stmt->execute([$name]);
    }

    // Slet rolle
    public static function delete($id)
    {
        global $db;

        $stmt = $db->prepare("DELETE FROM roles WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Hent rolle-permissions
    public static function permissions($roleId)
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
}
