<?php
include '../config/koneksi.php';
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Si Pemesanan Sepatu Online</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/produk.css">
</head>
<body>
<?php include '../sepatu_online/header.php';
?>

<div class="spacer"></div>
<div class="container mt-2" style="margin-top: 150px;">
  <?php if (isset($_SESSION['user'])): ?>
    <div class="user-greeting">
      👋 Hai, <strong><?= htmlspecialchars($_SESSION['user']['username']) ?></strong>! 
      Ingin tampil santai tapi tetap keren? <b>Sepatu casual</b> kami siap lengkapi gayamu! Nyaman dipakai, kualitas terjamin, dan pastinya harga bersahabat! 👟👌
    </div>
  <?php endif; ?>
</div>

<!-- Search Section -->
<?php
if (isset($_GET['search'])) {
  $keyword = mysqli_real_escape_string($conn, $_GET['search']);
  $query = "SELECT * FROM produk WHERE 
            nama LIKE '%$keyword%' OR 
            brand LIKE '%$keyword%' OR 
            kategori LIKE '%$keyword%'";
  $result = mysqli_query($conn, $query);
  ?>
  
  <div class="container mt-4">
    <h5>Hasil pencarian untuk: <em><?= htmlspecialchars($keyword); ?></em></h5>
    <div class="row">
      <?php if (mysqli_num_rows($result) > 0): ?>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
          <div class="col-md-3 mb-4">
            <div class="card h-100">
              <img src="../gambar/<?= $row['gambar']; ?>" class="card-img-top" alt="<?= $row['nama']; ?>">
              <div class="card-body">
                <h5 class="card-title"><?= $row['nama']; ?></h5>
                <p class="card-text">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></p>
                <a href="detail_produk.php?id=<?= $row['id']; ?>" class="btn btn-outline-primary btn-sm">Lihat Detail</a>
              </div>
            </div>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <div class="col-12">
          <p class="text-muted">Tidak ada produk ditemukan.</p>
        </div>
      <?php endif; ?>
    </div>
  </div>
<?php } ?>

<section class="container my-5">
  <div class="row">
    <?php
    $query = "SELECT * FROM produk WHERE kategori = 'sneakers' ORDER BY id DESC";
    $result = mysqli_query($koneksi, $query);
    while ($row = mysqli_fetch_assoc($result)):
      $hargaSetelahDiskon = $row['harga'] - ($row['harga'] * $row['diskon'] / 100);
    ?>
      <div class="col-md-3 mb-4">
        <div class="card h-100 shadow-sm">
        <img src="<?= htmlspecialchars($row['gambar']); ?>" class="card-img-top" alt="<?= htmlspecialchars($row['nama']); ?>">
        <div class="card-body text-center">
            <h5 class="card-title"><?= $row['nama']; ?></h5>
            <p class="card-text">
              <strong>Rp <?= number_format($hargaSetelahDiskon, 0, ',', '.'); ?></strong><br>
                <small class="text-danger">
                  <s>Rp <?= number_format($row['harga'], 0, ',', '.'); ?></s><br>
                  Diskon: <?= $row['diskon']; ?>%
                </small>
            </p>
            <a href="detail.php?id=<?= $row['id']; ?>" class="btn btn-primary btn-sm">Detail</a>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</section>

<!-- Footer -->
<footer class="footer" style="background-color: #222; color: #fff; padding: 40px 20px 0; text-align: center;">

  <div class="footer-container" style="max-width: 1200px; margin: auto; display: flex; flex-wrap: wrap; justify-content: space-between; text-align: left;">
    
    <!-- About Section -->
    <div class="footer-about" style="flex: 1 1 250px; margin: 10px;">
      <h3>Rivano Shoes</h3>
      <p>Toko sepatu terpercaya yang menyediakan sepatu sport, casual, sneakers, dan banyak lagi dengan kualitas terbaik dan harga terjangkau.</p>
    </div>
    
    <!-- Quick Links -->
    <div class="footer-links" style="flex: 1 1 150px; margin: 10px;">
      <h4>Menu</h4>
      <ul style="list-style: none; padding: 0;">
        <li><a href="index.php" style="color: #fff; text-decoration: none;">Beranda</a></li>
        <li><a href="produk.php" style="color: #fff; text-decoration: none;">Produk</a></li>
        <li><a href="keranjang.php" style="color: #fff; text-decoration: none;">Keranjang</a></li>
        <li><a href="kontak.php" style="color: #fff; text-decoration: none;">Kontak</a></li>
      </ul>
    </div>

    <!-- Contact Info -->
    <div class="footer-contact" style="flex: 1 1 200px; margin: 10px;">
      <h4>Hubungi Kami</h4>
      <p>Email: info@rivanoshoes.com</p>
      <p>Telepon: +62 812-3456-7890</p>
      <p>Alamat: Jl. Sepatu No. 123, Kota Bengkulu</p>
    </div>

    <!-- Social Media -->
    <div class="footer-social" style="flex: 1 1 150px; margin: 10px;">
      <h4>Ikuti Kami</h4>
      <a href="#" style="color: #fff; margin-right: 10px;">Facebook</a>
      <a href="#" style="color: #fff; margin-right: 10px;">Instagram</a>
      <a href="#" style="color: #fff;">Twitter</a>
    </div>
  </div>

  <!-- Garis pembatas -->
  <div style="border-top: 1px solid #555; margin-top: 30px;"></div>

  <!-- Copyright -->
  <div style="background-color: #555; padding: 30px 0; width: 1200;">
    <p style="margin: 0; color: #aaa; font-size: 16px;">© 2025 Rivano Shoes. All Rights Reserved.</p>
  </div>

</footer>
</body>
</html>