<?php
// Session validation
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>مراكز التدريب</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-emerald-600 text-white py-4">
        <nav class="container mx-auto flex justify-between">
            <a href="index.php" class="text-lg font-bold">الرئيسية</a>
            <div class="flex items-center">
                <span class="mr-4"><?= $_SESSION['username'] ?></span>
                <a href="logout.php" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">تسجيل الخروج</a>
            </div>
        </nav>
    </header>
    <main class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-24">
        <h1 class="text-3xl font-bold mb-4">مراكز التدريب</h1>
        <div class="flex justify-between mb-4">
            <a href="create_مراكز-التدريب.php" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded">إضافة جديد</a>
            <input type="search" id="search" class="py-2 pl-10 text-sm text-gray-700" placeholder="بحث...">
        </div>
        <table id="table" class="w-full table-auto border-collapse border border-gray-400">
            <thead class="bg-emerald-600 text-white">
                <tr>
                    <th class="py-2">الاسم</th>
                    <th class="py-2">الوصف</th>
                    <th class="py-2">العمليات</th>
                </tr>
            </thead>
            <tbody id="table-body">
                <!-- Table data will be populated here -->
            </tbody>
        </table>
    </main>

    <script>
        // Fetch API to get list records
        fetch('../backend/مراكز-التدريب.php')
            .then(response => response.json())
            .then(data => {
                const tableBody = document.getElementById('table-body');
                data.forEach(item => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${item.name}</td>
                        <td>${item.description}</td>
                        <td>
                            <a href="edit_مراكز-التدريب.php?id=${item.id}" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteItem(${item.id})">حذف</button>
                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            });

        // Delete item using AJAX
        function deleteItem(id) {
            fetch('../backend/مراكز-التدريب.php', {
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
                        if (rows[i].children[2].children[1].onclick.toString().includes(id.toString())) {
                            tableBody.removeChild(rows[i]);
                            break;
                        }
                    }
                }
            });
        }

        // Search bar filtering
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