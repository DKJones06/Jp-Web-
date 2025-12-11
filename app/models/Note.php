<?php

class Note
{
    // ---------------------------------------------------------
    // SØGNING (forside: search + kategori filter)
    // ---------------------------------------------------------
    public static function search($search = '', $category = '')
    {
        global $db;

        $sql = "SELECT notes.*, users.username, categories.name AS category_name
                FROM notes
                JOIN users ON notes.author_id = users.id
                LEFT JOIN categories ON notes.category_id = categories.id
                WHERE 1 ";

        $params = [];

        // Tekstsøgning
        if (!empty($search)) {
            $sql .= "AND (notes.title LIKE ? OR notes.content LIKE ?) ";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        // Filter efter kategori
        if (!empty($category)) {
            $sql .= "AND notes.category_id = ? ";
            $params[] = $category;
        }

        $sql .= "ORDER BY notes.created_at DESC";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }


    // ---------------------------------------------------------
    // ADMIN SØGNING (search + kategori + tags)
    // ---------------------------------------------------------
    public static function adminSearch($search = '', $category = '', $tag = '')
    {
        global $db;

        $sql = "SELECT notes.*, 
                       users.username,
                       categories.name AS category_name
                FROM notes
                JOIN users ON notes.author_id = users.id
                LEFT JOIN categories ON notes.category_id = categories.id
                WHERE 1 ";

        $params = [];

        // Tekstsøgning
        if (!empty($search)) {
            $sql .= "AND (notes.title LIKE ? OR notes.content LIKE ?) ";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }

        // Filter efter kategori
        if (!empty($category)) {
            $sql .= "AND notes.category_id = ? ";
            $params[] = $category;
        }

        // Filter efter tag
        if (!empty($tag)) {
            $sql .= "AND notes.id IN (SELECT note_id FROM note_tags WHERE tag_id = ?) ";
            $params[] = $tag;
        }

        $sql .= "ORDER BY notes.created_at DESC";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll();
    }


    // ---------------------------------------------------------
    // HENT EN NOTE (brugt i NoteController@show)
    // ---------------------------------------------------------
    public static function find($id)
    {
        global $db;

        $stmt = $db->prepare("
            SELECT notes.*, 
                   users.username,
                   categories.name AS category_name
            FROM notes
            JOIN users ON notes.author_id = users.id
            LEFT JOIN categories ON notes.category_id = categories.id
            WHERE notes.id = ?
        ");
        $stmt->execute([$id]);

        return $stmt->fetch();
    }


    // ---------------------------------------------------------
    // HENT KOMMENTARER TIL NOTE
    // ---------------------------------------------------------
    public static function comments($noteId)
    {
        global $db;

        $stmt = $db->prepare("
            SELECT comments.*, users.username, users.avatar
            FROM comments
            JOIN users ON comments.user_id = users.id
            WHERE comments.note_id = ?
            ORDER BY comments.created_at ASC
        ");
        $stmt->execute([$noteId]);

        return $stmt->fetchAll();
    }


    // ---------------------------------------------------------
    // OPRET NOTE
    // ---------------------------------------------------------
    public static function create($title, $content, $categoryId, $authorId)
    {
        global $db;

        $stmt = $db->prepare("
            INSERT INTO notes (title, content, category_id, author_id)
            VALUES (?, ?, ?, ?)
        ");

        $stmt->execute([
            $title,
            $content,
            $categoryId,
            $authorId
        ]);

        return $db->lastInsertId(); // vigtigt til tags!
    }


    // ---------------------------------------------------------
    // OPDATER NOTE
    // ---------------------------------------------------------
    public static function update($id, $title, $content, $categoryId)
    {
        global $db;

        $stmt = $db->prepare("
            UPDATE notes
            SET title = ?, content = ?, category_id = ?, updated_at = NOW()
            WHERE id = ?
        ");

        return $stmt->execute([
            $title,
            $content,
            $categoryId,
            $id
        ]);
    }
}

// ---------------------------------------------------------
    // static
    // ---------------------------------------------------------
public static function countAll()
{
    global $db;
    return $db->query("SELECT COUNT(*) FROM notes")->fetchColumn();
}

public static function statsPerDay()
{
    global $db;
    $sql = "
        SELECT DATE(created_at) AS day, COUNT(*) AS total
        FROM notes
        GROUP BY DATE(created_at)
        ORDER BY day ASC
        LIMIT 14
    ";
    return $db->query($sql)->fetchAll();
}
