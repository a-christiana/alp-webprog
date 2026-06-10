<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
    echo "<script>
        alert('Cart masih kosong!');
        window.location.href='index.php';
    </script>";
    exit;
}

$total = 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Konfirmasi Checkout</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-yellow-50 min-h-screen flex items-center justify-center">

<div class="bg-white w-[420px] p-6 rounded-3xl shadow-xl">
    <h1 class="text-2xl font-black text-center mb-2">Konfirmasi Checkout</h1>
    <p class="text-center text-sm text-gray-400 mb-6">
        Apakah kamu yakin mau checkout pesanan ini?
    </p>

    <div class="space-y-3 border-t border-b py-4">
        <?php foreach ($_SESSION['cart'] as $menu_id => $qty): ?>
            <?php
            $menu_id = intval($menu_id);
            $query = mysqli_query($conn, "SELECT menu_name FROM menu WHERE menu_id = $menu_id");
            $menu = mysqli_fetch_assoc($query);

            $price = 15000;
            $subtotal = $price * $qty;
            $total += $subtotal;
            ?>

            <div class="flex justify-between text-sm">
                <span><?= $menu['menu_name']; ?> x <?= $qty; ?></span>
                <span>Rp <?= number_format($subtotal, 0, ',', '.'); ?></span>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="flex justify-between font-black text-lg mt-4">
        <span>Total</span>
        <span>Rp <?= number_format($total, 0, ',', '.'); ?></span>
    </div>

    <div class="flex gap-3 mt-6">
        <a href="index.php"
           class="w-full text-center bg-gray-200 py-3 rounded-2xl font-bold">
            Batal
        </a>

        <a href="controller.php?action=checkout"
           class="w-full text-center bg-yellow-400 py-3 rounded-2xl font-bold">
            Ya, Checkout
        </a>
    </div>
</div>

</body>
</html>