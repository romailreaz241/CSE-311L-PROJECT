<?php require_once __DIR__ . '/bootstrap.php'; fc_require_user(); $items = fc_cart_items(); $total = 0; foreach ($items as $item) { $total += (float)$item['price']; } ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
  <title>Cart — MediSync</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css"/>
  <link rel="stylesheet" href="../css/list-pages.css"/>
  <link rel="stylesheet" href="../css/responsive.css"/>
</head>
<body>
  <header class="container glass-panel page-topbar">
    <div class="left"><a class="glass-btn-sm" href="../departments.html">← Tests</a></div>
    <h1 class="page-title">🛒 My Cart</h1>
    <div class="right"><a class="glass-btn-sm" href="logout.php">Logout</a></div>
  </header>

  <main class="container page-section">
    <section class="glass-panel list-panel">
      <?= fc_flash_html() ?>
      <?php if (!$items): ?>
        <p style="text-align:center;padding:40px;color:var(--text-3)">Your cart is empty.</p>
        <p style="text-align:center"><a class="glass-btn-sm" href="../departments.html">Browse Tests</a></p>
      <?php else: ?>
        <div class="list-stack">
          <?php foreach ($items as $t): ?>
            <div class="test-item">
              <div class="test-item-info">
                <div class="test-item-name"><?= fc_esc($t['name']) ?></div>
                <div class="test-item-desc"><?= fc_esc($t['description'] ?? '') ?></div>
                <div class="test-item-meta">
                  <span>📍 <?= fc_esc($t['room'] ?? '') ?> · <?= fc_esc($t['floor'] ?? '') ?></span>
                  <span><?= fc_esc($t['category_icon'] ?? '') ?> <?= fc_esc($t['category_name'] ?? '') ?></span>
                </div>
              </div>
              <div class="test-item-right">
                <div class="test-item-price">৳<?= number_format((float)$t['price']) ?><br><small>per test</small></div>
                <form method="post" action="cart_remove.php">
                  <input type="hidden" name="test_id" value="<?= (int)$t['id'] ?>">
                  <button class="test-add-btn" type="submit">Remove</button>
                </form>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
        <div class="glass-panel" style="margin-top:24px;padding:24px">
          <h2 style="font-size:1.15rem;color:var(--text);margin-bottom:12px">Complete Payment</h2>
          <p style="color:var(--text-2);margin-bottom:16px">Total: <strong style="color:var(--accent)">৳<?= number_format($total, 2) ?></strong></p>
          <form method="post" action="book_handler.php">
            <div style="display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:10px;margin-bottom:18px">
              <label class="glass-pill"><input type="radio" name="payment_method" value="bKash" required> bKash</label>
              <label class="glass-pill"><input type="radio" name="payment_method" value="Nagad"> Nagad</label>
              <label class="glass-pill"><input type="radio" name="payment_method" value="Rocket"> Rocket</label>
              <label class="glass-pill"><input type="radio" name="payment_method" value="Card"> Card</label>
            </div>
            <button class="glass-pill primary-action" type="submit" style="width:100%">Pay Now</button>
          </form>
        </div>
      <?php endif; ?>
    </section>
  </main>
  <script src="../js/chatbot.js"></script>
</body>
</html>
