<?php
function fc_esc($value): string {
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

function fc_redirect(string $url): void {
    header('Location: ' . $url);
    exit;
}

function fc_is_post(): bool {
    return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}

function fc_in_connection_folder(): bool {
    return basename(dirname($_SERVER['SCRIPT_NAME'] ?? '')) === 'front_end_connection';
}

function fc_root_path(string $path): string {
    if (preg_match('/^(https?:)?\/\//', $path) || substr($path, 0, 1) === '/') return $path;
    return fc_in_connection_folder() ? '../' . ltrim($path, '/') : $path;
}
