<?php
function fc_handle_login_request(): void {
    if (!fc_is_post()) fc_redirect('../login.html');
    $result = fc_login_user($_POST['phone'] ?? '', $_POST['password'] ?? '');
    fc_set_flash($result['message'], $result['ok'] ? 'success' : 'error');
    fc_redirect($result['ok'] ? '../index.html' : '../login.html');
}

function fc_handle_signup_request(): void {
    if (!fc_is_post()) fc_redirect('../signup.html');
    $result = fc_signup_user($_POST);
    fc_set_flash($result['message'], $result['ok'] ? 'success' : 'error');
    fc_redirect($result['ok'] ? '../index.html' : '../signup.html');
}

function fc_handle_admin_login_request(): void {
    if (!fc_is_post()) fc_redirect('../admin-login.html');
    $result = fc_login_admin($_POST['username'] ?? '', $_POST['password'] ?? '');
    fc_set_flash($result['message'], $result['ok'] ? 'success' : 'error');
    fc_redirect($result['ok'] ? '../admin.html' : '../admin-login.html');
}

function fc_handle_logout_request(): void {
    fc_logout_current_session();
    fc_set_flash('Logged out successfully.', 'success');
    fc_redirect('../index.html');
}
