<?php 
include 'koneksi.php'; 
session_start();

// Urutkan menu yang Available & Qty > 0 di atas, sisanya di bawah
$query = "SELECT * FROM Menu ORDER BY CASE WHEN status = 'Available' AND qty > 0 THEN 1 ELSE 2 END ASC, menu_name ASC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LUCIOLE - Online Ice Cream & Snacks</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans min-h-screen">

    <nav class="bg-white sticky top-0 shadow-sm px-8 py-6 flex items-center justify-between z-50 border-b border-gray-100">
        <div class="text-2xl font-bold tracking-widest text-gray-800">LUCIOLE</div>
        <div class="flex items-center space-x-6">

    <?php if(isset($_SESSION['last_transaction_id'])) { ?>
        <a href="receipt.php?id=<?= $_SESSION['last_transaction_id']; ?>" 
           class="text-sm font-semibold text-gray-600 hover:text-black transition">
            Last Receipt
        </a>
    <?php } ?>

    <a href="dashboard.php" class="text-sm font-semibold text-gray-600 hover:text-black transition">
        Admin Panel &rarr;
    </a>
</div>
    </nav>

    <section class="p-8 max-w-7xl mx-auto">
        <div class="flex gap-x-4 mb-10 overflow-x-auto pb-2 scrollbar-none">
            <button onclick="filterCategory('ALL')" class="category-btn px-10 py-2.5 rounded-full bg-[#FBE49D] text-black font-semibold shadow-sm text-sm transition-all duration-200">ALL</button>
            <button onclick="filterCategory('Ice Cream')" class="category-btn px-10 py-2.5 rounded-full bg-white border text-gray-600 font-semibold text-sm hover:bg-gray-50 transition-all duration-200">Ice Cream</button>
            <button onclick="filterCategory('Drinks')" class="category-btn px-10 py-2.5 rounded-full bg-white border text-gray-600 font-semibold text-sm hover:bg-gray-50 transition-all duration-200">Drinks</button>
            <button onclick="filterCategory('Snacks')" class="category-btn px-10 py-2.5 rounded-full bg-white border text-gray-600 font-semibold text-sm hover:bg-gray-50 transition-all duration-200">Snacks</button>
        </div>

         <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php if(mysqli_num_rows($result) > 0): ?>
                <?php while($row = mysqli_fetch_assoc($result)): 
                    $is_available = ($row['status'] == 'Available' && $row['qty'] > 0);
                    // Kita asumsikan harga flat Rp 15000 dulu sesuai UI kamu, atau bisa diganti $row['price'] jika kolomnya sudah ada
                    $menu_price = $row['price']; 
                ?>
                    
                    <div class="menu-item bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-md transition duration-300 flex flex-col justify-between border border-gray-100 p-5 <?= !$is_available ? 'opacity-50 bg-gray-50' : ''; ?>" 
                         data-category="<?= $row['category']; ?>">
                        
                        <div class="w-full h-44 rounded-2xl overflow-hidden mb-4">

    <?php if(!empty($row['image'])): ?>

        <img src="uploads/<?= htmlspecialchars($row['image']); ?>"
             alt="<?= htmlspecialchars($row['menu_name']); ?>"
             class="w-full h-full object-cover">

    <?php else: ?>

        <div class="w-full h-full flex items-center justify-center font-bold text-3xl <?= $is_available ? 'bg-amber-50 text-amber-500' : 'bg-gray-200 text-gray-400 grayscale'; ?>">
            <?php
                if($row['category'] == 'Drinks') echo '🍹';
                elseif($row['category'] == 'Snacks') echo '🍿';
                else echo '🍦';
            ?>
        </div>

    <?php endif; ?>

