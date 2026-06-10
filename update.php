<?php
include 'koneksi.php';

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit;
}

$id = intval($_GET['id']);

$query = mysqli_query($conn, "SELECT * FROM Menu WHERE menu_id = $id");
$menu = mysqli_fetch_assoc($query);

if (!$menu) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu - Luciole Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans min-h-screen flex flex-col items-center justify-center p-6">

    <div class="bg-white rounded-2xl shadow-md border border-gray-200 w-full max-w-md overflow-hidden">
        
        <div class="bg-[#FBE49D] px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h2 class="text-md font-black text-gray-800 uppercase tracking-wider">✏️ Edit Varian Menu</h2>
            <a href="dashboard.php" class="text-xs bg-white hover:bg-gray-50 text-gray-700 font-bold px-3 py-1.5 rounded-lg border transition shadow-sm">
                &larr; Batal
            </a>
        </div>

        <form action="controller.php" method="POST" class="p-6 space-y-5">
            <input type="hidden" name="action" value="update_menu">
            <input type="hidden" name="id" value="<?= $menu['menu_id']; ?>">

            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-gray-600 uppercase tracking-wide">Nama Varian Menu</label>
                <input type="text" name="menu_name" value="<?= htmlspecialchars($menu['menu_name']); ?>" 
                       class="w-full px-3 py-2 text-sm rounded-lg border bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-yellow-300 transition" required>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-gray-600 uppercase tracking-wide">Sisa Stok (pcs)</label>
                    <input type="number" name="qty" value="<?= $menu['qty']; ?>" 
                           class="w-full px-3 py-2 text-sm rounded-lg border bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-yellow-300 transition" required>
                </div>
                
                <div class="flex flex-col gap-1.5">
                    <label class="text-xs font-bold text-gray-600 uppercase tracking-wide">Kategori</label>
                    <select name="category" class="w-full px-3 py-2 text-sm rounded-lg border bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-yellow-300 transition" required>
                        <option value="Ice Cream" <?= $menu['category'] == 'Ice Cream' ? 'selected' : ''; ?>>Ice Cream</option>
                        <option value="Drinks" <?= $menu['category'] == 'Drinks' ? 'selected' : ''; ?>>Drinks</option>
                        <option value="Snacks" <?= $menu['category'] == 'Snacks' ? 'selected' : ''; ?>>Snacks</option>
                    </select>
                </div>
            </div>

            <div class="flex flex-col gap-1.5">
                <label class="text-xs font-bold text-gray-600 uppercase tracking-wide">Status Etalase Produk</label>
                <select name="status" class="w-full px-3 py-2 text-sm rounded-lg border bg-gray-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-yellow-300 transition" required>
                    <option value="Available" <?= $menu['status'] == 'Available' ? 'selected' : ''; ?>>🟢 Available (Tersedia)</option>
                    <option value="Sold Out" <?= $menu['status'] == 'Sold Out' ? 'selected' : ''; ?>>🔴 Sold Out (Habis)</option>
                </select>
            </div>

            <div class="pt-2">
                <button type="submit" class="w-full bg-yellow-400 hover:bg-yellow-500 text-gray-800 font-bold text-sm py-2.5 rounded-lg transition shadow-sm tracking-wide">
                    💾 Simpan Perubahan Menu
                </button>
            </div>
        </form>

    </div>

</body>
</html>