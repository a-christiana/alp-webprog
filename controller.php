<?php
include 'koneksi.php';

$action = isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : '');

switch ($action) {
    // 1. TAMBAH MENU BARU
    case 'add_menu':
        $name = mysqli_real_escape_string($conn, $_POST['menu_name']);
        $qty  = intval($_POST['qty']);
        $category = mysqli_real_escape_string($conn, $_POST['category']);
        
        $sql = "INSERT INTO Menu (menu_name, qty, category, status) VALUES ('$name', $qty, '$category', 'Available')";
        mysqli_query($conn, $sql);
        header("Location: dashboard.php");
        break;

    // 2. EDIT/UPDATE MENU
    case 'update_menu':
        $id   = intval($_POST['id']);
        $name = mysqli_real_escape_string($conn, $_POST['menu_name']);
        $qty  = intval($_POST['qty']);
        $category = mysqli_real_escape_string($conn, $_POST['category']);
        $status = mysqli_real_escape_string($conn, $_POST['status']);
        
        $sql = "UPDATE Menu SET menu_name='$name', qty=$qty, category='$category', status='$status' WHERE menu_id=$id";
        mysqli_query($conn, $sql);
        header("Location: dashboard.php");
        break;

    // 3. HAPUS MENU
    case 'delete_menu':
        $id = intval($_GET['id']);
        mysqli_query($conn, "DELETE FROM Menu WHERE menu_id = $id");
        header("Location: dashboard.php");
        break;

    // 4. MEMBUAT TRANSAKSI BARU (DARI STOREFRONT)
    case 'create_transaction':
    $menu_id = intval($_POST['menu_id']);
    $qty = intval($_POST['qty']);

    $check_menu = mysqli_query($conn, "SELECT qty FROM menu WHERE menu_id = $menu_id");
    $menu = mysqli_fetch_assoc($check_menu);

    if ($menu && $menu['qty'] >= $qty) {
        mysqli_query($conn, "UPDATE menu SET qty = qty - $qty WHERE menu_id = $menu_id");

        mysqli_query($conn, "INSERT INTO `transaction` (order_status) VALUES ('Incoming')");
        $transaction_id = mysqli_insert_id($conn);

        mysqli_query($conn, "INSERT INTO transaction_detail 
        (transaction_id, menu_id, price, qty) 
        VALUES ($transaction_id, $menu_id, 15000, $qty)");

        echo "<script>
alert('Pesanan berhasil dibuat! Nomor antrean kamu: #TRX-$transaction_id');
window.location.href='receipt.php?id=$transaction_id';
</script>";
    } else {
        echo "<script>
            alert('Maaf, stok tidak cukup!');
            window.location.href='index.php';
        </script>";
    }
    break;

    // 5. UPDATE STATUS PESANAN 
    case 'update_status':
        $trx_id = intval($_GET['id']);
        $next_status = mysqli_real_escape_string($conn, $_GET['status']);
        
        $sql = "UPDATE `Transaction` SET order_status = '$next_status' WHERE transaction_id = $trx_id";
        mysqli_query($conn, $sql);
        header("Location: dashboard.php");
        break;

    default:
        header("Location: index.php");
        break;
}
?>