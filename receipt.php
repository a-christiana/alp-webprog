<?php
include 'koneksi.php';

$id = intval($_GET['id']);

$query = mysqli_query($conn, "
    SELECT 
        t.transaction_id,
        t.order_status,
        t.created_at,
        m.menu_name,
        td.qty,
        td.price
    FROM `transaction` t
    JOIN transaction_detail td ON t.transaction_id = td.transaction_id
    JOIN menu m ON td.menu_id = m.menu_id
    WHERE t.transaction_id = $id
");

$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "Struk tidak ditemukan.";
    exit;
}

$total = $data['qty'] * $data['price'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Struk Elektronik</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-yellow-50 min-h-screen flex items-center justify-center">

    <div class="bg-white w-[380px] p-6 rounded-3xl shadow-xl">
        <h1 class="text-2xl font-black text-center mb-2">LUCIOLE</h1>
        <p class="text-center text-sm text-gray-400 mb-6">Electronic Receipt</p>

        <div class="border-t border-b py-4 space-y-2 text-sm">
           <p><b>Order ID:</b> #TRX-<?= $data['transaction_id']; ?></p>
<p><b>Status:</b> <?= $data['order_status']; ?></p>
<p><b>Date:</b> <?= $data['created_at']; ?></p>
        </div>

        <div class="py-4">
            <div class="flex justify-between text-sm">
                <span><?= $data['menu_name']; ?> x <?= $data['qty']; ?></span>
                <span>Rp <?= number_format($total, 0, ',', '.'); ?></span>
            </div>
        </div>

        <div class="border-t pt-4 flex justify-between font-black text-lg">
            <span>Total</span>
            <span>Rp <?= number_format($total, 0, ',', '.'); ?></span>
        </div>

        <div class="mt-6 flex gap-2">

            <a href="index.php" class="w-full text-center bg-yellow-300 py-3 rounded-2xl font-bold">
                Back
            </a>
        </div>
    </div>

</body>
</html>