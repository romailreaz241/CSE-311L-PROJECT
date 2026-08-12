<?php
function fc_test_card(array $t): string {
    $id = (int)$t['id'];
    $inCart = in_array($id, fc_cart_ids(), true);
    $description = $t['description'] ?? '';
    $prep = $t['preparation'] ?? '';
    $price = number_format((float)$t['price']);
    if (fc_is_admin()) {
        $action = '<div class="test-actions"><a class="test-add-btn" href="admin-tests.html" title="Edit this test">⚙️ Edit</a></div>';
    } else {
        $chat = '<button class="test-chat-btn" type="button" onclick="askBot(\'' . fc_esc($t['name']) . '\')">🤖</button>';
        if (fc_is_user()) {
            $action = '<form method="post" action="front_end_connection/cart_add.php" style="display:inline">' .
                      '<input type="hidden" name="test_id" value="' . $id . '">' .
                      '<input type="hidden" name="return_to" value="' . fc_esc($_SERVER['REQUEST_URI'] ?? '../departments.html') . '">' .
                      '<button class="test-add-btn ' . ($inCart ? 'added' : '') . '" type="submit">' . ($inCart ? '✓ Added' : 'Add to Cart') . '</button>' .
                      '</form>';
        } else {
            $action = '<a class="test-add-btn" href="login.html">Login to Book</a>';
        }
        $action = '<div class="test-actions">' . $chat . $action . '</div>';
    }
    return '<div class="test-item">' .
        '<div class="test-item-info">' .
        '<div class="test-item-name">' . fc_esc($t['name']) . '</div>' .
        '<div class="test-item-desc">' . fc_esc($description) . '</div>' .
        '<div class="test-item-meta"><span>⏱️ ' . fc_esc($t['duration_min'] ?? '') . ' mins</span><span>📍 ' . fc_esc($t['room'] ?? '') . ' · ' . fc_esc($t['floor'] ?? '') . '</span></div>' .
        '<div class="test-item-prep">📋 ' . fc_esc(mb_substr($prep, 0, 90)) . '…</div>' .
        '</div>' .
        '<div class="test-item-right"><div class="test-item-price">৳' . $price . '<br><small>per test</small></div>' . $action . '</div>' .
        '</div>';
}

function fc_render_test_list_for_page(string $page): string {
    $catId = fc_category_id_for_page($page);
    if (!$catId) return '<p style="text-align:center;padding:40px;color:var(--text-3)">No category found.</p>';
    $tests = fc_filter_tests_for_page(fc_fetch_tests($catId), $page);
    if (!$tests) return '<p style="text-align:center;padding:40px;color:var(--text-3)">No tests found for this category.</p>';
    return implode('', array_map('fc_test_card', $tests));
}

function fc_department_search_html(): string {
    $q = trim($_GET['q'] ?? '');
    if ($q === '') return '';
    $tests = fc_fetch_tests(0, $q);
    $cards = '';
    foreach ($tests as $t) {
        $id = (int)$t['id'];
        $inCart = in_array($id, fc_cart_ids(), true);
        if (fc_is_user()) {
            $btn = '<form method="post" action="front_end_connection/cart_add.php" style="margin-top:12px">' .
                   '<input type="hidden" name="test_id" value="' . $id . '">' .
                   '<input type="hidden" name="return_to" value="' . fc_esc($_SERVER['REQUEST_URI'] ?? '../departments.html') . '">' .
                   '<button class="dtc-add ' . ($inCart ? 'added' : '') . '" type="submit">' . ($inCart ? '✓ Added' : 'Add to Cart') . '</button>' .
                   '</form>';
        } else {
            $btn = '<a class="dtc-add" href="login.html">Login to Book</a>';
        }
        $cards .= '<div class="dept-test-card">' .
                  '<div class="dtc-cat">' . fc_esc($t['category_icon'] ?? '') . ' ' . fc_esc($t['category_name'] ?? '') . '</div>' .
                  '<div class="dtc-name">' . fc_esc($t['name']) . '</div>' .
                  '<div class="dtc-price">৳' . number_format((float)$t['price']) . '</div>' . $btn . '</div>';
    }
    if ($cards === '') $cards = '<div class="no-results">No tests found for "<strong>' . fc_esc($q) . '</strong>".</div>';
    return '<div class="search-mode-header"><h2>🔍 Results for "<em>' . fc_esc($q) . '</em>"</h2><a class="back-to-grid" href="departments.html">← Back to Departments</a></div>' .
           '<div class="dept-search-grid">' . $cards . '</div>';
}
