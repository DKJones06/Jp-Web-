<?php

class Tag
{
    public static function all()
    {
        global $db;
        return $db->query("SELECT * FROM tags ORDER BY name ASC")->fetchAll();
    }

    public static function find($id)
    {
        global $db;
        $stmt = $db->prepare("SELECT * FROM tags WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
