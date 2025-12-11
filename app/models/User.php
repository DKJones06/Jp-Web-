<?php

class User
{
    // --------------------------------------------------
    // HENT ÉN BRUGER
    // --------------------------------------------------
    public static function find($id)
    {
        global $db;

        $stmt = $db->prepare("SELECT * FROM users WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // --------------------------------------------------
    // OPDATER AVATAR
    // --------------------------------------------------
    public static function updateAvatar($userId, $filename)
    {
        global $db;

        $stmt = $db->prepare("UPDATE users SET avatar = ? WHERE id = ?");
        return $stmt->execute([$filename, $userId]);
    }

    // --------------------------------------------------
    // STATISTIK: ANTAL BRUGERE (til dashboard)
    // --------------------------------------------------
    public static function countAll()
    {
        global $db;

        return $db->query("SELECT COUNT(*) FROM users")->fetchColumn();
    }

    // --------------------------------------------------
    // STATISTIK: TOP 5 MEST AKTIVE BRUGERE
    // --------------------------------------------------
    public static function topActiveUsers()
    {
        global $db;

        $sql = "
            SELECT users.username, COUNT(comments.id) AS total
            FROM users
            LEFT JOIN comments ON comments.user_id = users.id
            GROUP BY users.id
            ORDER BY total DESC
            LIMIT 5
        ";

        return $db->query($sql)->fetchAll();
    }
}

public static function all()
{
    global $db;

    return $db->query("
        SELECT users.*, roles.role_name
        FROM users
        LEFT JOIN roles ON roles.id = users.role_id
        ORDER BY users.id DESC
    ")->fetchAll();
}

public static function updateRole($userId, $roleId)
{
    global $db;

    $stmt = $db->prepare("UPDATE users SET role_id = ? WHERE id = ?");
    return $stmt->execute([$roleId, $userId]);
}
