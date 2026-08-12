<?php
function fc_handle_complete_booking_request(): void {
    fc_require_admin();
    if (!fc_is_post()) fc_redirect('../admin.html');

    $bookingId = (int)($_POST['booking_id'] ?? 0);
    if ($bookingId > 0) {
        $completed = fc_mark_booking_completed($bookingId);
        fc_set_flash(
            $completed ? 'Booking marked as completed.' : 'Booking not found or already completed.',
            $completed ? 'success' : 'error'
        );
    }
    fc_redirect('../admin.html');
}

function fc_handle_update_test_request(): void {
    fc_require_admin();
    if (!fc_is_post()) fc_redirect('../admin-tests.html');

    $result = fc_update_test_from_input($_POST);
    fc_set_flash($result['message'], $result['ok'] ? 'success' : 'error');
    fc_redirect('../admin-tests.html');
}
