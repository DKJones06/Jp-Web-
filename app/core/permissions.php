<?php

function hasPermission($key) {
    if (!isLoggedIn()) return false;

    global $db;

    $stmt = $db->prepare("
        SELECT COUNT(*)
        FROM role_permissions rp
        JOIN permissions p ON rp.permission_id = p.id
        JOIN users u ON u.role_id = rp.role_id
        WHERE u.id = ? AND p.permission_key = ?
    ");

    $stmt->execute([$_SESSION['user_id'], $key]);

    return $stmt->fetchColumn() > 0;
}

function isTimedOut($userId) {
    global $db;

    $stmt = $db->prepare("
        SELECT expires_at
        FROM timeouts 
        WHERE user_id = ?
        ORDER BY id DESC LIMIT 1
    ");
    $stmt->execute([$userId]);
    $timeout = $stmt->fetch();

    return $timeout && strtotime($timeout['expires_at']) > time();
}
