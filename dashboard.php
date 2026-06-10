<?php
session_start();

if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

include 'koneksi.php';

// 1. Ambil data Menu Gudang
$menus = mysqli_query($conn, "SELECT * FROM Menu");

// 2. Ambil data Transaksi/Pesanan (Menggunakan kolom order_status yang baru)
$orders_query = "SELECT t.transaction_id, t.order_status, m.menu_name, td.qty 
                 FROM `Transaction` t
                 JOIN Transaction_Detail td ON t.transaction_id = td.transaction_id
                 JOIN Menu m ON td.menu_id = m.menu_id
                 ORDER BY t.transaction_id DESC";
$orders = mysqli_query($conn, $orders_query);

// 3. Hitung jumlah masing-masing status pesanan untuk kotak summary atas
$count_incoming  = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `Transaction` WHERE order_status='Incoming'"));
$count_progress  = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `Transaction` WHERE order_status='In Progress'"));
$count_pickup    = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `Transaction` WHERE order_status='Ready to Pick Up'"));
$count_completed = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `Transaction` WHERE order_status='Completed'"));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Luciole</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans min-h-screen flex flex-col">

    <header class="bg-[#FBE49D] px-4 sm:px-8 py-4 flex flex-col sm:flex-row gap-4 items-center justify-between shadow-sm">
        <div class="flex flex-col sm:flex-row items-center gap-3 sm:space-x-12 text-center sm:text-left">
            <h1 class="text-xl font-bold tracking-widest text-gray-800">LUCIOLE ADMIN</h1>
            <nav class="space-x-6 text-sm font-medium text-gray-700">
                <a href="index.php" class="hover:text-black">Storefront</a>
                <a href="#" class="hover:text-black border-b-2 border-black pb-1">Dashboard</a>
            </nav>
        </div>
    
        <a href="logout.php" 
       style="background-color: #dc3545; color: white; padding: 6px 12px; text-decoration: none; border-radius: 4px; font-family: sans-serif; font-size: 14px; font-weight: bold;" 
       onclick="return confirm('Confirm Logout?');">
       Logout
    </a>
    </header>

    <main class="flex-1 p-8 space-y-8">
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-blue-50 p-5 rounded-xl border border-blue-200 shadow-sm flex flex-col justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-blue-600">📥 Pesanan Masuk</p>
                <p class="text-3xl font-black text-blue-900 mt-2"><?= $count_incoming; ?> <span class="text-sm font-normal">Antrean</span></p>
            </div>
            <div class="bg-amber-50 p-5 rounded-xl border border-amber-200 shadow-sm flex flex-col justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-amber-600">⏳ In Progress</p>
                <p class="text-3xl font-black text-amber-900 mt-2"><?= $count_progress; ?> <span class="text-sm font-normal">Dibuat</span></p>
            </div>
            <div class="bg-purple-50 p-5 rounded-xl border border-purple-200 shadow-sm flex flex-col justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-purple-600">🛍️ Ready to Pick Up</p>
                <p class="text-3xl font-black text-purple-900 mt-2"><?= $count_pickup; ?> <span class="text-sm font-normal">Siap</span></p>
            </div>
            <div class="bg-green-50 p-5 rounded-xl border border-green-200 shadow-sm flex flex-col justify-between">
                <p class="text-xs font-bold uppercase tracking-wider text-green-600">✅ Completed</p>
                <p class="text-3xl font-black text-green-900 mt-2"><?= $count_completed; ?> <span class="text-sm font-normal">Selesai</span></p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
            <h2 class="text-lg font-extrabold text-gray-800 mb-4">📋 Antrean & Status Orderan Masuk</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 text-xs font-bold uppercase border-b border-gray-200">
                            <th class="p-3">ID Transaksi</th>
                            <th class="p-3">Nama Menu</th>
                            <th class="p-3">Jumlah</th>
                            <th class="p-3">Status Pesanan</th>
                            <th class="p-3 text-center">Aksi Proses Pesanan</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        <?php if(mysqli_num_rows($orders) > 0): ?>
                            <?php while($o = mysqli_fetch_assoc($orders)): ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3 font-mono font-bold text-gray-600">#TRX-<?= $o['transaction_id']; ?></td>
                                <td class="p-3 font-semibold text-gray-800"><?= htmlspecialchars($o['menu_name']); ?></td>
                                <td class="p-3 text-gray-600"><?= $o['qty']; ?>x</td>
                                <td class="p-3">
                                    <?php 
                                        if($o['order_status'] == 'Incoming') echo '<span class="bg-blue-100 text-blue-800 px-2.5 py-1 rounded-full text-xs font-bold">Incoming</span>';
                                        elseif($o['order_status'] == 'In Progress') echo '<span class="bg-amber-100 text-amber-800 px-2.5 py-1 rounded-full text-xs font-bold">In Progress</span>';
                                        elseif($o['order_status'] == 'Ready to Pick Up') echo '<span class="bg-purple-100 text-purple-800 px-2.5 py-1 rounded-full text-xs font-bold">Ready to Pick Up</span>';
                                        else echo '<span class="bg-green-100 text-green-800 px-2.5 py-1 rounded-full text-xs font-bold">Completed</span>';
                                    ?>
                                </td>
                                <td class="p-3 text-center">
                                    <?php if($o['order_status'] == 'Incoming'): ?>
                                        <a href="controller.php?action=update_status&id=<?= $o['transaction_id']; ?>&status=In Progress" class="bg-amber-400 hover:bg-amber-500 text-xs font-bold px-4 py-1.5 rounded transition shadow-sm">Proses Pembuatan &rarr;</a>
                                    <?php elseif($o['order_status'] == 'In Progress'): ?>
                                        <a href="controller.php?action=update_status&id=<?= $o['transaction_id']; ?>&status=Ready to Pick Up" class="bg-purple-500 hover:bg-purple-600 text-white text-xs font-bold px-4 py-1.5 rounded transition shadow-sm">Set Siap Diambil &rarr;</a>
                                    <?php elseif($o['order_status'] == 'Ready to Pick Up'): ?>
                                        <a href="controller.php?action=update_status&id=<?= $o['transaction_id']; ?>&status=Completed" class="bg-green-600 hover:bg-green-700 text-white text-xs font-bold px-4 py-1.5 rounded transition shadow-sm">Selesaikan Pesanan ✓</a>
                                    <?php else: ?>
                                        <span class="text-gray-400 text-xs font-medium italic">Pesanan Selesai Diarsip</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="p-6 text-center text-gray-400 italic">Belum ada pesanan masuk dari storefront pembeli.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                <h2 class="text-lg font-extrabold text-gray-800">🍦 Master Kelola Menu Toko</h2>
                
                <form action="controller.php" method="POST" enctype="multipart/form-data" class="flex flex-wrap gap-2 items-center bg-gray-50 p-3 rounded-lg border w-full md:w-auto">
                    <input type="hidden" name="action" value="add_menu">
                    <input type="text" name="menu_name" placeholder="Nama Menu Baru" class="px-3 py-1.5 text-sm rounded border bg-white focus:outline-none" required>
                    <input type="number" name="qty" placeholder="Stok" class="w-20 px-3 py-1.5 text-sm rounded border bg-white focus:outline-none" required>
                    <input type="number" name="price" placeholder="Harga (Rp)" class="w-28 px-3 py-1.5 text-sm rounded border bg-white focus:outline-none" required>
                    <input type="file" name="image" accept="image/*">
                    <select name="category" class="px-3 py-1.5 text-sm rounded border bg-white focus:outline-none" required>
                        <option value="Ice Cream">Ice Cream</option>
                        <option value="Drinks">Drinks</option>
                        <option value="Snacks">Snacks</option>
                    </select>
                    <button type="submit" class="bg-yellow-400 hover:bg-yellow-500 px-4 py-1.5 rounded text-sm font-semibold transition">+ Tambah Menu</button>
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-600 text-xs font-bold uppercase border-b border-gray-200">
                            <th class="p-3">No.</th>
                            <th class="p-3">Nama Menu</th>
                            <th class="p-3">Kategori</th>
                            <th class="p-3">Stok</th>
                            <th class="p-3">Status Produk</th>
                            <th class="p-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        <?php if(mysqli_num_rows($menus) > 0): ?>
                            
                            <?php 
                            $no = 1;
                            ?>
                            
                            <?php while($m = mysqli_fetch_assoc($menus)): ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3 text-gray-500 font-semibold"><?= $no++; ?>.</td>
                                <td class="p-3 font-semibold text-gray-800"><?= htmlspecialchars($m['menu_name']); ?></td>
                                <td class="p-3"><span class="bg-orange-50 text-orange-700 font-medium px-2 py-0.5 rounded text-xs"><?= $m['category']; ?></span></td>
                                <td class="p-3 text-gray-700"><?= $m['qty']; ?> pcs</td>
                                <td class="p-3">
                                    <span class="px-2 py-0.5 rounded text-xs font-bold <?= $m['status']=='Available' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?>">
                                        <?= $m['status']; ?>
                                    </span>
                                </td>
                                <td class="p-3 text-center space-x-4">
                                    <a href="update.php?id=<?= $m['menu_id']; ?>" class="text-blue-500 hover:underline font-medium">Edit</a>
                                    <a href="controller.php?action=delete_menu&id=<?= $m['menu_id']; ?>" onclick="return confirm('Hapus menu ini?')" class="text-red-500 hover:underline font-medium">Delete</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="p-4 text-center text-gray-400 italic">Belum ada data menu di gudang.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>