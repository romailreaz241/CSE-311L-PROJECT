<?php
function fc_login_user(string $phone, string $password): array {
    $phone = trim($phone);
    $password = trim($password);
    if ($phone === '' || $password === '') {
        return ['ok' => false, 'message' => 'Please enter your phone number and password.'];
    }

    $user = fc_find_user_by_phone($phone);
    if (!$user || !password_verify($password, $user['password'])) {
        return ['ok' => false, 'message' => 'Incorrect phone number or password.'];
    }

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_phone'] = $phone;
    unset($_SESSION['admin_id'], $_SESSION['admin_username'], $_SESSION['is_admin']);

    return ['ok' => true, 'message' => 'Welcome back, ' . $user['name'] . '!'];
}

function fc_signup_user(array $data): array {
    $fname = trim($data['fname'] ?? '');
    $lname = trim($data['lname'] ?? '');
    $name = trim($fname . ' ' . $lname);
    $phone = trim($data['phone'] ?? '');
    $age = (int)($data['age'] ?? 0);
    $password = trim($data['password'] ?? '');
    $confirm = trim($data['confirm_password'] ?? '');

    if ($name === '' || $phone === '' || $age < 1 || $password === '' || $confirm === '') {
        return ['ok' => false, 'message' => 'Please fill in all fields correctly.'];
    }
    if (strlen($password) < 6) {
        return ['ok' => false, 'message' => 'Password must be at least 6 characters.'];
    }
    if ($password !== $confirm) {
        return ['ok' => false, 'message' => 'Passwords do not match.'];
    }
    if (fc_user_phone_exists($phone)) {
        return ['ok' => false, 'message' => 'Phone number already registered.'];
    }

    $userId = fc_create_user($name, $phone, $age, password_hash($password, PASSWORD_DEFAULT));
    $_SESSION['user_id'] = $userId;
    $_SESSION['user_name'] = $name;
    $_SESSION['user_phone'] = $phone;
    unset($_SESSION['admin_id'], $_SESSION['admin_username'], $_SESSION['is_admin']);

    return ['ok' => true, 'message' => 'Account created! Welcome, ' . $name . '.'];
}

function fc_login_admin(string $username, string $password): array {
    $username = trim($username);
    $password = trim($password);
    if ($username === '' || $password === '') {
        return ['ok' => false, 'message' => 'Please enter username and password.'];
    }

    $admin = fc_find_admin_by_credentials($username, $password);
    if (!$admin) {
        return ['ok' => false, 'message' => 'Invalid username or password.'];
    }

    $_SESSION['admin_id'] = $admin['id'];
    $_SESSION['admin_username'] = $admin['username'];
    $_SESSION['is_admin'] = true;
    unset($_SESSION['user_id'], $_SESSION['user_name'], $_SESSION['user_phone'], $_SESSION['cart']);

    return ['ok' => true, 'message' => 'Admin login successful.'];
}

function fc_logout_current_session(): void {
    session_unset();
    session_destroy();
    session_start();
}
