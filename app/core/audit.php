<?php

require_once __DIR__ . '/../models/AuditLog.php';

//
// LOGIN HELPERS
//
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        redirect('/login');
    }
}

function currentUser() {
    global $db;
    if (!isLoggedIn()) return null;

    $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

//
// AUDIT LOGGING FUNCTION
//
function audit($action, $details = null)
{
    if (!isset($_SESSION['user_id'])) return;

    AuditLog::add($_SESSION['user_id'], $action, $details);
}
