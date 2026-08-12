<?php require_once __DIR__ . '/bootstrap.php'; fc_require_user(); $booking = fc_booking_success_data(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
  <title>Booking Successful — MediSync</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../css/style.css"/>
  <link rel="stylesheet" href="../css/list-pages.css"/>
  <link rel="stylesheet" href="../css/responsive.css"/>
</head>
<body>
  <header class="container glass-panel page-topbar">
    <div class="left"><a class="glass-btn-sm" href="../departments.html">← Tests</a></div>
    <h1 class="page-title">✅ Payment Successful</h1>
    <div class="right"><a class="glass-btn-sm" href="../index.html">Home</a></div>
  </header>
  <main class="container page-section">
    <section class="glass-panel list-panel" style="text-align:center">
      <?php if (!$booking): ?>
        <p style="color:var(--red)">Booking not found.</p>
      <?php else: ?>
        <div style="font-size:3rem;margin-bottom:12px">✅</div>
        <h2 style="color:var(--green);font-size:1.5rem;margin-bottom:8px">Your tests have been booked.</h2>
        <div style="background:linear-gradient(135deg,var(--accent),#1a6dca);border-radius:var(--r);padding:20px;margin:18px auto;max-width:420px">
          <div style="font-size:.75rem;font-weight:700;letter-spacing:.1em;text-transform:uppercase;color:#fff;opacity:.85">Your Token Number</div>
          <div style="font-size:2.6rem;font-weight:800;letter-spacing:3px;color:#fff">#<?= fc_esc($booking['token_number']) ?></div>
          <div style="font-size:.75rem;color:#fff;opacity:.8">Show this at the reception desk</div>
        </div>
        <div style="background:var(--bg-3);border:1px solid var(--glass-border);border-radius:var(--r);padding:16px;margin:18px auto;text-align:left;max-width:560px">
          <div style="font-weight:700;color:var(--text);margin-bottom:10px">Booked Tests</div>
          <?php foreach ($booking['items'] as $item): ?>
            <div style="display:flex;justify-content:space-between;padding:7px 0;border-bottom:1px solid var(--glass-border);font-size:.85rem;color:var(--text-2)">
              <span><?= fc_esc($item['name']) ?></span>
              <span style="color:var(--accent);font-weight:700"><?= fc_esc($item['room']) ?> · <?= fc_esc($item['floor']) ?></span>
            </div>
          <?php endforeach; ?>
        </div>
        <a class="glass-pill primary-action" href="../departments.html" style="display:inline-block;text-decoration:none">Continue →</a>
      <?php endif; ?>
    </section>
  </main>
</body>
</html>
