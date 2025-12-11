<?php

class Category
{
    public static function all()
    {
        global $db;
        $stmt = $db->query("SELECT * FROM categories ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    public static function find($id)
    {
        global $db;
        $stmt = $db->prepare("SELECT * FROM categories WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function create($name, $slug)
    {
        global $db;
        $stmt = $db->prepare("INSERT INTO categories (name, slug) VALUES (?, ?)");
        return $stmt->execute([$name, $slug]);
    }

    public static function update($id, $name, $slug)
    {
        global $db;
        $stmt = $db->prepare("UPDATE categories SET name=?, slug=? WHERE id=?");
        return $stmt->execute([$name, $slug, $id]);
    }

    public static function delete($id)
    {
        global $db;
        $stmt = $db->prepare("DELETE FROM categories WHERE id=?");
        return $stmt->execute([$id]);
    }
}
