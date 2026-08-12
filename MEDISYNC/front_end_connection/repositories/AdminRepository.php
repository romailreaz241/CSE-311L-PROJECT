<?php
function fc_find_admin_by_credentials(string $username, string $password): ?array {
    $stmt = getDB()->prepare('SELECT id, username FROM admins WHERE username = ? AND password = ?');
    $stmt->execute([$username, $password]);
    $admin = $stmt->fetch();
    return $admin ?: null;
}
