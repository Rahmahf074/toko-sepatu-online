<?php
session_start();
include '../config/koneksi.php';

// Hapus item dari keranjang
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    unset($_SESSION['cart'][$id]);
    header("Location: keranjang.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keranjang Belanja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/keranjang.css">
</head>
<body>

<div class="container cart-container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Keranjang Belanja</h2>
    </div>

    <?php if (!empty($_SESSION['cart'])): ?>
    <form action="checkout.php" method="POST">
        <div class="card cart-card p-4">
            <a href="index.php" class="btn-close position-absolute top-0 end-0 fw-bold" style="top: 15px; right: 15px;" aria-label="Close"></a>
            <table class="table table-hover align-middle">
                <thead class="table-dark text-center">
                    <tr>
                        <th><input type="checkbox" id="checkAll"></th> <!-- Check All -->
                        <th>Produk</th>
                        <th>Ukuran</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                        <th>Diskon</th>

                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $total = 0;
                    foreach ($_SESSION['cart'] as $id => $item): 
                    $hargaAwal = $item['price']; // Ini harga sudah diskon
                    $diskon = (!empty($item['discount'])) ? $item['discount'] : 0;
                    $sub_total = $hargaAwal * $item['quantity']; 
                    ?>

                    <tr class="text-center">
                        <td><input type="checkbox" name="selected_items[]" value="<?= $id; ?>" class="item-check"></td>
                        <td class="text-start">
                            <div class="d-flex align-items-center gap-3">
                            <img src="<?= htmlspecialchars($item['image']); ?>" class="cart-img" alt="<?= htmlspecialchars($item['name']); ?>">
                            <div><strong><?= $item['name']; ?></strong></div>
                            </div>
                        </td>
                        <td><?= $item['size']; ?></td>
                        <td><?= $item['quantity']; ?></td>
                        <td>
                            <strong>Rp <?= number_format($hargaAwal, 0, ',', '.'); ?></strong>
                        </td>
                        <td>
                            <?= $diskon; ?>%
                        </td>
                        <td>Rp <?= number_format($sub_total, 0, ',', '.'); ?></td>
                        <td>
                            <a href="keranjang.php?hapus=<?= $id; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus item ini?')">🗑️</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Total dan Checkout -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <h5>Total Dipilih: <strong id="totalSelected">Rp 0</strong></h5>
                <button type="submit" class="btn btn-primary btn-lg">Checkout</button>
            </div>
        </div>
    </form>
    <?php else: ?>
        <div class="alert alert-warning text-center">Keranjang kamu kosong 😢</div>
        <div class="text-center">
            <a href="index.php" class="btn btn-success">Belanja Sekarang</a>
        </div>
    <?php endif; ?>
</div>

<script>
// Ceklis semua item
document.getElementById('checkAll').addEventListener('change', function() {
    let checks = document.querySelectorAll('.item-check');
    checks.forEach(check => check.checked = this.checked);
    updateTotal();
});

// Update total saat ceklis berubah
let itemChecks = document.querySelectorAll('.item-check');
itemChecks.forEach(check => {
    check.addEventListener('change', updateTotal);
});

function updateTotal() {
    let selected = document.querySelectorAll('.item-check:checked');
    let total = 0;
    <?php foreach ($_SESSION['cart'] as $id => $item): 
        $sub_total = $item['price'] * $item['quantity']; // Harga sudah diskon, langsung kalikan quantity
    ?>
        if (document.querySelector('input[value="<?= $id; ?>"]')?.checked) {
            total += <?= $sub_total; ?>;
        }
    <?php endforeach; ?>
    document.getElementById('totalSelected').innerText = "Rp " + total.toLocaleString('id-ID');
}
</script>

</body>
</html>
