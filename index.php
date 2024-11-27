<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];

// Proses pembaruan data
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    $uid = $data['uid'];
    $field = $data['field'];
    $value = $data['value'];

    $sql = "UPDATE user SET $field = '$value' WHERE uid = '$uid'";

    $response = [];
    if ($conn->query($sql) === TRUE) {
        $response['success'] = true;
        // Update session data
        $_SESSION['user'][$field] = $value;
    } else {
        $response['success'] = false;
    }

    echo json_encode($response);
    exit();
}

$sql = "SELECT uid, nama_lengkap, nama_pengguna, role, IFNULL(kredensial, 'kredensial anda') AS kredensial FROM user WHERE uid='{$user['uid']}'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Informasi Akun</title>
</head>
<body class="bg-gradient-to-r from-indigo-500 to-purple-500 flex items-center justify-center min-h-screen">
    <div class="max-w-lg w-full bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold text-indigo-600">Informasi Akun Anda</h2>
                <a href="logout.php" class="text-red-500 hover:text-red-700">Logout</a>
            </div>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="bg-gray-100 p-4 rounded-lg mb-4">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-lg font-semibold text-gray-800">Nama Lengkap</p>
                                <p class="text-sm text-gray-600" data-field="nama_lengkap"><?= $row['nama_lengkap'] ?></p>
                            </div>
                            <button class="edit-button" data-field="nama_lengkap" data-uid="<?= $row['uid'] ?>">
                                <img src="https://img.icons8.com/ios-glyphs/30/000000/pencil.png" alt="Edit">
                            </button>
                        </div>
                        <div class="flex justify-between items-center mt-2">
                            <div>
                                <p class="text-lg font-semibold text-gray-800">Username</p>
                                <p class="text-sm text-gray-600" data-field="nama_pengguna"><?= $row['nama_pengguna'] ?></p>
                            </div>
                            <button class="edit-button" data-field="nama_pengguna" data-uid="<?= $row['uid'] ?>">
                                <img src="https://img.icons8.com/ios-glyphs/30/000000/pencil.png" alt="Edit">
                            </button>
                        </div>
                        <div class="flex justify-between items-center mt-2">
                            <div>
                                <p class="text-lg font-semibold text-gray-800">Posisi</p>
                                <p class="text-sm text-gray-600" data-field="role"><?= $row['role'] ?></p>
                            </div>
                            <button class="edit-button" data-field="role" data-uid="<?= $row['uid'] ?>">
                                <img src="https://img.icons8.com/ios-glyphs/30/000000/pencil.png" alt="Edit">
                            </button>
                        </div>
                        <div class="flex justify-between items-center mt-2">
                            <div>
                                <p class="text-lg font-semibold text-gray-800">Info Lainnya</p>
                                <p class="text-sm text-gray-600" data-field="kredensial"><?= $row['kredensial'] ?></p>
                            </div>
                            <button class="edit-button" data-field="kredensial" data-uid="<?= $row['uid'] ?>">
                                <img src="https://img.icons8.com/ios-glyphs/30/000000/pencil.png" alt="Edit">
                            </button>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p class="text-gray-500">Tidak ada data.</p>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal -->
    <div id="confirmationModal" class="fixed inset-0 bg-gray-800 bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <p class="mb-4">Apakah Anda yakin ingin menyimpan perubahan ini?</p>
            <button id="confirmButton" class="bg-indigo-500 text-white p-2 rounded mr-2">Ya</button>
            <button id="cancelButton" class="bg-red-500 text-white p-2 rounded">Tidak</button>
        </div>
    </div>

    <script>
        let currentInput, currentValue, currentField, currentUid;

        document.querySelectorAll('.edit-button').forEach(button => {
            button.addEventListener('click', function() {
                const field = this.getAttribute('data-field');
                const uid = this.getAttribute('data-uid');
                const valueElement = this.previousElementSibling.querySelector('p.text-sm');
                currentValue = valueElement.textContent;

                const input = document.createElement('input');
                input.type = 'text';
                input.value = currentValue;
                input.className = 'w-full p-2 border border-gray-300 rounded mt-1';

                valueElement.replaceWith(input);
                currentInput = input;
                currentField = field;
                currentUid = uid;

                input.addEventListener('keydown', function(event) {
                    if (event.key === 'Enter') {
                        document.getElementById('confirmationModal').classList.remove('hidden');
                    }
                });
            });
        });

        document.getElementById('confirmButton').addEventListener('click', function() {
            const newValue = currentInput.value;
            document.getElementById('confirmationModal').classList.add('hidden');

           
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    uid: currentUid,
                    field: currentField,
                    value: newValue
                })
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      currentInput.replaceWith(document.createTextNode(newValue));
                  } else {
                      alert('Gagal memperbarui data.');
                  }
              });
        });

        document.getElementById('cancelButton').addEventListener('click', function() {
            document.getElementById('confirmationModal').classList.add('hidden');
            currentInput.replaceWith(document.createTextNode(currentValue));
        });
    </script>
</body>
</html>