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
  <link rel="stylesheet" href="../css/index.css">
</head>
<body>

<?php include '../sepatu_online/header.php'; ?>

<div class="container mt-2" style="margin-top:150px;">
  <?php if (isset($_SESSION['user'])): ?>
    <div class="user-greeting">
      👋 Hai, <strong><?= htmlspecialchars($_SESSION['user']['username']) ?></strong>! 
      Selamat datang kembali di toko sepatu kami. Lihat koleksi terbaru kami di bawah ya! 👟✨
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
              <img src="<?= htmlspecialchars($row['gambar']); ?>" class="card-img-top" alt="<?= htmlspecialchars($row['nama']); ?>">
              <div class="card-body">
                <h5 class="card-title"><?= htmlspecialchars($row['nama']); ?></h5>
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

<div id="carouselBanner" class="carousel slide" data-bs-ride="carousel" style="margin-top: <?= isset($_SESSION['user']) ? '0px' : '150px'; ?>;">
  <div class="carousel-indicators">
    <button type="button" data-bs-target="#carouselBanner" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
    <button type="button" data-bs-target="#carouselBanner" data-bs-slide-to="1" aria-label="Slide 2"></button>
    <button type="button" data-bs-target="#carouselBanner" data-bs-slide-to="2" aria-label="Slide 3"></button>
  </div>

  <div class="carousel-inner">
    <div class="carousel-item active">
      <a href="detail.php?id=1">
        <img src="../gambar/banner1.png" class="d-block w-100" alt="Banner 1">
      </a>
    </div>
    <div class="carousel-item">
      <a href="detail.php?id=2">
        <img src="../gambar/banner2.png" class="d-block w-100" alt="Banner 2">
      </a>
    </div>
    <div class="carousel-item">
      <a href="detail.php?id=3">
        <img src="../gambar/banner3.png" class="d-block w-100" alt="Banner 3">
      </a>
    </div>
  </div>

  <button class="carousel-control-prev" type="button" data-bs-target="#carouselBanner" data-bs-slide="prev">
    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Sebelumnya</span>
  </button>
  <button class="carousel-control-next" type="button" data-bs-target="#carouselBanner" data-bs-slide="next">
    <span class="carousel-control-next-icon" aria-hidden="true"></span>
    <span class="visually-hidden">Berikutnya</span>
  </button>
</div>

<section id="brand" class="container my-5">
  <h4 class="text-left mb-4 fw-bold">Brand Populer</h4>
  <div id="brandCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <div class="d-flex justify-content-center gap-4">
          <img src="../gambar/brand1.jpg" alt="Fila" class="img-thumbnail" style="height: 100px;">
          <img src="../gambar/brand2.jpg" alt="Adidas" class="img-thumbnail" style="height: 100px;">
          <img src="../gambar/brand3.jpg" alt="Puma" class="img-thumbnail" style="height: 100px;">
          <img src="../gambar/brand4.jpg" alt="Vans" class="img-thumbnail" style="height: 100px;">
          <img src="../gambar/brand5.jpg" alt="Jordan" class="img-thumbnail" style="height: 100px;">
        </div>
      </div>

      <div class="carousel-item">
        <div class="d-flex justify-content-center gap-4">
          <img src="../gambar/brand6.jpg" alt="Reebok" class="img-thumbnail" style="height: 100px;">
          <img src="../gambar/brand7.jpg" alt="Converse" class="img-thumbnail" style="height: 100px;">
          <img src="../gambar/brand8.jpg" alt="New Balance" class="img-thumbnail" style="height: 100px;">
          <img src="../gambar/brand9.jpg" alt="Supreme" class="img-thumbnail" style="height: 100px;">
          <img src="../gambar/brand10.jpg" alt="Nike" class="img-thumbnail" style="height: 100px;">
        </div>
      </div>
    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#brandCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#brandCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
    </button>
  </div>
</section>

