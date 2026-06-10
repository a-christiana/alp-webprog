<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
    header("Location: index.php");
    exit;
}

$total = 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Checkout Cart</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex justify-center items-center">

<div class="bg-white p-6 rounded-3xl shadow-lg w-[500px]">

    <h1 class="text-2xl font-black mb-4">
        Checkout Cart
    </h1>

    <?php foreach($_SESSION['cart'] as $menu_id => $qty):

        $menu = mysqli_fetch_assoc(
            mysqli_query($conn,
            "SELECT * FROM menu WHERE menu_id=$menu_id")
        );

        $subtotal = $menu['price'] * $qty;
        $total += $subtotal;
    ?>

    <div class="flex justify-between py-2 border-b">
        <span>
            <?= $menu['menu_name']; ?> x <?= $qty; ?>
        </span>

        <span>
            Rp <?= number_format($subtotal,0,',','.'); ?>
        </span>
    </div>

    <?php endforeach; ?>

    <div class="flex justify-between font-black text-xl mt-4">
        <span>Total</span>
        <span>Rp <?= number_format($total,0,',','.'); ?></span>
    </div>

    <div class="mt-6 flex gap-3">

        <a href="index.php"
           class="flex-1 text-center bg-gray-200 py-3 rounded-xl font-bold">
            Kembali
        </a>

        <a href="controller.php?action=checkout"
           class="flex-1 text-center bg-yellow-400 py-3 rounded-xl font-bold">
            Confirm Checkout
        </a>

    </div>

</div>

</body>
</html>