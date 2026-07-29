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
    <title>المواد التدريبية</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <header class="bg-emerald-600 text-white p-4">
        <nav class="flex justify-between">
            <a href="index.php" class="text-lg font-bold">الرئيسية</a>
            <div class="flex items-center">
                <span class="mr-2"><?= $_SESSION['username'] ?></span>
                <a href="logout.php" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">تسجيل الخروج</a>
            </div>
        </nav>
    </header>
    <main class="p-4">
        <h1 class="text-3xl font-bold mb-4">المواد التدريبية</h1>
        <div class="flex justify-between mb-4">
            <a href="create_المواد-التدريبية.php" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded">إضافة جديد</a>
            <input type="search" id="search" class="py-2 pl-10 text-sm text-gray-700" placeholder="بحث...">
        </div>
        <table id="records" class="w-full text-right">
            <thead class="bg-emerald-600 text-white">
                <tr>
                    <th class="py-2">الاسم</th>
                    <th class="py-2">الوصف</th>
                    <th class="py-2">العمليات</th>
                </tr>
            </thead>
            <tbody id="records-body">
                <!-- Records will be populated here -->
            </tbody>
        </table>
    </main>

    <script>
        // Fetch records from backend
        fetch('../backend/المواد-التدريبية.php')
            .then(response => response.json())
            .then(data => {
                const recordsBody = document.getElementById('records-body');
                data.forEach(record => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${record.name}</td>
                        <td>${record.description}</td>
                        <td>
                            <a href="edit_المواد-التدريبية.php?id=${record.id}" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">تعديل</a>
                            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="deleteRecord(${record.id})">حذف</button>
                        </td>
                    `;
                    recordsBody.appendChild(row);
                });
            });

        // Delete record
        function deleteRecord(id) {
            fetch('../backend/المواد-التدريبية.php', {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove record from table
                    const recordsBody = document.getElementById('records-body');
                    const rows = recordsBody.children;
                    for (let i = 0; i < rows.length; i++) {
                        const row = rows[i];
                        const cells = row.children;
                        const idCell = cells[2].children[1];
                        if (idCell.getAttribute('onclick').includes(`deleteRecord(${id})`)) {
                            recordsBody.removeChild(row);
                            break;
                        }
                    }
                }
            });
        }

        // Search functionality
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('input', () => {
            const searchValue = searchInput.value.toLowerCase();
            const rows = document.getElementById('records-body').children;
            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const cells = row.children;
                const nameCell = cells[0].textContent;
                const descriptionCell = cells[1].textContent;
                if (nameCell.toLowerCase().includes(searchValue) || descriptionCell.toLowerCase().includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    </script>
</body>
</html>