<?php
function fc_book_current_cart_for_user(int $userId): array {
    $ids = fc_cart_ids();
    if (!$ids) {
        return ['ok' => false, 'message' => 'Your cart is empty.'];
    }

    $dbTests = fc_fetch_tests_by_ids($ids);
    if (count($dbTests) !== count($ids)) {
        return ['ok' => false, 'message' => 'Some selected tests were not found.'];
    }

    $txnRef = 'TXN-' . strtoupper(substr(md5(uniqid('', true)), 0, 12));
    $bookingId = fc_create_booking_from_tests($userId, $dbTests, $txnRef);
    fc_clear_cart();

    return ['ok' => true, 'booking_id' => $bookingId];
}
