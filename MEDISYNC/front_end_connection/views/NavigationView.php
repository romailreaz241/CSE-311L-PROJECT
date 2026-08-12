<?php
function fc_nav_actions(string $defaultHtml = ''): string {
    if (fc_is_user()) {
        return '<span class="nav-username">👤 ' . fc_esc(fc_user_name()) . '</span>' .
               '<a class="nav-cart-btn" href="front_end_connection/cart.php">🛒 <span class="cart-badge">' . fc_cart_count() . '</span></a>' .
               '<a class="nav-logout-btn" href="front_end_connection/logout.php">Logout</a>';
    }
    if (fc_is_admin()) {
        return '<span class="nav-username">⚙️ ' . fc_esc(fc_admin_name()) . '</span>' .
               '<a class="nav-admin-btn" href="admin.html">📋 Orders</a>' .
               '<a class="nav-admin-btn" href="admin-tests.html" style="margin-left:4px">🧪 Tests</a>' .
               '<a class="nav-logout-btn" href="front_end_connection/logout.php">Logout</a>';
    }
    return $defaultHtml ?: '<a href="login.html" class="nav-login">Login</a><a href="signup.html" class="glass-btn-sm">Sign Up</a>';
}

function fc_topbar_actions(string $fallback = '<a class="glass-btn-sm" href="index.html">Home</a>'): string {
    if (fc_is_user()) {
        return '<span class="glass-btn-sm nav-username topbar-user">👤 ' . fc_esc(fc_user_name()) . '</span>' .
               '<a class="glass-btn-sm topbar-cart" href="front_end_connection/cart.php">🛒 <span>Cart</span> <span class="cart-badge">' . fc_cart_count() . '</span></a>' .
               '<a class="glass-btn-sm topbar-logout" href="front_end_connection/logout.php">Logout</a>';
    }
    if (fc_is_admin()) {
        return '<span class="glass-btn-sm nav-username topbar-user">⚙️ ' . fc_esc(fc_admin_name()) . '</span>' .
               '<a class="glass-btn-sm topbar-admin" href="admin.html">Orders</a>' .
               '<a class="glass-btn-sm topbar-logout" href="front_end_connection/logout.php">Logout</a>';
    }
    return $fallback;
}
