<?php
session_start();
include '../config/koneksi.php';

$id = $_GET['id'] ?? null;
if ($id) {
    $query = "SELECT * FROM produk WHERE id = $id";
    $result = mysqli_query($koneksi, $query);
    if (mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
    } else {
        echo "Produk tidak ditemukan."; exit;
    }
} else {
    echo "ID produk tidak ditemukan."; exit;
}

// Tambah ke keranjang
if (isset($_POST['add_to_cart'])) {
    $size = $_POST['size'];
    $quantity = min((int)$_POST['quantity'], $product['stok']);

    $discount = $product['diskon'] ?? 0;
    $hargaSetelahDiskon = $product['harga'];

    if ($discount > 0) {
    $hargaSetelahDiskon = $product['harga'] - ($product['harga'] * $discount / 100);
    }

    $_SESSION['cart'][$product['id']] = [
    'id' => $product['id'],
    'name' => $product['nama'],
    'price' => $hargaSetelahDiskon,  // harga sudah dikurangi diskon
    'quantity' => $quantity,
    'image' => $product['gambar'],
    'size' => $size,
    'discount' => $discount 
    ];
    header("Location: keranjang.php");
    exit();
}

// Beli sekarang
if (isset($_POST['buy_now'])) {
    $size = $_POST['size'];
    $quantity = min((int)$_POST['quantity'], $product['stok']);

    $_SESSION['checkout'] = [
        'product_id' => $product['id'],
        'quantity' => $quantity,
        'size' => $size
    ];
    header("Location: checkout.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/detail.css">
</head>
<body>
<?php include '../sepatu_online/header.php'; ?>

<div class="container detail-container mt-5 mb-5">
    <div class="position-relative bg-white shadow rounded p-4 d-flex flex-wrap" style="gap: 30px;">
        
        <!-- Tombol X Kembali -->
        <a href="sale.php" class="btn-close position-absolute" style="top: 15px; right: 15px;" aria-label="Close"></a>

<!-- Kotak Gambar -->
<div class="image-box" style="flex: 1; min-width: 300px; display: flex; justify-content: center; align-items: center;">
    <img src="<?= htmlspecialchars($product['gambar']); ?>" class="img-fluid rounded" style="max-height: 400px;" alt="<?= htmlspecialchars($product['nama']); ?>">
</div>


        <!-- Kotak Detail -->
        <div class="detail-box" style="flex: 1; min-width: 300px;">
            <h2 class="mb-3"><?= $product['nama']; ?></h2>
            <p><strong>Deskripsi:</strong> <?= $product['deskripsi']; ?></p>
            <p><strong>Brand:</strong> <?= $product['brand']; ?></p>
            <p><strong>Kategori:</strong> <?= $product['kategori']; ?></p>
            <p><strong>Ukuran Tersedia:</strong> <?= $product['ukuran']; ?></p>
            <p><strong>Stok Tersedia:</strong> <?= $product['stok']; ?></p>
        <?php if ($product['diskon'] > 0): ?>
            <?php 
                $hargaDiskon = $product['harga'] - ($product['harga'] * $product['diskon'] / 100);
            ?>
            <p class="card-text">
                <strong>Harga Setelah Diskon:</strong> Rp <?= number_format($hargaDiskon, 0, ',', '.'); ?><br>
                <small class="text-danger"><s>Rp <?= number_format($product['harga'], 0, ',', '.'); ?></s> (Diskon <?= $product['diskon']; ?>%)
            </small>
            </p>
        <?php else: ?>
            <p><strong>Harga:</strong> Rp <?= number_format($product['harga'], 0, ',', '.'); ?></p>
        <?php endif; ?>

            <!-- Form Beli -->
            <form method="POST" class="mt-4">
                <div class="mb-3">
                    <label for="size" class="form-label"><strong>Pilih Ukuran:</strong></label>
                    <select name="size" id="size" class="form-select" required>
                        <option value="">-- Pilih Ukuran --</option>
                        <?php for ($i = 36; $i <= 45; $i++): ?>
                            <option value="<?= $i; ?>"><?= $i; ?></option>
                        <?php endfor; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="quantity" class="form-label"><strong>Jumlah:</strong></label>
                    <input type="number" name="quantity" id="quantity" class="form-control" min="1" max="<?= $product['stok']; ?>" value="1" required>
                </div>

                <div class="d-flex gap-2 flex-wrap">
                    <button type="submit" name="add_to_cart" class="btn btn-success">🛒</button>
                    <button type="submit" name="buy_now" class="btn btn-primary">Beli Sekarang</button>
                </div>
            </form>
        </div>

    </div>
</div>


<footer class="footer" style="background-color: #222; color: #fff; padding: 40px 20px 0; text-align: center;">
  <div class="footer-container" style="display: flex; flex-wrap: wrap; justify-content: space-between; text-align: left;">
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
<div style="background-color: #555; padding: 0; width: 100%; margin: 0;">
  <p style="margin: 0; color: #aaa; font-size: 16px;">© 2025 Rivano Shoes. All Rights Reserved.</p>
</div>

</footer>
</body>
</html>
