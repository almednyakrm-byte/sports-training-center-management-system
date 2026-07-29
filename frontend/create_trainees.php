<?php
// Start session
session_start();

// Session validation
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../backend/db.php';

// Define module slug
$mod_slug = 'trainees';

// Define page title
$page_title = 'Create Trainee';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto p-4 mt-10 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-orange-500 mb-4"><?php echo $page_title; ?></h2>
        <form id="create-trainee-form">
            <div class="mb-4">
                <label for="name" class="block text-gray-900 text-sm font-bold mb-2">Name</label>
                <input type="text" id="name" name="name" class="block w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-500 focus:border-orange-500">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-900 text-sm font-bold mb-2">Email</label>
                <input type="email" id="email" name="email" class="block w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-500 focus:border-orange-500">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-gray-900 text-sm font-bold mb-2">Phone</label>
                <input type="text" id="phone" name="phone" class="block w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-500 focus:border-orange-500">
            </div>
            <div class="mb-4">
                <label for="address" class="block text-gray-900 text-sm font-bold mb-2">Address</label>
                <textarea id="address" name="address" class="block w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-orange-500 focus:border-orange-500"></textarea>
            </div>
            <button type="submit" class="w-full bg-orange-500 hover:bg-orange-700 text-gray-900 font-bold py-2 px-4 rounded-lg">Create Trainee</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-trainee-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../backend/trainees.php',
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