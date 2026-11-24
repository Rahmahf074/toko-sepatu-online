<?php
session_start();
include '../config/koneksi.php';

// Query untuk menampilkan daftar user
$query = "SELECT * FROM user ORDER BY created_at DESC";
$result = mysqli_query($koneksi, $query);

// Cek jika ada parameter 'delete' di URL untuk menghapus user
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);

    // Query untuk menghapus data user berdasarkan ID
    $delete_query = "DELETE FROM user WHERE id = $id";
    if (mysqli_query($koneksi, $delete_query)) {
        echo "<script>alert('User berhasil dihapus'); window.location='list_user.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus user'); window.location='list_user.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kelola User - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/admin.css">
</head>
<body>

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
                    <?php echo isset($_SESSION['user']['username']) ? htmlspecialchars((string) $_SESSION['user']['username']) : 'admin'; ?>
                </div>
            </div>
            <a href="dashboard.php" class="d-block mb-2 <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'fw-bold text-primary' : '' ?>">Kelola Produk</a>
            <a href="pesanan.php" class="d-block mb-2 <?= basename($_SERVER['PHP_SELF']) == 'pesanan.php' ? 'fw-bold text-primary' : '' ?>">Kelola Transaksi</a>
            <a href="list_user.php" class="d-block mb-2 <?= basename($_SERVER['PHP_SELF']) == 'list_user.php' ? 'fw-bold text-primary' : '' ?>">Kelola User</a>
            <a href="../sepatu_online/logout.php" class="d-block text-danger">Keluar</a>
        </div>
        
        <!-- Konten utama -->
        <div class="col-md-10 content-wrapper p-4">
            <h2 class="mb-4 text-center">Daftar User</h2>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Nama Depan</th>
                            <th>Nama Belakang</th>
                            <th>Email</th>
                            <th>No HP</th>
                            <th>Tanggal Daftar</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>
                                <td>{$no}</td>
                                <td>" . htmlspecialchars($row['nama_depan'] ?? '') . "</td>
                                <td>" . htmlspecialchars($row['nama_belakang'] ?? '') . "</td>
                                <td>" . htmlspecialchars($row['email'] ?? '') . "</td>
                                <td>" . htmlspecialchars($row['no_hp'] ?? '') . "</td>
                                <td>" . htmlspecialchars($row['created_at'] ?? '') . "</td>
                                <td>
                                    <a href='edit_user.php?id=" . $row['id'] . "' class='btn btn-warning btn-sm'>Edit</a>
                                    <a href='list_user.php?delete=" . $row['id'] . "' class='btn btn-danger btn-sm' onclick='return confirm(\"Are you sure?\")'>Hapus</a>
                                </td>
                            </tr>";
                            $no++;
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
  </div>
</body>
</html>