</div>
                        
                        <div class="mb-4">
                            <h3 class="text-lg font-bold <?= $is_available ? 'text-gray-800' : 'text-gray-400'; ?> mb-1">
                                <?= htmlspecialchars($row['menu_name']); ?>
                            </h3>
                            
                            <div class="flex gap-2 items-center">
                                <span class="text-[10px] uppercase tracking-wider bg-orange-50 text-orange-600 font-bold px-2 py-1 rounded-md">
                                    <?= $row['category']; ?>
                                </span>
                                <?php if(!$is_available): ?>
                                    <span class="text-[10px] uppercase tracking-wider bg-red-100 text-red-600 font-bold px-2 py-1 rounded-md">
                                        🚫 UNAVAILABLE
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-between mt-auto pt-2 border-t border-gray-50">
                            <span class="text-lg font-extrabold <?= $is_available ? 'text-gray-900' : 'text-gray-400'; ?>">Rp <?= number_format($menu_price, 0, ',', '.'); ?></span>
                            
                            <?php if($is_available): ?>
                                <button type="button" 
                                        onclick="openModal('<?= $row['menu_id']; ?>', '<?= addslashes($row['menu_name']); ?>', <?= $menu_price; ?>, <?= $row['qty']; ?>)"
                                        class="px-5 py-2 bg-[#FBE49D] hover:bg-yellow-400 text-black font-bold text-xs rounded-full transition transform active:scale-95">
                                    ORDER NOW
                                </button>
                            <?php else: ?>
                                <button type="button" class="px-5 py-2 bg-gray-300 text-gray-500 font-bold text-xs rounded-full cursor-not-allowed" disabled>
                                    OUT OF STOCK
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-span-full text-center py-16 bg-white rounded-2xl border border-dashed">
                    <p class="text-gray-400 text-sm">Belum ada menu di database.</p>
                </div>
            <?php endif; ?>
         </div>
    </section>

    <div id="orderModal" class="fixed inset-0 bg-black/40 backdrop-blur-sm z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
        <div class="bg-white w-full max-w-md rounded-3xl p-6 shadow-2xl transform scale-95 transition-transform duration-300 mx-4">
            
            <div class="flex justify-between items-center mb-6">
                <div>
                    <span class="text-xs font-bold text-orange-500 uppercase tracking-wider">Customize Order</span>
                    <h2 id="modalMenuName" class="text-xl font-black text-gray-800">Nama Menu</h2>
                </div>
                <button onclick="closeModal()" class="text-gray-400 hover:text-black text-xl font-bold bg-gray-100 p-2 rounded-full w-9 h-9 flex items-center justify-center transition">&times;</button>
            </div>

            <form action="controller.php" method="POST" class="space-y-4">
                <input type="hidden" name="action" value="create_transaction">
                <input type="hidden" name="menu_id" id="modalMenuId">

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Nama Penerima</label>
                    <input type="text" name="customer_name" required placeholder="Masukkan nama kamu" 
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-yellow-300 font-medium text-sm transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Jenis Pesanan</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer border rounded-2xl p-3 flex items-center justify-center gap-2 font-semibold text-sm bg-gray-50 border-gray-100 hover:bg-gray-100 transition [&:has(input:checked)]:bg-amber-50 [&$:has(input:checked)]:border-amber-400">
                            <input type="radio" name="order_type" value="Dine In" checked class="accent-amber-500">
                            🍽️ Dine In
                        </label>
                        <label class="cursor-pointer border rounded-2xl p-3 flex items-center justify-center gap-2 font-semibold text-sm bg-gray-50 border-gray-100 hover:bg-gray-100 transition [&:has(input:checked)]:bg-amber-50 [&:has(input:checked)]:border-amber-400">
                            <input type="radio" name="order_type" value="Take Away" class="accent-amber-500">
                            🛍️ Take Away
                        </label>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1">Jumlah Pesanan</label>
                    <p class="text-[11px] text-gray-400 mb-2">Sisa stok tersedia: <span id="modalStockLabel" class="font-bold">0</span> pcs</p>
                    <div class="flex items-center gap-4 bg-gray-50 border border-gray-100 p-2 rounded-2xl w-max">
                        <button type="button" onclick="decrementQty()" class="w-8 h-8 rounded-xl bg-white shadow-sm flex items-center justify-center font-bold text-gray-600 hover:bg-gray-100 transition active:scale-90">-</button>
                        <input type="number" name="qty" id="modalQtyInput" value="1" min="1" readonly class="w-12 text-center bg-transparent font-extrabold text-gray-800 focus:outline-none">
                        <button type="button" onclick="incrementQty()" class="w-8 h-8 rounded-xl bg-white shadow-sm flex items-center justify-center font-bold text-gray-600 hover:bg-gray-100 transition active:scale-90">+</button>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-2">Catatan (Optional)</label>
                    <textarea name="notes" rows="2" placeholder="Contoh: Sendoknya double ya, jangan terlalu manis..." 
                              class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus:outline-none focus:ring-2 focus:ring-yellow-300 font-medium text-sm resize-none transition"></textarea>
                </div>

                <div class="border-t border-gray-100 pt-4 mt-6 flex items-center justify-between">
                    <div>
                        <span class="block text-[10px] text-gray-400 font-bold uppercase tracking-wider">Total Pembayaran</span>
                        <span class="text-xl font-black text-gray-900">Rp <span id="modalTotalPrice">0</span></span>
                    </div>
                    <button type="submit" class="px-8 py-3 bg-[#FBE49D] hover:bg-yellow-400 text-black font-black text-sm rounded-2xl transition transform active:scale-95 shadow-md shadow-yellow-100">
                        KONFIRMASI ORDER
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    // Global variables untuk menyimpan data item aktif yang sedang diklik
    let currentPrice = 0;
    let maxStock = 0;

    function openModal(id, name, price, stock) {
        currentPrice = price;
        maxStock = stock;

        // Set nilai ke dalam elemen modal
        document.getElementById('modalMenuId').value = id;
        document.getElementById('modalMenuName').innerText = name;
        document.getElementById('modalStockLabel').innerText = stock;
        document.getElementById('modalQtyInput').value = 1;
        
        // Hitung total harga awal (1 x harga item)
        updateTotalPrice();

        // Tampilkan modal dengan efek transisi smooth
        const modal = document.getElementById('orderModal');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modal.querySelector('div').classList.remove('scale-95');
        }, 10);
    }

    function closeModal() {
        const modal = document.getElementById('orderModal');
        modal.classList.add('opacity-0');
        modal.querySelector('div').classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    function incrementQty() {
        const input = document.getElementById('modalQtyInput');
        let val = parseInt(input.value);
        if (val < maxStock) {
            input.value = val + 1;
            updateTotalPrice();
        }
    }

    function decrementQty() {
        const input = document.getElementById('modalQtyInput');
        let val = parseInt(input.value);
        if (val > 1) {
            input.value = val - 1;
            updateTotalPrice();
        }
    }

    function updateTotalPrice() {
        const qty = parseInt(document.getElementById('modalQtyInput').value);
        const total = qty * currentPrice;
        // Format angka ke format ribuan rupiah Indonesia
        document.getElementById('modalTotalPrice').innerText = total.toLocaleString('id-ID');
    }

    // Fungsi filter kategori bawaan kamu yang sudah dibersihkan
    function filterCategory(selectedCategory) {
        const items = document.querySelectorAll('.menu-item');
        items.forEach(item => {
            const itemCategory = item.getAttribute('data-category');
            if (selectedCategory === 'ALL' || itemCategory === selectedCategory) {
                item.style.display = 'flex'; 
            } else {
                item.style.display = 'none'; 
            }
        });

        const buttons = document.querySelectorAll('.category-btn');
        buttons.forEach(btn => {
            btn.classList.remove('bg-[#FBE49D]', 'text-black', 'shadow-sm');
            btn.classList.add('bg-white', 'border', 'text-gray-600');
        });

        const clickedButton = event.currentTarget;
        clickedButton.classList.remove('bg-white', 'border', 'text-gray-600');
        clickedButton.classList.add('bg-[#FBE49D]', 'text-black', 'shadow-sm');
    }
    </script>

    <?php if (isset($_GET['success']) && isset($_GET['id'])) { ?>
<div class="fixed inset-0 bg-black/40 flex items-center justify-center z-50">
    <div class="bg-white w-[380px] p-6 rounded-3xl shadow-xl text-center">
        <p class="text-sm text-gray-400">Pesanan Berhasil</p>

        <h1 class="text-4xl font-black text-yellow-500 my-3">
            #TRX-<?= $_GET['id']; ?>
        </h1>

        <p class="text-gray-600 text-sm mb-6">
            Simpan nomor antrean ini untuk melihat status pesanan kamu.
        </p>

        <a href="index.php" 
           class="block w-full bg-yellow-400 py-3 rounded-2xl font-bold">
            Oke
        </a>
    </div>
</div>
<?php } ?>
</body>
</html>