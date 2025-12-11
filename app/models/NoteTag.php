<?php

class NoteTag
{
    public static function getTagsForNote($noteId)
    {
        global $db;

        $stmt = $db->prepare("
            SELECT tags.*
            FROM note_tags
            JOIN tags ON note_tags.tag_id = tags.id
            WHERE note_tags.note_id = ?
        ");
        $stmt->execute([$noteId]);

        return $stmt->fetchAll();
    }

    public static function setTagsForNote($noteId, $tagIds)
    {
        global $db;

        // Fjern eksisterende tags
        $db->prepare("DELETE FROM note_tags WHERE note_id = ?")
           ->execute([$noteId]);

        // Tilføj nye
        foreach ($tagIds as $tagId) {
            $stmt = $db->prepare("INSERT INTO note_tags (note_id, tag_id) VALUES (?, ?)");
            $stmt->execute([$noteId, $tagId]);
        }
    }
}
