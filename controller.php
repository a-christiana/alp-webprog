<?php
include 'koneksi.php';

$action = isset($_GET['action']) ? $_GET['action'] : (isset($_POST['action']) ? $_POST['action'] : '');

switch ($action) {
    // 1. TAMBAH MENU BARU
    case 'add_menu':

    $name = mysqli_real_escape_string($conn, $_POST['menu_name']);
    $qty = intval($_POST['qty']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);

    $image_name = null;

    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){

        $image_name =
            time() . "_" . basename($_FILES['image']['name']);

        move_uploaded_file(
            $_FILES['image']['tmp_name'],
            "uploads/" . $image_name
        );
    }

    $sql = "INSERT INTO Menu
            (menu_name, qty, category, status, image)
            VALUES
            ('$name', $qty, '$category', 'Available', '$image_name')";

    mysqli_query($conn, $sql);

    header("Location: dashboard.php");
    exit;

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
    case 'add_to_cart':
    session_start();

    $menu_id = intval($_POST['menu_id']);
    $qty = intval($_POST['qty']);

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$menu_id])) {
        $_SESSION['cart'][$menu_id] += $qty;
    } else {
        $_SESSION['cart'][$menu_id] = $qty;
    }

    echo "<script>
        alert('Menu berhasil ditambahkan ke cart!');
        window.location.href='index.php';
    </script>";
    break;


case 'checkout':
    session_start();

    if (!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
        echo "<script>
            alert('Cart masih kosong!');
            window.location.href='index.php';
        </script>";
        exit;
    }

    mysqli_query($conn, "INSERT INTO `transaction` (order_status) VALUES ('Incoming')");
    $transaction_id = mysqli_insert_id($conn);

    foreach ($_SESSION['cart'] as $menu_id => $qty) {
        $menu_id = intval($menu_id);
        $qty = intval($qty);

        $check_menu = mysqli_query($conn, "SELECT qty FROM menu WHERE menu_id = $menu_id");
        $menu = mysqli_fetch_assoc($check_menu);

        if ($menu && $menu['qty'] >= $qty) {
            mysqli_query($conn, "UPDATE menu SET qty = qty - $qty WHERE menu_id = $menu_id");

            mysqli_query($conn, "INSERT INTO transaction_detail 
            (transaction_id, menu_id, price, qty) 
            VALUES ($transaction_id, $menu_id, 15000, $qty)");
        }
    }

    unset($_SESSION['cart']);

    header("Location: receipt.php?id=$transaction_id");
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