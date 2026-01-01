<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="el">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pageTitle ?? 'Used Cars') ?></title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body data-theme="light">

<!-- ✅ Particles background (ΠΑΝΤΑ ΠΙΣΩ) -->
<div id="particles-js"></div>

<!-- ✅ Όλο το περιεχόμενο ΠΑΝΩ από τα particles -->
<div id="app-content">

<nav class="navbar navbar-expand-lg glass-navbar mb-4">
  <div class="container">

    <a class="navbar-brand fw-bold" href="index.php">Used Cars</a>

    <ul class="navbar-nav ms-auto align-items-center gap-3">

      <?php if (isset($_SESSION['user_id'])): ?>
        <li class="nav-item">
          <span class="navbar-text">
            Καλώς ήρθες <?= htmlspecialchars($_SESSION['username']) ?>
          </span>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="add_car.php">➕ Καταχώρηση</a>
        </li>
        <li class="nav-item">
          <a class="nav-link text-danger" href="logout.php">Logout</a>
        </li>
      <?php else: ?>
        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
        <li class="nav-item"><a class="nav-link" href="register.php">Εγγραφή</a></li>
      <?php endif; ?>

      <!-- 🌙 Theme Switch -->
      <li class="nav-item">
        <div class="form-check form-switch ms-3">
          <input class="form-check-input" type="checkbox" id="themeSwitch">
          <label class="form-check-label" for="themeSwitch">🌙</label>
        </div>
      </li>

    </ul>
  </div>
</nav>
