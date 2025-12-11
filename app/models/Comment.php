<?php

class Comment
{
    // ---------------------------------------------------------
    // Hent ALLE kommentarer (bruges i admin panel)
    // ---------------------------------------------------------
    public static function all()
    {
        global $db;

        $sql = "SELECT comments.*, 
                       users.username, 
                       users.avatar, 
                       notes.title AS note_title
                FROM comments
                JOIN users ON comments.user_id = users.id
                JOIN notes ON comments.note_id = notes.id
                ORDER BY comments.created_at DESC";

        return $db->query($sql)->fetchAll();
    }


    // ---------------------------------------------------------
    // Opret kommentar (bruges i NoteController -> commentPost)
    // ---------------------------------------------------------
    public static function create($noteId, $userId, $content)
    {
        global $db;

        $stmt = $db->prepare("
            INSERT INTO comments (note_id, user_id, content)
            VALUES (?, ?, ?)
        ");

        return $stmt->execute([
            $noteId,
            $userId,
            $content
        ]);
    }


    // ---------------------------------------------------------
    // Soft delete en kommentar
    // ---------------------------------------------------------
    public static function softDelete($id)
    {
        global $db;

        $stmt = $db->prepare("
            UPDATE comments 
            SET is_deleted = 1 
            WHERE id = ?
        ");

        return $stmt->execute([$id]);
    }


    // ---------------------------------------------------------
    // Gendan en kommentar
    // ---------------------------------------------------------
    public static function restore($id)
    {
        global $db;

        $stmt = $db->prepare("
            UPDATE comments 
            SET is_deleted = 0 
            WHERE id = ?
        ");

        return $stmt->execute([$id]);
    }


    // ---------------------------------------------------------
    // Hent kommentar via ID (fx til fremtidige features)
    // ---------------------------------------------------------
    public static function find($id)
    {
        global $db;

        $stmt = $db->prepare("
            SELECT comments.*, 
                   users.username, 
                   users.avatar
            FROM comments
            JOIN users ON comments.user_id = users.id
            WHERE comments.id = ?
        ");

        $stmt->execute([$id]);
        return $stmt->fetch();
    }


    // ---------------------------------------------------------
    // Hent kommentarer til én note (bruges af Note::comments)
    // ---------------------------------------------------------
    public static function findByNote($noteId)
    {
        global $db;

        $stmt = $db->prepare("
            SELECT comments.*, 
                   users.username, 
                   users.avatar
            FROM comments
            JOIN users ON comments.user_id = users.id
            WHERE comments.note_id = ?
            ORDER BY comments.created_at ASC
        ");

        $stmt->execute([$noteId]);
        return $stmt->fetchAll();
    }
}

    // ---------------------------------------------------------
    // static
    // ---------------------------------------------------------
public static function countAll()
{
    global $db;
    return $db->query("SELECT COUNT(*) FROM comments")->fetchColumn();
}

public static function statsPerDay()
{
    global $db;
    $sql = "
        SELECT DATE(created_at) AS day, COUNT(*) AS total
        FROM comments
        GROUP BY DATE(created_at)
        ORDER BY day ASC
        LIMIT 14
    ";
    return $db->query($sql)->fetchAll();
}
