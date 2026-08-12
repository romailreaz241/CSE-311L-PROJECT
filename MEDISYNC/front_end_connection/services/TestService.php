<?php
function fc_filter_tests_for_page(array $tests, string $page): array {
    $filters = [
        'ear' => ['audiometry','tympan','vestibul','otomicro','tuning fork','ear'],
        'nose' => ['nasal','nose','sinus','fiberscope','allergy','ct scan','mri'],
        'throat' => ['throat','laryngo','stroboscop','swab','pcr','gram stain'],
    ];
    if (!isset($filters[$page])) return $tests;
    $keywords = $filters[$page];
    return array_values(array_filter($tests, function($t) use ($keywords) {
        $haystack = strtolower(($t['name'] ?? '') . ' ' . ($t['description'] ?? ''));
        foreach ($keywords as $keyword) {
            if (strpos($haystack, $keyword) !== false) return true;
        }
        return false;
    }));
}

function fc_category_id_for_page(string $page): int {
    $map = ['general' => 1, 'heart' => 2, 'liver' => 3, 'kidney' => 4, 'gynecology' => 5, 'ear' => 6, 'nose' => 6, 'throat' => 6];
    return $map[$page] ?? 0;
}

function fc_update_test_from_input(array $input): array {
    $testId = (int)($input['test_id'] ?? 0);
    $price = (float)($input['price'] ?? 0);
    $room = trim($input['room'] ?? '');
    $floor = trim($input['floor'] ?? '');

    if (!$testId || $price <= 0 || $room === '' || $floor === '') {
        return ['ok' => false, 'message' => 'Invalid test data. Please fill price, room and floor.'];
    }

    fc_update_test_location_and_price($testId, $price, $room, $floor);
    return ['ok' => true, 'message' => 'Test updated successfully.'];
}
