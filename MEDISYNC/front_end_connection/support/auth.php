<?php
function fc_is_user(): bool { return isset($_SESSION['user_id']); }
function fc_is_admin(): bool { return isset($_SESSION['admin_id']); }
function fc_user_name(): string { return $_SESSION['user_name'] ?? 'User'; }
function fc_admin_name(): string { return $_SESSION['admin_username'] ?? 'Admin'; }

function fc_require_user(): void {
    if (!fc_is_user()) {
        fc_set_flash('Please log in first.', 'error');
        fc_redirect(fc_root_path('login.html'));
    }
}

function fc_require_admin(): void {
    if (!fc_is_admin()) {
        fc_set_flash('Admin login required.', 'error');
        fc_redirect(fc_root_path('admin-login.html'));
    }
}

function fc_require_user_or_admin(): void {
    if (!fc_is_user() && !fc_is_admin()) {
        fc_set_flash('Please log in to browse and book medical tests.', 'error');
        fc_redirect(fc_root_path('login.html'));
    }
}
