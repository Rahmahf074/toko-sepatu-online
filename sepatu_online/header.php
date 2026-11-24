<header class="container-fluid py-3">
  <div class="row align-items-center">
    <div class="col-md-3 d-flex justify-content-center">
      <a href="../sepatu_online/index.php">
        <img src="../gambar/Logo.svg" alt="Logo" class="img-fluid align-self-center" style="max-height: 100px;">
      </a>
    </div>

    <div class="col-md-9">
      <div class="d-flex justify-content-end align-items-center mb-2">

        <div class="user-section">
          <?php if (isset($_SESSION['user'])): ?>
            <a href="keranjang.php" class="btn btn-light text-dark me-2">🛒</a>
            <a href="user.php" class="btn btn-light text-dark me-2">👤</a>
            <a href="logout.php" class="btn btn-light text-dark">Keluar</a>
          <?php else: ?>
            <a href="login.php" class="btn btn-light me-2">🛒</a>
            <a href="login.php" class="btn btn-light text-dark me-2">Masuk</a>
            <a href="register.php" class="btn btn-light text-dark">Daftar</a>
          <?php endif; ?>
        </div>
      </div>

      <nav class="nav justify-content-between">
        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>" href="index.php">BERANDA</a>
        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'sale.php' ? 'active' : '' ?>" href="sale.php">SALE</a>
        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'sports.php' ? 'active' : '' ?>" href="sports.php">SPORTS</a>
        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'casual.php' ? 'active' : '' ?>" href="casual.php">CASUAL</a>
        <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'sneakers.php' ? 'active' : '' ?>" href="sneakers.php">SNEAKERS</a>
      </nav>
    </div>
  </div>
</header>
