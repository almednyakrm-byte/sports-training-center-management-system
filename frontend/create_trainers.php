<?php
// create_trainers.php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

include_once '../config.php';
include_once '../backend/connection.php';

$mod_slug = 'trainers';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Trainers</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto p-4 mt-10 bg-gray-100 rounded-lg shadow-md">
        <h2 class="text-2xl text-orange-500 font-bold mb-4">Create Trainers</h2>
        <form id="create-trainers-form">
            <div class="mb-4">
                <label for="name" class="block text-gray-900 text-sm font-bold mb-2">Name</label>
                <input type="text" id="name" name="name" class="block w-full p-2 border border-gray-400 rounded-lg focus:outline-none focus:ring-orange-500 focus:border-orange-500" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-900 text-sm font-bold mb-2">Email</label>
                <input type="email" id="email" name="email" class="block w-full p-2 border border-gray-400 rounded-lg focus:outline-none focus:ring-orange-500 focus:border-orange-500" required>
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-gray-900 text-sm font-bold mb-2">Phone</label>
                <input type="text" id="phone" name="phone" class="block w-full p-2 border border-gray-400 rounded-lg focus:outline-none focus:ring-orange-500 focus:border-orange-500" required>
            </div>
            <div class="mb-4">
                <label for="address" class="block text-gray-900 text-sm font-bold mb-2">Address</label>
                <textarea id="address" name="address" class="block w-full p-2 border border-gray-400 rounded-lg focus:outline-none focus:ring-orange-500 focus:border-orange-500" required></textarea>
            </div>
            <button type="submit" class="bg-orange-500 hover:bg-orange-700 text-gray-100 font-bold py-2 px-4 rounded-lg">Create</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-trainers-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../backend/trainers.php',
                    data: $(this).serialize(),
                    success: function(data) {
                        window.location.href = 'list_<?= $mod_slug ?>.php';
                    }
                });
            });
        });
    </script>
</body>
</html>