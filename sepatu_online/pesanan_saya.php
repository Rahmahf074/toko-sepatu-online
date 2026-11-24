<?php
session_start();
include '../config/koneksi.php';

// Ambil ID user dari session
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Jika ada request untuk membatalkan pesanan
if (isset($_GET['batal']) && is_numeric($_GET['batal'])) {
    $pesanan_id = intval($_GET['batal']);
    // Pastikan hanya pesanan milik user yang bisa dihapus
    mysqli_query($koneksi, "DELETE FROM pesanan WHERE id = '$pesanan_id' AND user_id = '$user_id'");
    header("Location: pesanan_saya.php");
    exit;
}

// Ambil data pesanan user
$result = mysqli_query($koneksi, "SELECT * FROM pesanan WHERE user_id = '$user_id' ORDER BY created_at DESC");

// Cek jika query error
if (!$result) {
    die("Query gagal: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Riwayat Pesanan - Rivaro Shoes</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/user.css">
</head>
<body>

<?php include '../sepatu_online/header.php'; ?>

<div class="container-fluid mt-4">
  <div class="row">
    <?php include '../sepatu_online/sidebar.php'; ?>

    <div class="col-md-9 jarak">
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>

      <h3 class="mb-4">Riwayat Pesanan</h3>

      <?php if (mysqli_num_rows($result) > 0): ?>
        <div class="table-responsive">
          <table class="table table-bordered align-middle">
            <thead class="table-light">
              <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Total Harga</th>
                <th>Status</th>
                <th>Alamat Pengiriman</th>
                <th>Catatan</th>
                <th>Aksi</th> <!-- Tambahan kolom Aksi -->
              </tr>
            </thead>
            <tbody>
              <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= date('d-m-Y H:i', strtotime($row['created_at'])) ?></td>
                <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                <td><span class="badge bg-primary"><?= ucfirst($row['status']) ?></span></td>
                <td><?= nl2br(htmlspecialchars($row['alamat_pengiriman'])) ?></td>
                <td><?= nl2br(htmlspecialchars($row['catatan'])) ?></td>
                <td>
                  <a href="?batal=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">Batalkan</a>
                </td>
              </tr>
              <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      <?php else: ?>
        <div class="alert alert-info text-center mt-5">Belum ada pesanan.</div>
      <?php endif; ?>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
