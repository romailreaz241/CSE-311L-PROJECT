<?php
function fc_handle_cart_add_request(): void {
    fc_require_user();
    if (!fc_is_post()) fc_redirect('../departments.html');

    $id = (int)($_POST['test_id'] ?? 0);
    $returnTo = $_POST['return_to'] ?? '../departments.html';

    if (fc_add_test_to_cart($id)) {
        fc_set_flash('Test added to cart.', 'success');
    }

    fc_redirect($returnTo);
}

function fc_handle_cart_remove_request(): void {
    fc_require_user();
    $id = (int)($_POST['test_id'] ?? 0);
    fc_remove_test_from_cart($id);
    fc_redirect('cart.php');
}
