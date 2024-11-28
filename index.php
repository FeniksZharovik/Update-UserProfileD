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
    <div class="absolute top-4 right-4">
        <a href="logout.php" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Logout</a>
    </div>
    <div class="max-w-lg w-full bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-6">
            <div class="flex items-center mb-4">
                <img src="https://img.icons8.com/ios-filled/50/000000/checked-user-male.png" alt="User Icon" class="mr-2">
                <h2 class="text-2xl text-black">Informasi Akun Anda</h2>
            </div>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="bg-white p-4 rounded-lg mb-4">
                        <div class="flex items-center mb-2">
                            <img src="https://img.icons8.com/ios-filled/24/888888/user.png" alt="Nama Lengkap Icon" class="mr-2">
                            <div class="flex-grow">
                                <p class="text-sm text-gray-700">Nama Lengkap</p>
                                <p class="text-lg font-bold text-gray-800"><?= $row['nama_lengkap'] ?></p>
                                <p class="text-xs text-gray-500">Informasi ini harus akurat</p>
                            </div>
                            <button class="edit-button ml-4" data-field="nama_lengkap" data-uid="<?= $row['uid'] ?>">
                                <img src="https://img.icons8.com/ios-filled/20/1176FA/pencil.png" alt="Edit Icon">
                            </button>
                        </div>
                        <hr class="border-gray-300 mb-2">
                        <div class="flex items-center mb-2">
                            <img src="https://img.icons8.com/ios-filled/24/888888/contacts.png" alt="Username Icon" class="mr-2">
                            <div class="flex-grow">
                                <p class="text-sm text-gray-700">Username</p>
                                <p class="text-lg font-bold text-gray-800"><?= $row['nama_pengguna'] ?></p>
                                <p class="text-xs text-gray-500">Nama ini akan terlihat pembaca dan tertera sebagai editor</p>
                            </div>
                            <button class="edit-button ml-4" data-field="nama_pengguna" data-uid="<?= $row['uid'] ?>">
                                <img src="https://img.icons8.com/ios-filled/20/1176FA/pencil.png" alt="Edit Icon">
                            </button>
                        </div>
                        <hr class="border-gray-300 mb-2">
                        <div class="flex items-center mb-2">
                            <img src="https://img.icons8.com/ios-filled/24/888888/briefcase.png" alt="Posisi Icon" class="mr-2">
                            <div class="flex-grow">
                                <p class="text-sm text-gray-700">Posisi</p>
                                <p class="text-lg font-bold text-gray-800"><?= $row['role'] ?></p>
                                <p class="text-xs text-gray-500">Informasi ini tidak dapat di ubah oleh anda</p>
                            </div>
                            <button class="role-button ml-4" data-field="role" data-uid="<?= $row['uid'] ?>">
                                <img src="https://img.icons8.com/ios-filled/20/1176FA/pencil.png" alt="Edit Icon">
                            </button>
                        </div>
                        <hr class="border-gray-300 mb-2">
                        <div class="flex items-center mb-2">
                            <img src="https://img.icons8.com/ios-filled/24/888888/info.png" alt="Info Lainnya Icon" class="mr-2">
                            <div class="flex-grow">
                                <p class="text-sm text-gray-700">Info Lainnya</p>
                                <p class="text-lg font-bold text-gray-800"><?= $row['kredensial'] ?></p>
                                <p class="text-xs text-gray-500">Nama ini akan terlihat pembaca dan tertera sebagai editor</p>
                            </div>
                            <button class="edit-button ml-4" data-field="kredensial" data-uid="<?= $row['uid'] ?>">
                                <img src="https://img.icons8.com/ios-filled/20/1176FA/pencil.png" alt="Edit Icon">
                            </button>
                        </div>
                        <hr class="border-gray-300">
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
        let currentInput, currentValue, currentField, currentUid, selectedRole;

        document.querySelectorAll('.edit-button').forEach(button => {
            button.addEventListener('click', function() {
                const field = this.getAttribute('data-field');
                const uid = this.getAttribute('data-uid');
                const valueElement = this.previousElementSibling.querySelector('p.text-lg');
                currentValue = valueElement.textContent;

                if (currentInput) {
                    if (currentInput.value === currentValue) {
                        closeInput();
                    } else {
                        document.getElementById('confirmationModal').classList.remove('hidden');
                    }
                    return;
                }

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
                        if (input.value !== currentValue) {
                            document.getElementById('confirmationModal').classList.remove('hidden');
                        } else {
                            closeInput();
                        }
                    }
                });

                document.addEventListener('click', function(event) {
                    if (!input.contains(event.target) && !button.contains(event.target)) {
                        closeInput();
                    }
                }, { once: true });
            });
        });

        document.querySelectorAll('.role-button').forEach(button => {
            button.addEventListener('click', function(event) {
                event.stopPropagation(); // Prevent closing immediately

                const field = this.getAttribute('data-field');
                const uid = this.getAttribute('data-uid');
                const valueElement = this.previousElementSibling.querySelector('p.text-lg');
                currentValue = valueElement.textContent;

                const roleOptions = document.createElement('div');
                roleOptions.className = 'flex flex-col mt-1 bg-gray-100 p-2 rounded shadow';

                const roles = ['pembaca', 'penulis'];
                roles.forEach(role => {
                    const roleOption = document.createElement('p');
                    roleOption.textContent = role;
                    roleOption.className = 'cursor-pointer p-2 rounded hover:bg-gray-200';

                    if (role === currentValue) {
                        roleOption.classList.add('text-gray-400');
                    } else {
                        roleOption.classList.add('text-black');
                        roleOption.addEventListener('click', function() {
                            selectedRole = role;
                            document.getElementById('confirmationModal').classList.remove('hidden');
                        });
                    }

                    roleOptions.appendChild(roleOption);
                });

                valueElement.replaceWith(roleOptions);
                currentInput = roleOptions;
                currentField = field;
                currentUid = uid;

                document.addEventListener('click', function(event) {
                    if (!roleOptions.contains(event.target) && !button.contains(event.target)) {
                        closeInput();
                    }
                }, { once: true });
            });
        });

        function closeInput() {
            const newText = document.createElement('p');
            newText.className = 'text-lg font-bold text-gray-800';
            newText.textContent = currentValue;
            currentInput.replaceWith(newText);
            currentInput = null;
        }

        document.getElementById('confirmButton').addEventListener('click', function() {
            const newValue = selectedRole || currentValue;
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
                      const newText = document.createElement('p');
                      newText.className = 'text-lg font-bold text-gray-800';
                      newText.textContent = newValue;
                      currentInput.replaceWith(newText);
                      currentInput = null;
                  } else {
                      alert('Gagal memperbarui data.');
                  }
              });
        });

        document.getElementById('cancelButton').addEventListener('click', function() {
            document.getElementById('confirmationModal').classList.add('hidden');
            closeInput();
        });
    </script>
</body>
</html>