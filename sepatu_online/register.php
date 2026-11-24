<?php
include '../config/koneksi.php';

$role = 'user'; // kunci role agar tidak bisa register sebagai admin
$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $konfirmasi = $_POST['konfirmasi'];

    if ($password !== $konfirmasi) {
        $error = "Konfirmasi password tidak cocok.";
    } else {
        $tabel = 'user';
        $kolomNama = 'nama_lengkap';

        // Cek apakah username sudah digunakan
        $stmt = $koneksi->prepare("SELECT * FROM $tabel WHERE username=?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Username sudah digunakan.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            // Insert data user baru
            $stmt = $koneksi->prepare("INSERT INTO $tabel ($kolomNama, username, password) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $nama, $username, $hash);

            if ($stmt->execute()) {
                $success = "Pendaftaran berhasil! Silakan login.";
            } else {
                $error = "Terjadi kesalahan saat mendaftar.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar - Rivaro Shoes</title>
    <link rel="stylesheet" href="../css/loginregis.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <a href="../sepatu_online/index.php" class="close-btn" title="Tutup">×</a>
            <img src="../gambar/Logo.svg" alt="Logo" class="logo">

            <h2>Daftar</h2>

            <?php if ($error): ?>
                <p class="error"><?= $error ?></p>
            <?php endif; ?>

            <?php if ($success): ?>
                <p class="success"><?= $success ?></p>
                <p class="daftar-link">Silakan <a href="login.php">Login</a></p>
            <?php else: ?>
                <form method="POST" class="form-login">
                    <input type="text" name="nama" placeholder="Nama Lengkap" required>
                    <input type="text" name="username" placeholder="Masukkan Username" required>
                    <input type="password" name="password" placeholder="Masukkan Password" required>
                    <input type="password" name="konfirmasi" placeholder="Konfirmasi Password" required>
                    <button type="submit">Daftar</button>
                    <p class="daftar-link">Sudah punya akun? <a href="login.php">Login</a></p>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
