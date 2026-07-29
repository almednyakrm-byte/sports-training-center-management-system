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
    <title>Trainees List</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-900 text-white">
    <header class="bg-orange-500 p-4 flex justify-between">
        <a href="index.php" class="text-lg font-bold">Back to Index</a>
        <div class="flex items-center">
            <span class="mr-4">Welcome, <?php echo $_SESSION['username']; ?></span>
            <a href="logout.php" class="text-lg font-bold">Logout</a>
        </div>
    </header>
    <main class="p-4">
        <h1 class="text-3xl font-bold mb-4">Trainees List</h1>
        <input type="search" id="search" class="w-full p-2 mb-4" placeholder="Search...">
        <table id="trainees-table" class="w-full table-auto">
            <thead>
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody id="trainees-tbody">
                <!-- Table content will be populated by JavaScript -->
            </tbody>
        </table>
        <a href="create_trainees.php" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded mt-4">Add New Item</a>
    </main>

    <script>
        // Fetch trainees data from backend
        fetch('../backend/trainees.php')
            .then(response => response.json())
            .then(data => {
                const tbody = document.getElementById('trainees-tbody');
                data.forEach(trainee => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-4 py-2">${trainee.id}</td>
                        <td class="px-4 py-2">${trainee.name}</td>
                        <td class="px-4 py-2">
                            <a href="edit_trainees.php?id=${trainee.id}" class="text-orange-500 hover:text-orange-700">Edit</a>
                            <button class="text-red-500 hover:text-red-700 ml-2" onclick="deleteTrainee(${trainee.id})">Delete</button>
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            });

        // Delete trainee by ID
        function deleteTrainee(id) {
            fetch('../backend/trainees.php', {
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
                    const rows = document.getElementById('trainees-tbody').children;
                    for (let i = 0; i < rows.length; i++) {
                        if (rows[i].children[0].textContent == id) {
                            rows[i].remove();
                            break;
                        }
                    }
                } else {
                    console.error('Error deleting trainee:', data.error);
                }
            });
        }

        // Search functionality
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', () => {
            const searchValue = searchInput.value.toLowerCase();
            const rows = document.getElementById('trainees-tbody').children;
            for (let i = 0; i < rows.length; i++) {
                const rowText = rows[i].textContent.toLowerCase();
                if (rowText.includes(searchValue)) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>