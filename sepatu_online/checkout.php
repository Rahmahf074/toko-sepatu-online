<?php
session_start();
include '../config/koneksi.php';

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Mengambil alamat pengiriman
$alamat_query = mysqli_query($koneksi, "SELECT * FROM alamat_pengiriman WHERE user_id = '$user_id' LIMIT 1");
$alamat = mysqli_fetch_assoc($alamat_query);

// Mengambil data keranjang
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $items = $_SESSION['cart'];
    $total_harga = 0;
    foreach ($items as $id => $item) {
        $total_harga += $item['price'] * $item['quantity'];
    }
} else {
    echo "<script>alert('Keranjang Anda kosong. Silakan tambahkan produk terlebih dahulu.'); window.location='keranjang.php';</script>";
    exit;
}

// Proses checkout
if (isset($_POST['checkout'])) {
    $metode_pembayaran = $_POST['metode_pembayaran'];
    $catatan = mysqli_real_escape_string($koneksi, $_POST['catatan']);
    $alamat_pengiriman = $_POST['alamat_pengiriman'];

    if (empty($alamat_pengiriman)) {
        echo "<script>alert('Alamat pengiriman belum ada.'); window.location='alamat.php';</script>";
        exit;
    }

    $insert_pesanan = mysqli_query($koneksi, "INSERT INTO pesanan (user_id, total_harga, status, alamat_pengiriman, catatan) VALUES ('$user_id', '$total_harga', 'pending', '$alamat_pengiriman', '$metode_pembayaran - $catatan')");

    if ($insert_pesanan) {
        $pesanan_id = mysqli_insert_id($koneksi);

        foreach ($items as $item) {
          
            $jumlah = $item['quantity'];
            $harga = $item['price'];

            mysqli_query($koneksi, "INSERT INTO pesanan_detail (pesanan_id, jumlah, harga) VALUES ('$pesanan_id', '$jumlah', '$harga')");
        }

        // Hapus data keranjang setelah checkout
        mysqli_query($koneksi, "DELETE FROM keranjang WHERE user_id = '$user_id'");

        // Langsung arahkan ke halaman pesanan_saya.php
        echo "<script>alert('Pesanan berhasil dibuat!'); window.location='pesanan_saya.php';</script>";
        exit;
    } else {
        echo "<script>alert('Gagal membuat pesanan.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/co.css">
</head>
<body>

<div class="container">
    <a href="index.php" class="close-btn">×</a>

    <h2>Checkout</h2>

    <!-- Alamat -->
    <div class="section">
        <h3>Alamat Pengiriman</h3>
        <?php if ($alamat): ?>
            <p><strong>Nama Penerima:</strong> <?= htmlspecialchars($alamat['nama_penerima']) ?></p>
            <p><strong>Nomor HP:</strong> <?= htmlspecialchars($alamat['no_hp']) ?></p>
            <p><strong>Alamat:</strong> <?= htmlspecialchars($alamat['alamat_lengkap']) ?></p>
            <p><strong>Kota:</strong> <?= htmlspecialchars($alamat['kota']) ?></p>
            <p><strong>Provinsi:</strong> <?= htmlspecialchars($alamat['provinsi']) ?></p>
            <p><strong>Kode Pos:</strong> <?= htmlspecialchars($alamat['kode_pos']) ?></p>
        <?php else: ?>
            <p>Belum ada alamat. <a href="alamat.php">Tambah Alamat</a></p>
        <?php endif; ?>
    </div>

    <!-- Produk -->
    <div class="section">
        <h3>Produk yang Dibeli</h3>
        <div class="produk-list">
            <?php foreach ($items as $item): ?>
                <div class="produk-item d-flex mb-3">
                    <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px; margin-right: 10px;">
                    <div>
                        <h5><?= htmlspecialchars($item['name']) ?></h5>
                        <p>Ukuran: <?= htmlspecialchars($item['size']) ?></p>
                        <p>Jumlah: <?= $item['quantity'] ?></p>
                        <p>Harga: Rp <?= number_format($item['price'], 0, ',', '.') ?></p>
                        <p>Subtotal: Rp <?= number_format($item['price'] * $item['quantity'], 0, ',', '.') ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <hr>
        <strong>Total: Rp <?= number_format($total_harga, 0, ',', '.') ?></strong>
    </div>

    <!-- Form Checkout -->
    <form method="POST" class="section">
        <h3>Pilih Pembayaran</h3>
        <div class="form-group">
            <select name="metode_pembayaran" class="form-select" required>
                <option value="">-- Pilih Metode --</option>
                <option value="COD">COD (Bayar di Tempat)</option>
                <option value="Cash">Cash</option>
            </select>
        </div>

        <div class="form-group mt-2">
            <label>Catatan (Opsional)</label>
            <textarea name="catatan" rows="3" class="form-control"></textarea>
        </div>

        <input type="hidden" name="alamat_pengiriman" value="<?= htmlspecialchars($alamat['alamat_lengkap'] ?? '') ?>">

        <div class="btn-group mt-3">
            <a href="keranjang.php" class="btn btn-danger">Batal</a>
            <button type="submit" name="checkout" class="btn btn-primary">Checkout</button>
        </div>
    </form>
</div>

</body>
</html>
