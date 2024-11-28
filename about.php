<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Tentang Kami</title>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-white shadow-md py-4">
        <div class="container mx-auto flex justify-between items-center px-6">
            <h1 class="text-2xl font-bold text-indigo-600">Tentang Kami</h1>
            <a href="index.php" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Kembali</a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 p-6">
        <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Visi dan Misi</h2>
            <p class="text-gray-700 mb-4">Kami berkomitmen untuk menyediakan layanan terbaik dengan fokus pada kualitas dan kepuasan pelanggan. Visi kami adalah menjadi pemimpin dalam industri ini dengan inovasi dan integritas.</p>
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Tim Kami</h2>
            <p class="text-gray-700 mb-4">Tim kami terdiri dari para profesional yang berdedikasi dan berpengalaman di bidangnya masing-masing. Kami bekerja sama untuk mencapai tujuan bersama dan memberikan hasil terbaik bagi klien kami.</p>
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Kontak</h2>
            <p class="text-gray-700">Untuk informasi lebih lanjut, silakan hubungi kami di <a href="mailto:info@company.com" class="text-indigo-600 hover:underline">info@company.com</a>.</p>
        </div>
    </main>
</body>
</html>