<section class="container my-5" id="sale.php">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold">Promo Diskon</h4>
    <a href="sale.php" class="btn btn-outline-primary">Lihat Semua &raquo;</a>
  </div>

  <div id="carouselSale" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <?php
      include '../config/koneksi.php';
      $queryDiskon = "SELECT * FROM produk WHERE diskon > 0 ORDER BY id DESC LIMIT 8";
      $result = mysqli_query($koneksi, $queryDiskon);
      $produk = [];
      while ($row = mysqli_fetch_assoc($result)) {
        $produk[] = $row;
      }

      $chunk = array_chunk($produk, 4);
      foreach ($chunk as $index => $group):
      ?>
        <div class="carousel-item <?= $index === 0 ? 'active' : ''; ?>">
          <div class="row">
            <?php foreach ($group as $row): ?>
              <div class="col-md-3 mb-4">
                <div class="card h-100 shadow-sm">
                  <img src="<?= htmlspecialchars($row['gambar']); ?>" class="card-img-top" alt="<?= htmlspecialchars($row['nama']); ?>">
                  <div class="card-body text-center">
                    <h5 class="card-title"><?= htmlspecialchars($row['nama']); ?></h5>
                    <p class="card-text mb-0">
                      <small class="text-danger"><s>Rp <?= number_format($row['harga'], 0, ',', '.'); ?></s></small><br>
                      <strong>Rp <?= number_format($row['harga'] - ($row['harga'] * $row['diskon'] / 100), 0, ',', '.'); ?></strong>
                    </p>
                    <a href="detail.php?id=<?= $row['id']; ?>" class="btn btn-primary btn-sm mt-2">Lihat</a>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <?php if (count($produk) > 4): ?>
      <button class="carousel-control-prev" type="button" data-bs-target="#carouselSale" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carouselSale" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
      </button>
    <?php endif; ?>
  </div>
</section>

<!-- Footer -->
<footer class="footer" style="background-color: #222; color: #fff; padding: 40px 20px 0; text-align: center;">
  <div class="footer-container" style="max-width: 1200px; margin: auto; display: flex; flex-wrap: wrap; justify-content: space-between; text-align: left;">
    <div class="footer-about" style="flex: 1 1 250px; margin: 10px;">
      <h3>Rivano Shoes</h3>
      <p>Toko sepatu terpercaya yang menyediakan sepatu sport, casual, sneakers, dan banyak lagi dengan kualitas terbaik dan harga terjangkau.</p>
    </div>
    
    <div class="footer-links" style="flex: 1 1 150px; margin: 10px;">
      <h4>Menu</h4>
      <ul style="list-style: none; padding: 0;">
        <li><a href="index.php" style="color: #fff; text-decoration: none;">Beranda</a></li>
        <li><a href="produk.php" style="color: #fff; text-decoration: none;">Produk</a></li>
        <li><a href="keranjang.php" style="color: #fff; text-decoration: none;">Keranjang</a></li>
        <li><a href="kontak.php" style="color: #fff; text-decoration: none;">Kontak</a></li>
      </ul>
    </div>

    <div class="footer-contact" style="flex: 1 1 200px; margin: 10px;">
      <h4>Hubungi Kami</h4>
      <p>Email: info@rivanoshoes.com</p>
      <p>Telepon: +62 812-3456-7890</p>
      <p>Alamat: Jl. Sepatu No. 123, Kota Bengkulu</p>
    </div>

    <div class="footer-social" style="flex: 1 1 150px; margin: 10px;">
      <h4>Ikuti Kami</h4>
      <a href="#" style="color: #fff; margin-right: 10px;">Facebook</a>
      <a href="#" style="color: #fff; margin-right: 10px;">Instagram</a>
      <a href="#" style="color: #fff;">Twitter</a>
    </div>
  </div>

  <div style="border-top: 1px solid #555; margin-top: 30px;"></div>

  <div style="background-color: #555; padding: 30px 0;">
    <p style="margin: 0; color: #aaa; font-size: 16px;">© 2025 Rivano Shoes. All Rights Reserved.</p>
  </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
  const myCarousel = document.querySelector('#carouselBanner');
  const carousel = new bootstrap.Carousel(myCarousel, {
    interval: 5000,
    ride: 'carousel'
  });
</script>

</body>
</html>
