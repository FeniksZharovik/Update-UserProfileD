<?php
include 'config.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $identifier = isset($_POST['identifier']) ? $_POST['identifier'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    $sql = "SELECT * FROM user WHERE nama_pengguna='$identifier' OR email='$identifier'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['user'] = $row;
            setcookie('user', json_encode($row), time() + (86400 * 30), "/"); // Simpan status login di cookie selama 30 hari
            header("Location: index.php");
        } else {
            echo "Password salah.";
        }
    } else {
        echo "Nama pengguna atau email tidak ditemukan.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Login</title>
</head>
<body class="bg-gradient-to-r from-indigo-500 to-purple-500 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-96">
        <h2 class="text-3xl font-bold mb-6 text-center text-indigo-600">Login</h2>
        <form method="POST">
            <div class="mb-4">
                <label class="block text-gray-700">Username atau Email</label>
                <input type="text" name="identifier" class="w-full p-2 border border-gray-300 rounded mt-1" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Password</label>
                <input type="password" name="password" class="w-full p-2 border border-gray-300 rounded mt-1" required>
            </div>
            <button type="submit" class="w-full bg-indigo-500 text-white p-2 rounded hover:bg-indigo-600">Login</button>
        </form>
    </div>
</body>
</html>