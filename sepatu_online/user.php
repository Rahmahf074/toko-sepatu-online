<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['user'])) {
  header("Location: ../login.php");
  exit();
}

$user_id = $_SESSION['user']['id'];
$query = "SELECT * FROM user WHERE id = '$user_id'";
$result = mysqli_query($koneksi, $query);
$user = mysqli_fetch_assoc($result);

if (isset($_POST['update'])) {
  $nama_depan = $_POST['nama_depan'];
  $nama_belakang = $_POST['nama_belakang'];
  $no_hp = $_POST['no_hp'];
  $email = $_POST['email'];
  $tgl_lahir = $_POST['tgl_lahir'];
  $jenis_kelamin = $_POST['jenis_kelamin'];

  $update = "UPDATE user SET 
              nama_depan='$nama_depan', 
              nama_belakang='$nama_belakang', 
              no_hp='$no_hp', 
              email='$email', 
              tgl_lahir='$tgl_lahir', 
              jenis_kelamin='$jenis_kelamin' 
            WHERE id='$user_id'";
  mysqli_query($koneksi, $update);
  $_SESSION['user']['username'] = $nama_depan;
  echo "<script>alert('Profil berhasil diperbarui!'); window.location='user.php';</script>";
  exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="../css/user.css">
  <title>Profil Pengguna</title>
</head>
<body>
<?php include '../sepatu_online/header.php'; ?>

<div class="container-fluid">
  <div class="row">
    <?php include '../sepatu_online/sidebar.php'; ?>
    <div class="col-md-9 form-section">
      <h3 class="mb-4">Informasi Akun</h3>
      <form method="POST" id="form-profil">
        <div class="row mb-3">
          <div class="col">
            <label>Nama Depan</label>
            <input type="text" name="nama_depan" class="form-control" value="<?= htmlspecialchars($user['nama_depan']) ?>" required>
          </div>
          <div class="col">
            <label>Nama Belakang</label>
            <input type="text" name="nama_belakang" class="form-control" value="<?= htmlspecialchars($user['nama_belakang']) ?>" readonly>
          </div>
        </div>
        <div class="mb-3">
          <label>No. Handphone</label>
          <input type="text" name="no_hp" class="form-control" value="<?= htmlspecialchars($user['no_hp']) ?>" readonly>
        </div>
        <div class="mb-3">
          <label>Email</label>
          <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required readonly>
        </div>
        <div class="mb-3">
          <label>Tanggal Lahir</label>
          <input type="date" name="tgl_lahir" class="form-control" value="<?= htmlspecialchars($user['tgl_lahir']) ?>" readonly>
        </div>
        <div class="mb-3">
          <label>Jenis Kelamin</label>
          <select name="jenis_kelamin" class="form-control" readonly>
            <option value="">Pilih</option>
            <option value="Laki-laki" <?= ($user['jenis_kelamin'] == 'Laki-laki') ? 'selected' : '' ?>>Laki-laki</option>
            <option value="Perempuan" <?= ($user['jenis_kelamin'] == 'Perempuan') ? 'selected' : '' ?>>Perempuan</option>
          </select>
        </div>
        <button type="button" id="btn-edit" class="btn btn-warning">Edit</button>
        <button type="submit" name="update" id="btn-simpan" class="btn btn-primary d-none">Simpan</button>
      </form>
    </div>
  </div>
</div>

<!-- JavaScript untuk tombol Edit -->
<script>
  document.getElementById('btn-edit').addEventListener('click', function () {
    const form = document.getElementById('form-profil');
    const inputs = form.querySelectorAll('input, select');
    inputs.forEach(input => {
      input.removeAttribute('readonly');
      input.removeAttribute('disabled');
    });

    document.getElementById('btn-edit').classList.add('d-none');
    document.getElementById('btn-simpan').classList.remove('d-none');
  });
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
