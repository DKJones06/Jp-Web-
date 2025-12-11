<?php

require_once __DIR__ . '/../models/Comment.php';
require_once __DIR__ . '/../core/permissions.php';

class AdminCommentController extends Controller
{
    public function index()
    {
        requirePermission('moderate_comments');

        $comments = Comment::all();

        return $this->view('admin/comments/index', [
            'comments' => $comments
        ]);
    }

    public function delete()
    {
        requirePermission('moderate_comments');

        $id = $_POST['id'];
        Comment::softDelete($id);

        redirect('/admin/comments');
    }

    public function restore()
    {
        requirePermission('moderate_comments');

        $id = $_POST['id'];
        Comment::restore($id);

        redirect('/admin/comments');
    }
}
