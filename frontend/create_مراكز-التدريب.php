<?php
// create_مراكز-التدريب.php

// Session validation
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
include '../backend/db.php';

// Define module slug
$mod_slug = 'مراكز-التدريب';

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة مركز تدريب</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4 pt-6 mt-10 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl text-emerald-600 mb-4">إضافة مركز تدريب</h2>
        <form id="create-form">
            <div class="mb-4">
                <label for="name" class="block text-sm text-gray-600 mb-2">اسم المركز</label>
                <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-lg focus:outline-none focus:ring-emerald-600 focus:border-emerald-600" required>
            </div>
            <div class="mb-4">
                <label for="address" class="block text-sm text-gray-600 mb-2">عنوان المركز</label>
                <input type="text" id="address" name="address" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-lg focus:outline-none focus:ring-emerald-600 focus:border-emerald-600" required>
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-sm text-gray-600 mb-2">رقم الهاتف</label>
                <input type="tel" id="phone" name="phone" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-lg focus:outline-none focus:ring-emerald-600 focus:border-emerald-600" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm text-gray-600 mb-2">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-lg focus:outline-none focus:ring-emerald-600 focus:border-emerald-600" required>
            </div>
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded-lg">إضافة</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../backend/مراكز-التدريب.php',
                    data: $(this).serialize(),
                    success: function() {
                        window.location.href = 'list_<?php echo $mod_slug; ?>.php';
                    }
                });
            });
        });
    </script>
</body>
</html>