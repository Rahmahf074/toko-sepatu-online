<?php
session_start();
include '../config/koneksi.php';

// Jika tidak ada ID, redirect ke list_user.php
if (!isset($_GET['id'])) {
    header('Location: list_user.php');
    exit();
}

$id = $_GET['id'];

// Ambil data user berdasarkan ID
$query = "SELECT * FROM user WHERE id = $id";
$result = mysqli_query($koneksi, $query);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    echo "<script>alert('User tidak ditemukan'); window.location='list_user.php';</script>";
    exit();
}

if (isset($_POST['update'])) {
    $nama_depan = $_POST['nama_depan'];
    $nama_belakang = $_POST['nama_belakang'];
    $email = $_POST['email'];
    $no_hp = $_POST['no_hp'];

    $update_query = "UPDATE user SET 
                     nama_depan = '$nama_depan', 
                     nama_belakang = '$nama_belakang', 
                     email = '$email', 
                     no_hp = '$no_hp' 
                     WHERE id = $id";

    if (mysqli_query($koneksi, $update_query)) {
        echo "<script>alert('User berhasil diperbarui'); window.location='list_user.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui user');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit User</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/admin.css">
</head>
<body>

  <header class="bg-dark text-white text-center py-3">
    <img src="../gambar/Logo.svg" alt="Logo" class="img-fluid" style="max-height: 50px;">
  </header>

  <div class="container-fluid">
    <div class="row">
      <div class="col-md-2 sidebar">
        <h6 class="text-center mb-3">Dashboard Admin</h6>
        <div class="text-center mb-4">
                <div style="font-size: 30px;">👤</div>
                <div class="text-muted">
                    <?php echo isset($_SESSION['user']['username']) ? htmlspecialchars($_SESSION['user']['username']) : 'admin'; ?>
                </div>
            </div>
        <a href="dashboard.php" class="d-block mb-2">Kelola Produk</a>
        <a href="pesanan.php" class="d-block mb-2">Kelola Transaksi</a>
        <a href="list_user.php" class="d-block mb-2">Kelola User</a>
        <a href="../sepatu_online/logout.php" class="d-block text-danger">Keluar</a>
      </div>

      <div class="col-md-10 content-wrapper p-4">
        <h2 class="mb-4">Edit User</h2>

        <form action="edit_user.php?id=<?= $id ?>" method="POST">
          <div class="mb-3">
            <label for="nama_depan" class="form-label">Nama Depan</label>
            <input type="text" class="form-control" id="nama_depan" name="nama_depan" value="<?= htmlspecialchars($user['nama_depan']) ?>" required>
          </div>
          <div class="mb-3">
            <label for="nama_belakang" class="form-label">Nama Belakang</label>
            <input type="text" class="form-control" id="nama_belakang" name="nama_belakang" value="<?= htmlspecialchars($user['nama_belakang']) ?>" required>
          </div>
          <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
          </div>
          <div class="mb-3">
            <label for="no_hp" class="form-label">No HP</label>
            <input type="text" class="form-control" id="no_hp" name="no_hp" value="<?= htmlspecialchars($user['no_hp']) ?>" required>
          </div>
          <button type="submit" name="update" class="btn btn-primary">Update</button>
        </form>
      </div>
    </div>
  </div>
</body>
</html>
