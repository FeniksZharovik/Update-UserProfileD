<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Pengaturan</title>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-white shadow-md py-4">
        <div class="container mx-auto flex justify-between items-center px-6">
            <h1 class="text-2xl font-bold text-indigo-600">Pengaturan</h1>
            <a href="index.php" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Kembali</a>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 p-6">
        <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
            <h2 class="text-xl font-semibold text-gray-800 mb-4">Pengaturan Akun</h2>
            <form>
                <div class="mb-4">
                    <label class="block text-gray-700">Email</label>
                    <input type="email" class="w-full p-2 border border-gray-300 rounded mt-1" value="user@example.com">
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">Password</label>
                    <input type="password" class="w-full p-2 border border-gray-300 rounded mt-1" placeholder="********">
                </div>
                <button type="submit" class="bg-indigo-500 text-white px-4 py-2 rounded hover:bg-indigo-600">Simpan Perubahan</button>
            </form>
            <h2 class="text-xl font-semibold text-gray-800 mt-8 mb-4">Preferensi</h2>
            <div class="mb-4">
                <label class="block text-gray-700">Bahasa</label>
                <select class="w-full p-2 border border-gray-300 rounded mt-1">
                    <option>Indonesia</option>
                    <option>English</option>
                </select>
            </div>
        </div>
    </main>
</body>
</html> 