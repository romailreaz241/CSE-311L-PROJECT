<?php
function fc_test_exists(int $id): bool {
    $stmt = getDB()->prepare('SELECT id FROM tests WHERE id = ?');
    $stmt->execute([$id]);
    return (bool)$stmt->fetch();
}

function fc_fetch_tests(int $catId = 0, string $q = ''): array {
    $db = getDB();
    $q = trim($q);
    if ($q !== '') {
        $like = '%' . $q . '%';
        $stmt = $db->prepare("SELECT t.*, c.name AS category_name, c.icon AS category_icon, c.color AS category_color FROM tests t JOIN categories c ON t.category_id = c.id WHERE t.name LIKE ? OR t.description LIKE ? ORDER BY c.id, t.name");
        $stmt->execute([$like, $like]);
        return $stmt->fetchAll();
    }
    if ($catId > 0) {
        $stmt = $db->prepare("SELECT t.*, c.name AS category_name, c.icon AS category_icon, c.color AS category_color FROM tests t JOIN categories c ON t.category_id = c.id WHERE t.category_id = ? ORDER BY t.name");
        $stmt->execute([$catId]);
        return $stmt->fetchAll();
    }
    return $db->query("SELECT t.*, c.name AS category_name, c.icon AS category_icon, c.color AS category_color FROM tests t JOIN categories c ON t.category_id = c.id ORDER BY c.id, t.name")->fetchAll();
}

function fc_fetch_tests_by_ids(array $ids): array {
    if (!$ids) return [];
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = getDB()->prepare("SELECT id, price FROM tests WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
}

function fc_fetch_cart_items_by_ids(array $ids): array {
    if (!$ids) return [];
    $placeholders = implode(',', array_fill(0, count($ids), '?'));
    $stmt = getDB()->prepare("SELECT t.*, c.name AS category_name, c.icon AS category_icon FROM tests t JOIN categories c ON t.category_id = c.id WHERE t.id IN ($placeholders) ORDER BY FIELD(t.id, $placeholders)");
    $stmt->execute(array_merge($ids, $ids));
    return $stmt->fetchAll();
}

function fc_update_test_location_and_price(int $testId, float $price, string $room, string $floor): void {
    $stmt = getDB()->prepare('UPDATE tests SET price = ?, room = ?, floor = ? WHERE id = ?');
    $stmt->execute([$price, $room, $floor, $testId]);
}

function fc_total_tests_count(): int {
    return (int)getDB()->query('SELECT COUNT(*) FROM tests')->fetchColumn();
}
