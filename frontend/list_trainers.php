<?php
// Session validation
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trainers List</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-900 text-white">
    <header class="bg-orange-500 p-4 text-white">
        <nav class="flex justify-between">
            <a href="index.php" class="text-lg font-bold">Back to Index</a>
            <span class="text-lg font-bold">Welcome, <?php echo $_SESSION['username']; ?></span>
            <a href="logout.php" class="text-lg font-bold">Logout</a>
        </nav>
    </header>
    <main class="p-4">
        <h1 class="text-3xl font-bold mb-4">Trainers List</h1>
        <input type="search" id="search" placeholder="Search trainers..." class="w-full p-2 mb-4 border border-gray-700 rounded">
        <table id="trainers-table" class="w-full text-white">
            <thead>
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody id="trainers-tbody">
                <!-- Table content will be populated via AJAX -->
            </tbody>
        </table>
        <a href="create_trainers.php" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded mt-4">Add New Item</a>
    </main>

    <script>
        // Fetch trainers data from backend
        fetch('../backend/trainers.php')
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('trainers-tbody');
                data.forEach(trainer => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-4 py-2">${trainer.id}</td>
                        <td class="px-4 py-2">${trainer.name}</td>
                        <td class="px-4 py-2">
                            <a href="edit_trainers.php?id=${trainer.id}" class="text-orange-500 hover:text-orange-700">Edit</a>
                            <button class="text-orange-500 hover:text-orange-700" onclick="deleteTrainer(${trainer.id})">Delete</button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            });

        // Delete trainer via AJAX
        function deleteTrainer(id) {
            fetch('../backend/trainers.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove deleted row from table
                    const rows = document.getElementById('trainers-tbody').children;
                    for (let i = 0; i < rows.length; i++) {
                        if (rows[i].children[0].textContent == id) {
                            rows[i].remove();
                            break;
                        }
                    }
                } else {
                    console.error('Error deleting trainer:', data.error);
                }
            });
        }

        // Search bar filtering
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', () => {
            const searchValue = searchInput.value.toLowerCase();
            const rows = document.getElementById('trainers-tbody').children;
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const rowText = row.textContent.toLowerCase();
                if (rowText.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>