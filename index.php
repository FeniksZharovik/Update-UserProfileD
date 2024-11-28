<?php
include 'config.php';
session_start();

// Cek cookie untuk login
if (!isset($_SESSION['user']) && isset($_COOKIE['user'])) {
    $_SESSION['user'] = json_decode($_COOKIE['user'], true);
}

$user = $_SESSION['user'];

// Simpan status login di cookie
setcookie('user', json_encode($user), time() + (86400 * 30), "/"); // 30 hari

$sql = "SELECT uid, nama_lengkap FROM user WHERE uid='{$user['uid']}'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Dashboard</title>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-white shadow-md py-4">
        <div class="container mx-auto flex justify-between items-center px-6">
            <h1 class="text-2xl font-bold text-indigo-600">Dashboard</h1>
            <div class="flex items-center">
                <a href="profile.php" class="mr-4">
                    <img src="https://img.icons8.com/ios-filled/50/000000/user.png" alt="Profile Icon" class="w-8 h-8">
                </a>
                <a href="logout.php" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Logout</a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="flex flex-1">
        <!-- Sidebar -->
        <aside class="bg-indigo-600 text-white w-64 py-6 px-4">
            <nav>
                <ul>
                    <li class="mb-4">
                        <a href="#" class="flex items-center text-white hover:text-indigo-200">
                            <img src="https://img.icons8.com/ios-filled/24/ffffff/home.png" class="mr-2">
                            Home
                        </a>
                    </li>
                    <li class="mb-4">
                        <a href="#" class="flex items-center text-white hover:text-indigo-200">
                            <img src="https://img.icons8.com/ios-filled/24/ffffff/settings.png" class="mr-2">
                            Settings
                        </a>
                    </li>
                    <li class="mb-4">
                        <a href="#" class="flex items-center text-white hover:text-indigo-200">
                            <img src="https://img.icons8.com/ios-filled/24/ffffff/info.png" class="mr-2">
                            About
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Dashboard Content -->
        <main class="flex-1 p-6">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Selamat Datang, <?= $user['nama_lengkap'] ?></h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Card 1 -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Statistik Pengguna</h3>
                    <p class="text-gray-600">Jumlah pengguna aktif: 120</p>
                </div>
                <!-- Card 2 -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Penjualan Bulanan</h3>
                    <p class="text-gray-600">Total penjualan: Rp 50.000.000</p>
                </div>
                <!-- Card 3 -->
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Feedback Pelanggan</h3>
                    <p class="text-gray-600">Rating rata-rata: 4.5/5</p>
                </div>
            </div>

            <!-- Chart Section -->
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Grafik Penjualan</h3>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <canvas id="salesChart" width="400" height="200"></canvas>
                </div>
            </div>
        </main>
    </div>

    <script>
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni'],
                datasets: [{
                    label: 'Penjualan',
                    data: [12000, 19000, 3000, 5000, 20000, 30000],
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>