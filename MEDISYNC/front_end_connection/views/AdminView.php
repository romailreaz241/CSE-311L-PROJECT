<?php
function fc_render_admin_orders(): string {
    fc_require_admin();
    $bookings = fc_pending_bookings();
    if (!$bookings) return '<div class="admin-empty"><span>✅</span>No pending orders. All caught up!</div>';
    $rows = '';
    foreach ($bookings as $b) {
        $rows .= '<tr>' .
            '<td><span class="token-badge">' . fc_esc($b['token_number'] ?: '—') . '</span></td>' .
            '<td style="font-weight:600;color:var(--text)">' . fc_esc($b['patient_name'] ?: 'Unknown') . '</td>' .
            '<td style="color:var(--text-2);font-size:.8rem">' . fc_esc($b['test_names'] ?: '—') . '</td>' .
            '<td style="font-weight:700;color:var(--accent)">৳' . number_format((float)$b['total_amount']) . '</td>' .
            '<td style="color:var(--text-3);font-size:.8rem">' . fc_esc(date('M j, Y g:i A', strtotime($b['created_at']))) . '</td>' .
            '<td><form method="post" action="front_end_connection/complete_booking_handler.php"><input type="hidden" name="booking_id" value="' . (int)$b['id'] . '"><button class="btn-done" type="submit">✔ Done</button></form></td>' .
            '</tr>';
    }
    return '<div style="overflow-x:auto"><table class="admin-table"><thead><tr><th>Token</th><th>Patient</th><th>Tests</th><th>Total (৳)</th><th>Booked At</th><th>Action</th></tr></thead><tbody>' . $rows . '</tbody></table></div>';
}

function fc_render_admin_tests(): string {
    fc_require_admin();
    $tests = fc_fetch_tests();
    if (!$tests) return '<div class="admin-empty"><span>⚠️</span>No tests found.</div>';
    $rows = '';
    foreach ($tests as $t) {
        $id = (int)$t['id'];
        $rows .= '<tr>' .
            '<td><span style="font-size:.75rem;color:var(--accent)">' . fc_esc($t['category_icon'] ?? '') . ' ' . fc_esc($t['category_name'] ?? '') . '</span></td>' .
            '<td style="font-weight:600;color:var(--text)">' . fc_esc($t['name']) . '</td>' .
            '<td><form method="post" action="front_end_connection/update_test_handler.php" class="inline-test-form">' .
            '<input type="hidden" name="test_id" value="' . $id . '">' .
            '<input class="admin-inline-input" name="price" type="number" value="' . fc_esc($t['price']) . '" min="1" step="0.01" style="max-width:110px" /></td>' .
            '<td><input class="admin-inline-input" name="room" type="text" value="' . fc_esc($t['room'] ?? '') . '" placeholder="e.g. Room G-01" /></td>' .
            '<td><input class="admin-inline-input" name="floor" type="text" value="' . fc_esc($t['floor'] ?? '') . '" placeholder="e.g. Ground Floor" /></td>' .
            '<td><button class="btn-save-test" type="submit">💾 Save</button></form></td>' .
            '</tr>';
    }
    return '<div style="overflow-x:auto"><table class="admin-table"><thead><tr><th>Category</th><th>Test Name</th><th>Price (৳)</th><th>Room</th><th>Floor</th><th>Action</th></tr></thead><tbody>' . $rows . '</tbody></table></div>';
}
