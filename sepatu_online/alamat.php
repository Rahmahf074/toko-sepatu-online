<?php
session_start();
include '../config/koneksi.php';

if (!isset($_SESSION['user'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user']['id'];

// Hapus alamat jika ada permintaan
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $query = "DELETE FROM alamat_pengiriman WHERE id='$id' AND user_id='$user_id'";
    mysqli_query($koneksi, $query);
    echo "<script>alert('Alamat berhasil dihapus!'); window.location='alamat.php';</script>";
    exit();
}

// Tambah atau update alamat
if (isset($_POST['simpan'])) {
    $id = $_POST['id'] ?? '';
    $nama_penerima = $_POST['nama_penerima'];
    $alamat_lengkap = $_POST['alamat_lengkap'];
    $kota = $_POST['kota'];
    $provinsi = $_POST['provinsi'];
    $kode_pos = $_POST['kode_pos'];
    $no_hp = $_POST['no_hp'];

    if ($id) {
        // update
        $query = "UPDATE alamat_pengiriman SET nama_penerima='$nama_penerima', alamat_lengkap='$alamat_lengkap', kota='$kota', provinsi='$provinsi', kode_pos='$kode_pos', no_hp='$no_hp' WHERE id='$id' AND user_id='$user_id'";
    } else {
        // insert
        $query = "INSERT INTO alamat_pengiriman (user_id, nama_penerima, alamat_lengkap, kota, provinsi, kode_pos, no_hp) VALUES ('$user_id', '$nama_penerima', '$alamat_lengkap', '$kota', '$provinsi', '$kode_pos', '$no_hp')";
    }
    mysqli_query($koneksi, $query);
    echo "<script>alert('Alamat berhasil disimpan!'); window.location='alamat.php';</script>";
    exit();
}

// Ambil data jika ingin edit
$editData = null;
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $result = mysqli_query($koneksi, "SELECT * FROM alamat_pengiriman WHERE id='$id' AND user_id='$user_id'");
    $editData = mysqli_fetch_assoc($result);
}

// Ambil semua alamat
$result = mysqli_query($koneksi, "SELECT * FROM alamat_pengiriman WHERE user_id = '$user_id' ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/user.css">
    <title>Alamat Pengiriman</title>
</head>
<body>

<?php include '../sepatu_online/header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <?php include '../sepatu_online/sidebar.php'; ?>
        <div class="col-md-9">
            <h3 class="mb-3">Alamat Pengiriman</h3>

            <?php if (mysqli_num_rows($result) == 0): ?>
                <div class="alert alert-info mb-2" style="margin-top: 150px;">
                    Tidak ada alamat yang tersimpan.
                </div>
                <!-- Tombol untuk menambah alamat -->
                <div class="mb-4">
                    <button class="btn btn-primary justify-content-right" data-bs-toggle="modal" data-bs-target="#modalAlamat">
                        + Tambah Alamat
                    </button>
                </div>
            <?php endif; ?>

            <!-- Modal Form Tambah atau Edit Alamat -->
            <div class="modal fade" id="modalAlamat" tabindex="-1" aria-labelledby="modalAlamatLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalAlamatLabel"><?= $editData ? 'Edit Alamat' : 'Tambah Alamat' ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST">
                                <input type="hidden" name="id" value="<?= $editData['id'] ?? '' ?>">
                                <div class="mb-3">
                                    <label for="nama_penerima" class="form-label">Nama Penerima</label>
                                    <input type="text" class="form-control" id="nama_penerima" name="nama_penerima" value="<?= $editData['nama_penerima'] ?? '' ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="no_hp" class="form-label">No HP</label>
                                    <input type="text" class="form-control" id="no_hp" name="no_hp" value="<?= $editData['no_hp'] ?? '' ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="alamat_lengkap" class="form-label">Alamat Lengkap</label>
                                    <textarea class="form-control" id="alamat_lengkap" name="alamat_lengkap" required><?= $editData['alamat_lengkap'] ?? '' ?></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="kota" class="form-label">Kota</label>
                                    <input type="text" class="form-control" id="kota" name="kota" value="<?= $editData['kota'] ?? '' ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="provinsi" class="form-label">Provinsi</label>
                                    <input type="text" class="form-control" id="provinsi" name="provinsi" value="<?= $editData['provinsi'] ?? '' ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label for="kode_pos" class="form-label">Kode Pos</label>
                                    <input type="text" class="form-control" id="kode_pos" name="kode_pos" value="<?= $editData['kode_pos'] ?? '' ?>" required>
                                </div>
                                <button type="submit" name="simpan" class="btn btn-success"><?= $editData ? 'Perbarui Alamat' : 'Simpan Alamat' ?></button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Menampilkan alamat yang tersimpan -->
            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($alamat = mysqli_fetch_assoc($result)): ?>
                    <div class="card mb-3" style="margin-top: 150px;">
                        <div class="card-body">
                            <h5><?= htmlspecialchars($alamat['nama_penerima']) ?> - <?= htmlspecialchars($alamat['no_hp']) ?></h5>
                            <p>
                                <?= htmlspecialchars($alamat['alamat_lengkap']) ?>,<br>
                                <?= htmlspecialchars($alamat['kota']) ?>, <?= htmlspecialchars($alamat['provinsi']) ?> <?= htmlspecialchars($alamat['kode_pos']) ?>
                            </p>
                            <a href="alamat.php?edit=<?= $alamat['id'] ?>" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalAlamat">Edit</a>
                            <a href="alamat.php?hapus=<?= $alamat['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus alamat ini?')">Hapus</a>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
