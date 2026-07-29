<?php
// Session validation
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}

// Get current user info
$current_user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facilities Management</title>
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
    <main class="container mx-auto p-4 pt-6 mt-10 bg-gray-900">
        <h1 class="text-3xl font-bold mb-4">Facilities Management</h1>
        <button class="bg-orange-500 hover:bg-orange-700 text-white font-bold py-2 px-4 rounded mb-4">
            <a href="create_facilities.php">Add New Item</a>
        </button>
        <input type="text" id="search" class="bg-gray-800 text-white font-bold py-2 px-4 rounded mb-4 w-full" placeholder="Search...">
        <table id="facilities-table" class="w-full text-white">
            <thead>
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Name</th>
                    <th class="px-4 py-2">Actions</th>
                </tr>
            </thead>
            <tbody id="facilities-tbody">
                <!-- Table data will be populated here -->
            </tbody>
        </table>
    </main>

    <script>
        // Fetch facilities data from backend
        fetch('../backend/facilities.php')
            .then(response => response.json())
            .then(data => {
                const facilitiesTable = document.getElementById('facilities-table');
                const facilitiesTbody = document.getElementById('facilities-tbody');
                data.forEach(facility => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-4 py-2">${facility.id}</td>
                        <td class="px-4 py-2">${facility.name}</td>
                        <td class="px-4 py-2">
                            <a href="edit_facilities.php?id=${facility.id}" class="text-orange-500 hover:text-orange-700">Edit</a>
                            <button class="text-orange-500 hover:text-orange-700" onclick="deleteFacility(${facility.id})">Delete</button>
                        </td>
                    `;
                    facilitiesTbody.appendChild(row);
                });
            });

        // Delete facility using AJAX
        function deleteFacility(id) {
            fetch(`../backend/facilities.php?id=${id}`, {
                method: 'DELETE'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the deleted facility from the table
                    const facilitiesTable = document.getElementById('facilities-table');
                    const facilitiesTbody = document.getElementById('facilities-tbody');
                    const rows = facilitiesTbody.children;
                    for (let i = 0; i < rows.length; i++) {
                        const row = rows[i];
                        const idCell = row.children[0];
                        if (idCell.textContent == id) {
                            facilitiesTbody.removeChild(row);
                            break;
                        }
                    }
                }
            });
        }

        // Search facilities in real-time
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', () => {
            const searchValue = searchInput.value.toLowerCase();
            const facilitiesTable = document.getElementById('facilities-table');
            const facilitiesTbody = document.getElementById('facilities-tbody');
            const rows = facilitiesTbody.children;
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const nameCell = row.children[1];
                if (nameCell.textContent.toLowerCase().includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>