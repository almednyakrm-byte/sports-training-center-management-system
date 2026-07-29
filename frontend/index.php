<?php
// Session check
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام إدارة مراكز تدريب رياضي</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="h-screen bg-emerald-600 flex justify-center items-center">
    <div class="glassmorphism-card w-11/12 h-5/6 p-4 rounded-2xl bg-white/20 backdrop-blur-md">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-3xl text-teal-500 font-bold">مرحباً!</h1>
            <button class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded" onclick="logout()">تسجيل الخروج</button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
            <div class="bg-emerald-600/20 p-4 rounded-2xl">
                <h2 class="text-2xl text-teal-500 font-bold mb-2">مراكز التدريب</h2>
                <p id="training-centers-count" class="text-4xl text-teal-500 font-bold"></p>
            </div>
            <div class="bg-emerald-600/20 p-4 rounded-2xl">
                <h2 class="text-2xl text-teal-500 font-bold mb-2">المواد التدريبية</h2>
                <p id="training-materials-count" class="text-4xl text-teal-500 font-bold"></p>
            </div>
            <div class="bg-emerald-600/20 p-4 rounded-2xl">
                <h2 class="text-2xl text-teal-500 font-bold mb-2">الموظفين</h2>
                <p id="employees-count" class="text-4xl text-teal-500 font-bold"></p>
            </div>
            <div class="bg-emerald-600/20 p-4 rounded-2xl">
                <h2 class="text-2xl text-teal-500 font-bold mb-2">حجوزات المواعيد</h2>
                <p id="bookings-count" class="text-4xl text-teal-500 font-bold"></p>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <a href="training-centers.php" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">إدارة مراكز التدريب</a>
            <a href="training-materials.php" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">إدارة المواد التدريبية</a>
            <a href="employees.php" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded">إدارة الموظفين</a>
        </div>
    </div>

    <script>
        function logout() {
            window.location.href = 'logout.php';
        }

        // Fetch stats dynamically via Javascript API calls from the backend files
        fetch('api/training-centers-count.php')
            .then(response => response.json())
            .then(data => document.getElementById('training-centers-count').innerText = data.count);

        fetch('api/training-materials-count.php')
            .then(response => response.json())
            .then(data => document.getElementById('training-materials-count').innerText = data.count);

        fetch('api/employees-count.php')
            .then(response => response.json())
            .then(data => document.getElementById('employees-count').innerText = data.count);

        fetch('api/bookings-count.php')
            .then(response => response.json())
            .then(data => document.getElementById('bookings-count').innerText = data.count);
    </script>
</body>
</html>