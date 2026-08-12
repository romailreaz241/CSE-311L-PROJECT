<?php
function fc_create_booking_from_tests(int $userId, array $testPrices, string $txnRef): int {
    $db = getDB();
    $db->beginTransaction();
    try {
        $total = array_sum($testPrices);
        $stmt = $db->prepare("INSERT INTO bookings (user_id, patient_name, patient_phone, total_amount, status, txn_ref) SELECT id, name, phone, ?, 'pending', ? FROM users WHERE id = ?");
        $stmt->execute([$total, $txnRef, $userId]);
        $bookingId = (int)$db->lastInsertId();

        $tokenNumber = chr(rand(65, 90)) . '-' . sprintf('%03d', $bookingId);
        $stmt = $db->prepare('UPDATE bookings SET token_number = ? WHERE id = ?');
        $stmt->execute([$tokenNumber, $bookingId]);

        $itemStmt = $db->prepare('INSERT INTO booking_items (booking_id, test_id, price) VALUES (?, ?, ?)');
        foreach ($testPrices as $testId => $price) {
            $itemStmt->execute([$bookingId, $testId, $price]);
        }

        $db->commit();
        return $bookingId;
    } catch (Exception $e) {
        if ($db->inTransaction()) $db->rollBack();
        throw $e;
    }
}

function fc_pending_bookings(): array {
    $stmt = getDB()->prepare("SELECT b.id, b.token_number, b.total_amount, b.created_at, COALESCE(u.name, b.patient_name) AS patient_name, GROUP_CONCAT(t.name ORDER BY t.name SEPARATOR ', ') AS test_names FROM bookings b LEFT JOIN users u ON b.user_id = u.id LEFT JOIN booking_items bi ON bi.booking_id = b.id LEFT JOIN tests t ON t.id = bi.test_id WHERE b.status = 'pending' GROUP BY b.id ORDER BY b.created_at ASC");
    $stmt->execute();
    return $stmt->fetchAll();
}

function fc_mark_booking_completed(int $bookingId): bool {
    $stmt = getDB()->prepare("UPDATE bookings SET status = 'completed' WHERE id = ? AND status = 'pending'");
    $stmt->execute([$bookingId]);
    return $stmt->rowCount() > 0;
}

function fc_booking_success_data(): ?array {
    $id = (int)($_GET['id'] ?? 0);
    if (!$id) return null;
    $stmt = getDB()->prepare('SELECT * FROM bookings WHERE id = ?');
    $stmt->execute([$id]);
    $booking = $stmt->fetch();
    if (!$booking) return null;
    $stmt = getDB()->prepare('SELECT t.name, t.room, t.floor, bi.price FROM booking_items bi JOIN tests t ON bi.test_id = t.id WHERE bi.booking_id = ?');
    $stmt->execute([$id]);
    $booking['items'] = $stmt->fetchAll();
    return $booking;
}
