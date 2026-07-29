<?php
// create_المواد-التدريبية.php

// Session validation
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
include '../backend/db.php';

// Set module slug
$mod_slug = 'المواد-التدريبية';

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة المواد التدريبية</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-5xl mx-auto p-4 sm:p-6 md:p-8 mb-12">
        <h1 class="text-3xl text-emerald-600 font-bold mb-4">إضافة المواد التدريبية</h1>
        <form id="create-form">
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">العنوان</label>
                <input type="text" id="title" name="title" class="mt-1 focus:ring-emerald-600 focus:border-emerald-600 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">الوصف</label>
                <textarea id="description" name="description" class="mt-1 focus:ring-emerald-600 focus:border-emerald-600 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
            </div>
            <div class="mb-4">
                <label for="category" class="block text-sm font-medium text-gray-700">الفئة</label>
                <select id="category" name="category" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-emerald-600 focus:border-emerald-600 sm:text-sm">
                    <option value="">اختر الفئة</option>
                    <?php
                    // Fetch categories from database
                    $categories = mysqli_query($conn, "SELECT * FROM categories");
                    while ($category = mysqli_fetch_assoc($categories)) {
                        echo '<option value="' . $category['id'] . '">' . $category['name'] . '</option>';
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="py-2 px-4 bg-emerald-600 hover:bg-emerald-700 focus:ring-emerald-600 focus:ring-offset-emerald-600 text-white w-full transition ease-in duration-200 text-center text-base font-semibold shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 rounded-lg">إضافة</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../backend/المواد-التدريبية.php',
                    data: $(this).serialize(),
                    success: function() {
                        window.location.href = 'list_المواد-التدريبية.php';
                    }
                });
            });
        });
    </script>
</body>
</html>