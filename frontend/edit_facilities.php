<?php
// edit_facilities.php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: list_facilities.php');
    exit;
}

$id = $_GET['id'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Facility</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto mt-10 p-4 bg-gray-900 rounded-md shadow-md">
        <h2 class="text-2xl text-orange-500 font-bold mb-4">Edit Facility</h2>
        <form id="edit-facility-form">
            <div class="mb-4">
                <label for="name" class="block text-gray-300 text-sm font-bold mb-2">Name</label>
                <input type="text" id="name" name="name" class="block w-full p-2 bg-gray-800 text-gray-300 border border-gray-700 rounded-md">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-300 text-sm font-bold mb-2">Description</label>
                <textarea id="description" name="description" class="block w-full p-2 bg-gray-800 text-gray-300 border border-gray-700 rounded-md"></textarea>
            </div>
            <button type="submit" class="bg-orange-500 hover:bg-orange-700 text-gray-300 font-bold py-2 px-4 rounded-md">Update Facility</button>
        </form>
    </div>

    <script>
        const form = document.getElementById('edit-facility-form');
        const id = <?php echo $id; ?>;

        // Fetch existing record details
        fetch(`../backend/facilities.php?id=${id}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('name').value = data.name;
                document.getElementById('description').value = data.description;
            })
            .catch(error => console.error('Error:', error));

        // Submit form with AJAX PUT request
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            fetch(`../backend/facilities.php`, {
                method: 'PUT',
                body: JSON.stringify({
                    id: id,
                    name: formData.get('name'),
                    description: formData.get('description')
                }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'list_facilities.php';
                } else {
                    console.error('Error:', data.message);
                }
            })
            .catch(error => console.error('Error:', error));
        });
    </script>
</body>
</html>