<?php 
session_start();
include '../config/koneksi.php';

// Update status jika admin mengubahnya
if (isset($_POST['update_status'])) {
    $pesanan_id = intval($_POST['pesanan_id']);
    $new_status = mysqli_real_escape_string($koneksi, $_POST['status']);
    mysqli_query($koneksi, "UPDATE pesanan SET status = '$new_status' WHERE id = '$pesanan_id'");
}

// Hapus pesanan jika admin klik tombol hapus
if (isset($_POST['hapus_pesanan'])) {
    $pesanan_id = intval($_POST['pesanan_id']);
    mysqli_query($koneksi, "DELETE FROM pesanan WHERE id = '$pesanan_id'");
}

// Query untuk mengambil data pesanan
$query = "
    SELECT p.*, u.nama_depan
    FROM pesanan p
    JOIN user u ON p.user_id = u.id
    ORDER BY p.created_at DESC
";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Pelanggan - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/admin.css"> <!-- Kalau ada tambahan CSS -->
</head>
<body>

<!-- Header -->
<header class="bg-dark text-white text-center py-3">
    <img src="../gambar/Logo.svg" alt="Logo" class="img-fluid" style="max-height: 50px;">
</header>

<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-2 sidebar">
            <h6 class="text-center mb-3">Dashboard Admin</h6>
            <div class="text-center mb-4">
                <div style="font-size: 30px;">👤</div>
                <div class="text-muted">
                    <?= isset($_SESSION['user']['username']) ? htmlspecialchars($_SESSION['user']['username']) : 'admin'; ?>
                </div>
            </div>
            <a href="dashboard.php" class="d-block mb-2 <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'fw-bold text-primary' : '' ?>">Kelola Produk</a>
            <a href="pesanan.php" class="d-block mb-2 <?= basename($_SERVER['PHP_SELF']) == 'pesanan.php' ? 'fw-bold text-primary' : '' ?>">Kelola Transaksi</a>
            <a href="list_user.php" class="d-block mb-2 <?= basename($_SERVER['PHP_SELF']) == 'list_user.php' ? 'fw-bold text-primary' : '' ?>">Kelola User</a>
            <a href="../sepatu_online/logout.php" class="d-block text-danger">Keluar</a>
        </div>

        <!-- Konten utama -->
        <div class="col-md-10 content-wrapper p-4">
            <br>
            <br>
            <h3>Data Pesanan Pelanggan</h3>

            <div class="table-responsive mt-4">
                <table class="table table-bordered align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Pelanggan</th>
                            <th>Tanggal</th>
                            <th>Total Harga</th>
                            <th>Alamat Pengiriman</th>
                            <th>Catatan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['nama_depan']) ?></td>
                            <td><?= date('d-m-Y H:i', strtotime($row['created_at'])) ?></td>
                            <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                            <td><?= nl2br(htmlspecialchars($row['alamat_pengiriman'])) ?></td>
                            <td><?= nl2br(htmlspecialchars($row['catatan'])) ?></td>
                            <td>
                                <form method="POST" class="d-flex">
                                    <input type="hidden" name="pesanan_id" value="<?= $row['id'] ?>">
                                    <select name="status" class="form-select form-select-sm me-2">
                                        <?php 
                                        $status_options = ['pending', 'paid', 'shipped', 'completed', 'cancelled'];
                                        foreach ($status_options as $status) {
                                            $selected = ($row['status'] == $status) ? 'selected' : '';
                                            echo "<option value='$status' $selected>" . ucfirst($status) . "</option>";
                                        }
                                        ?>
                                    </select>
                                    <button type="submit" name="update_status" class="btn btn-sm btn-primary me-2">Simpan</button>
                                    <button type="submit" name="hapus_pesanan" class="btn btn-sm btn-danger" onclick="return confirm('Yakin mau hapus pesanan ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
