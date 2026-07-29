<?php
// edit_trainers.php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: list_trainers.php');
    exit;
}

$id = $_GET['id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Trainer</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto mt-10 p-4 bg-gray-900 rounded-lg shadow-md">
        <h2 class="text-2xl text-orange-500 font-bold mb-4">Edit Trainer</h2>
        <form id="edit-trainer-form">
            <div class="mb-4">
                <label for="name" class="block text-orange-500 text-sm font-bold mb-2">Name</label>
                <input type="text" id="name" name="name" class="block w-full p-2 bg-gray-800 text-orange-500 border border-gray-700 rounded">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-orange-500 text-sm font-bold mb-2">Email</label>
                <input type="email" id="email" name="email" class="block w-full p-2 bg-gray-800 text-orange-500 border border-gray-700 rounded">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-orange-500 text-sm font-bold mb-2">Phone</label>
                <input type="text" id="phone" name="phone" class="block w-full p-2 bg-gray-800 text-orange-500 border border-gray-700 rounded">
            </div>
            <button type="submit" class="bg-orange-500 hover:bg-orange-700 text-gray-900 font-bold py-2 px-4 rounded">Update Trainer</button>
        </form>
    </div>

    <script>
        const form = document.getElementById('edit-trainer-form');
        const id = '<?php echo $id; ?>';

        // Fetch existing record details
        fetch(`../backend/trainers.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('name').value = data.name;
                document.getElementById('email').value = data.email;
                document.getElementById('phone').value = data.phone;
            });

        // Submit form using AJAX PUT request
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            fetch('../backend/trainers.php', {
                method: 'PUT',
                body: JSON.stringify({
                    id: id,
                    name: formData.get('name'),
                    email: formData.get('email'),
                    phone: formData.get('phone')
                }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_trainers.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch(error => console.error(error));
        });
    </script>
</body>
</html>