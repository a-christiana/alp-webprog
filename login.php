<?php
session_start();

if (isset($_SESSION['admin_logged_in'])) {
    header("Location: dashboard.php");
    exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = $_POST['password'];

    if ($password === 'admin123') {
        $_SESSION['admin_logged_in'] = true;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Password salah! Coba lagi.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Luciole</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Plus+Jakarta+Sans:wght@500;700&display=swap" rel="stylesheet">
    <style>
        .font-serif-login { font-family: 'Playfair Display', serif; }
        .font-sans-login { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-gray-400/30 backdrop-blur-sm min-h-screen flex items-center justify-center font-sans-login p-4">

    <div class="bg-[#FBE49D] w-full max-w-[420px] rounded-[40px] p-10 shadow-2xl flex flex-col items-center relative border border-yellow-200/50">
        
        <div class="w-24 h-24 bg-white rounded-full shadow-inner mb-6 flex items-center justify-center">
            <span class="text-2xl">✨</span>
        </div>

        <h1 class="text-3xl font-serif-login font-bold tracking-wider text-black mb-2">
            ADMIN LOGIN
        </h1>

        <p class="text-sm font-medium text-black text-center mb-8">
            Enter your password to access admin
        </p>

        <form action="" method="POST" class="w-full space-y-4">
            
            <div class="w-full">
                <input type="password" name="password" placeholder="••••••••" 
                       class="w-full bg-white text-center text-gray-800 py-3.5 px-6 rounded-full font-bold focus:outline-none focus:ring-4 focus:ring-yellow-400/50 shadow-md transition placeholder-gray-300" required>
            </div>

            <button type="submit" 
                    class="w-full bg-white hover:bg-gray-50 active:scale-[0.98] text-gray-800 font-bold py-3.5 rounded-full shadow-md transition tracking-wide text-sm uppercase">
                Login &rarr;
            </button>
        </form>

        <?php if (!empty($error)): ?>
            <p class="text-xs text-red-600 font-bold mt-4 bg-red-50 px-3 py-1 rounded-full border border-red-200">
                <?= $error; ?>
            </p>
        <?php endif; ?>

        <a href="index.php" class="text-xs text-gray-600 hover:text-black font-semibold mt-6 transition underline underline-offset-4">
            &larr; Kembali ke Toko
        </a>
    </div>

</body>
</html>