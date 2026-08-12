<?php
function fc_handle_book_request(): void {
    fc_require_user();
    if (!fc_is_post()) fc_redirect('cart.php');

    try {
        $result = fc_book_current_cart_for_user((int)$_SESSION['user_id']);
        if (!$result['ok']) {
            fc_set_flash($result['message']);
            fc_redirect('cart.php');
        }
        fc_redirect('booking_success.php?id=' . (int)$result['booking_id']);
    } catch (Exception $e) {
        fc_set_flash('Booking failed: ' . $e->getMessage());
        fc_redirect('cart.php');
    }
}
