<?php
function fc_cart_ids(): array {
    $ids = $_SESSION['cart'] ?? [];
    if (!is_array($ids)) return [];
    return array_values(array_unique(array_filter(array_map('intval', $ids))));
}

function fc_cart_count(): int { return count(fc_cart_ids()); }

function fc_cart_items(): array {
    return fc_fetch_cart_items_by_ids(fc_cart_ids());
}

function fc_add_test_to_cart(int $id): bool {
    if ($id <= 0 || !fc_test_exists($id)) return false;
    $cart = fc_cart_ids();
    if (!in_array($id, $cart, true)) $cart[] = $id;
    $_SESSION['cart'] = $cart;
    return true;
}

function fc_remove_test_from_cart(int $id): void {
    $_SESSION['cart'] = array_values(array_filter(fc_cart_ids(), function($tid) use ($id) { return $tid !== $id; }));
}

function fc_clear_cart(): void {
    $_SESSION['cart'] = [];
}
