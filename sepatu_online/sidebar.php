<div class="col-md-3 sidebar">
  <h5 class="px-3 mb-3 text-center">Dashboard User</h5>
  <div class="d-flex flex-column align-items-center mb-4">
    <div style="font-size: 40px;">👤</div>
    <div class="text-muted"><?= htmlspecialchars($_SESSION['user']['username']) ?></div>
  </div>
  <a href="user.php" class="d-block py-2 <?= basename($_SERVER['PHP_SELF']) == 'user.php' ? 'active' : '' ?>">Informasi Akun</a>
  <a href="pesanan_saya.php" class="d-block py-2 <?= basename($_SERVER['PHP_SELF']) == 'pesanan_saya.php' ? 'active' : '' ?>">Riwayat Transaksi</a>
  <a href="alamat.php" class="d-block py-2 <?= basename($_SERVER['PHP_SELF']) == 'alamat.php' ? 'active' : '' ?>">Daftar Alamat</a>
  <a href="index.php" class="d-block py-2 <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">Keluar</a>
</div>
