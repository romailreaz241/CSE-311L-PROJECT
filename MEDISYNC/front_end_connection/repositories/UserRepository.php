<?php
function fc_find_user_by_phone(string $phone): ?array {
    $stmt = getDB()->prepare('SELECT id, name, password FROM users WHERE phone = ?');
    $stmt->execute([$phone]);
    $user = $stmt->fetch();
    return $user ?: null;
}

function fc_user_phone_exists(string $phone): bool {
    $stmt = getDB()->prepare('SELECT id FROM users WHERE phone = ?');
    $stmt->execute([$phone]);
    return (bool)$stmt->fetch();
}

function fc_create_user(string $name, string $phone, int $age, string $passwordHash): int {
    $stmt = getDB()->prepare('INSERT INTO users (name, phone, age, password) VALUES (?, ?, ?, ?)');
    $stmt->execute([$name, $phone, $age, $passwordHash]);
    return (int)getDB()->lastInsertId();
}
