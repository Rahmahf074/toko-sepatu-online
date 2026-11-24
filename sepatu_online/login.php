<?php
session_start();
include '../config/koneksi.php';

// (Opsional) Tampilkan semua error untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$role  = $_GET['role'] ?? 'user';  // default role
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role     = $_POST['role'];

    // Pilih tabel berdasarkan role
    if ($role === 'admin') {
        $sql = "SELECT * FROM admin WHERE username = '$username' LIMIT 1";
    } else {
        $sql = "SELECT * FROM user WHERE username = '$username' LIMIT 1";
    }

    $result = $koneksi->query($sql);
    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verifikasi password
        if (password_verify($password, $user['password'])) {
            // ***Di sinilah perubahannya***
            if ($role === 'admin') {
                $_SESSION['admin'] = $user;   // simpan ke session admin
            } else {
                $_SESSION['user']  = $user;   // simpan ke session user
                $_SESSION['user_id'] = $user['id'];
            }
            $_SESSION['role'] = $role;

            // Debugging: Periksa session user_id
          var_dump($_SESSION['user_id']);  // Tambahkan untuk memastikan user_id ada
          $_SESSION['role'] = $role;

            // Redirect
            if ($role === 'admin') {
                header("Location: ../admin/dashboard.php");
            } else {
                header("Location: ../sepatu_online/index.php");
            }
            exit();
        } else {
            $error = "Login gagal: password salah.";
        }
    } else {
        $error = "Login gagal: username tidak ditemukan.";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Login - FitLearn</title>
  <link rel="stylesheet" href="../css/loginregis.css">
</head>
<body>
  <div class="login-container">
    <div class="login-box <?= htmlspecialchars($role) ?>">
      <a href="../sepatu_online/index.php" class="close-btn" title="Tutup">×</a>
      <img src="../gambar/Logo.svg" alt="Logo" class="logo">

      <div class="tab-role">
        <a href="?role=user"  class="tab <?= $role==='user'  ? 'active' : '' ?>">User</a>
        <a href="?role=admin" class="tab <?= $role==='admin' ? 'active' : '' ?>">Admin</a>
      </div>

      <h2>Masuk</h2>

      <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
      <?php endif; ?>

      <form method="POST" class="form-login">
        <input type="hidden" name="role" value="<?= htmlspecialchars($role) ?>">
        <input type="text"     name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Masuk</button>
        <?php if ($role === 'user'): ?>
          <p class="daftar-link">Belum punya akun? <a href="register.php">Daftar</a></p>
        <?php endif; ?>
      </form>
    </div>
  </div>
</body>
</html>
