<?php
function fc_set_flash(string $message, string $type = 'error'): void {
    $_SESSION['flash'] = ['message' => $message, 'type' => $type];
}

function fc_take_flash(): ?array {
    if (empty($_SESSION['flash'])) return null;
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}

function fc_flash_html(): string {
    $flash = fc_take_flash();
    if (!$flash) return '';
    $color = ($flash['type'] ?? '') === 'success' ? 'var(--green)' : 'var(--red)';
    return '<div class="auth-error" style="display:block;color:' . $color . '">' . fc_esc($flash['message']) . '</div>';
}
