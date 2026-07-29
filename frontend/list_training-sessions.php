<?php
// Session validation
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}

// Current user info
$current_user = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Training Sessions</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-900 text-white">
    <header class="bg-orange-500 py-4">
        <nav class="container mx-auto flex justify-between">
            <a href="index.php" class="text-lg font-bold">Home</a>
            <span class="text-lg font-bold">Welcome, <?php echo $current_user; ?></span>
            <a href="logout.php" class="text-lg font-bold">Logout</a>
        </nav>
    </header>
    <main class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-24">
        <h1 class="text-3xl font-bold mb-4">Training Sessions</h1>
        <div class="flex justify-between mb-4">
            <a href="create_training-sessions.php" class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded">Add New Item</a>
            <input type="search" id="search" class="bg-gray-800 text-white font-bold py-2 px-4 rounded" placeholder="Search...">
        </div>
        <table id="training-sessions-table" class="w-full text-white">
            <thead class="bg-gray-800">
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <!-- Table data will be populated here -->
            </tbody>
        </table>
    </main>

    <script>
        // Fetch API to get training sessions
        fetch('../backend/training-sessions.php')
            .then(response => response.json())
            .then(data => {
                const tableBody = document.getElementById('table-body');
                data.forEach(session => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-4 py-2">${session.id}</td>
                        <td class="px-4 py-2">${session.name}</td>
                        <td class="px-4 py-2">
                            <a href="edit_training-sessions.php?id=${session.id}" class="text-orange-500 hover:text-orange-700">Edit</a>
                            <button class="text-orange-500 hover:text-orange-700" onclick="deleteSession(${session.id})">Delete</button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            });

        // Delete session function
        function deleteSession(id) {
            fetch('../backend/training-sessions.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the deleted row from the table
                    const tableBody = document.getElementById('table-body');
                    const rows = tableBody.children;
                    for (let i = 0; i < rows.length; i++) {
                        if (rows[i].children[0].textContent == id) {
                            tableBody.removeChild(rows[i]);
                            break;
                        }
                    }
                }
            });
        }

        // Search bar functionality
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', () => {
            const searchValue = searchInput.value.toLowerCase();
            const rows = document.getElementById('table-body').children;
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