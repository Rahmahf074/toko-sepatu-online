<?php
session_start();
include '../config/koneksi.php';

// Proteksi: hanya admin
if (!isset($_SESSION['admin']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: ../sepatu_online/login.php?role=admin");
    exit();
}

// Ambil data admin dari session
$admin = $_SESSION['admin'];
$admin_id = (int) $admin['id'];
$admin_name = htmlspecialchars($admin['username']);

// Menambah produk baru
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_product'])) {
    $nama = $_POST['nama'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];
    $diskon = $_POST['diskon'];
    $ukuran = $_POST['ukuran'];
    $brand = $_POST['brand'];
    $kategori = $_POST['kategori'];
    $stok = $_POST['stok'];
    $gambar = $_POST['gambar'];

    $query = "INSERT INTO produk (nama, deskripsi, harga, diskon, ukuran, brand, kategori, gambar, stok) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "ssdiisssi", $nama, $deskripsi, $harga, $diskon, $ukuran, $brand, $kategori, $gambar, $stok);
    mysqli_stmt_execute($stmt);
}

// Ambil data untuk edit
$edit_data = null;
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $result_edit = mysqli_query($koneksi, "SELECT * FROM produk WHERE id = $edit_id");
    $edit_data = mysqli_fetch_assoc($result_edit);
}

// Update produk
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_product'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga'];
    $diskon = $_POST['diskon'];
    $ukuran = $_POST['ukuran'];
    $brand = $_POST['brand'];
    $kategori = $_POST['kategori'];
    $stok = $_POST['stok'];
    $gambar = $_POST['gambar'];

    $query = "UPDATE produk SET 
        nama='$nama',
        deskripsi='$deskripsi',
        harga='$harga',
        diskon='$diskon',
        ukuran='$ukuran',
        brand='$brand',
        kategori='$kategori',
        stok='$stok',
        gambar='$gambar'
        WHERE id='$id'";
    mysqli_query($koneksi, $query);
    header("Location: dashboard.php");
    exit();
}

// Menghapus produk
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $query = "DELETE FROM produk WHERE id='$id'";
    mysqli_query($koneksi, $query);
    header("Location: dashboard.php");
    exit();
}

// Menampilkan produk
$query = "SELECT * FROM produk";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/admin.css">
</head>
<body>

<header class="bg-dark text-white text-center py-3">
    <img src="../Gambar/Logo.svg" alt="Logo" class="img-fluid" style="max-height: 50px;">
</header>

<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <div class="col-md-2 sidebar">
        <h6 class="text-center mb-3">Dashboard Admin</h6>
        <div class="text-center mb-4">
            <div style="font-size: 30px;">👤</div>
            <div class="text-muted">
                <?= $admin_name ?>
            </div>
        </div>
        <a href="dashboard.php" class="d-block mb-2 <?= basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'fw-bold text-primary' : '' ?>">Kelola Produk</a>
        <a href="pesanan.php" class="d-block mb-2 <?= basename($_SERVER['PHP_SELF']) == 'pesanan.php' ? 'fw-bold text-primary' : '' ?>">Kelola Transaksi</a>
        <a href="list_user.php" class="d-block mb-2 <?= basename($_SERVER['PHP_SELF']) == 'list_user.php' ? 'fw-bold text-primary' : '' ?>">Kelola User</a>
        <a href="../sepatu_online/logout.php" class="d-block text-danger">Keluar</a>
    </div>

    <!-- Konten -->
    <div class="col-md-10 content-wrapper p-4">

      <!-- Form produk -->
      <form method="POST">
        <input type="hidden" name="id" value="<?= $edit_data ? $edit_data['id'] : '' ?>">

        <div class="mb-3">
          <label for="nama" class="form-label">Nama Produk</label>
          <input type="text" class="form-control" id="nama" name="nama" required value="<?= $edit_data ? htmlspecialchars($edit_data['nama']) : '' ?>">
        </div>

        <div class="mb-3">
          <label for="deskripsi" class="form-label">Deskripsi</label>
          <textarea class="form-control" id="deskripsi" name="deskripsi"><?= $edit_data ? htmlspecialchars($edit_data['deskripsi']) : '' ?></textarea>
        </div>

        <div class="mb-3">
          <label for="harga" class="form-label">Harga</label>
          <input type="number" class="form-control" id="harga" name="harga" required value="<?= $edit_data ? htmlspecialchars($edit_data['harga']) : '' ?>">
        </div>

        <div class="mb-3">
          <label for="diskon" class="form-label">Diskon</label>
          <input type="number" class="form-control" id="diskon" name="diskon" value="<?= $edit_data ? htmlspecialchars($edit_data['diskon']) : '' ?>">
        </div>

        <div class="mb-3">
          <label for="ukuran" class="form-label">Ukuran</label>
          <input type="text" class="form-control" id="ukuran" name="ukuran" value="<?= $edit_data ? htmlspecialchars($edit_data['ukuran']) : '' ?>">
        </div>

        <div class="mb-3">
          <label for="brand" class="form-label">Brand</label>
          <input type="text" class="form-control" id="brand" name="brand" value="<?= $edit_data ? htmlspecialchars($edit_data['brand']) : '' ?>">
        </div>

        <div class="mb-3">
          <label for="kategori" class="form-label">Kategori</label>
          <select class="form-control" id="kategori" name="kategori">
            <option value="sport" <?= $edit_data && $edit_data['kategori'] == 'sport' ? 'selected' : '' ?>>Sport</option>
            <option value="casual" <?= $edit_data && $edit_data['kategori'] == 'casual' ? 'selected' : '' ?>>Casual</option>
            <option value="sneakers" <?= $edit_data && $edit_data['kategori'] == 'sneakers' ? 'selected' : '' ?>>Sneakers</option>
          </select>
        </div>

        <div class="mb-3">
          <label for="stok" class="form-label">Stok</label>
          <input type="number" class="form-control" id="stok" name="stok" required value="<?= $edit_data ? htmlspecialchars($edit_data['stok']) : '' ?>">
        </div>

        <div class="mb-3">
          <label for="gambar" class="form-label">URL Gambar</label>
          <input type="text" class="form-control" id="gambar" name="gambar" placeholder="https://example.com/gambar.jpg" value="<?= $edit_data ? htmlspecialchars($edit_data['gambar']) : '' ?>">
        </div>

        <button type="submit" class="btn btn-primary" name="<?= $edit_data ? 'update_product' : 'add_product' ?>">
          <?= $edit_data ? 'Update Produk' : 'Tambah Produk' ?>
        </button>
      </form>

      <hr>

      <h3>Daftar Produk</h3>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>#</th>
            <th>Nama</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Gambar</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($product = mysqli_fetch_assoc($result)): ?>
          <tr>
            <td><?= $product['id'] ?></td>
            <td><?= htmlspecialchars($product['nama']) ?></td>
            <td><?= number_format($product['harga'], 0, ',', '.') ?></td>
            <td><?= $product['stok'] ?></td>
            <td><img src="<?= htmlspecialchars($product['gambar']) ?>" alt="Gambar" width="100"></td>
            <td>
              <a href="?edit_id=<?= $product['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
              <a href="?delete_id=<?= $product['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">Hapus</a>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
      </table>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
