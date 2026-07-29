<?php
// create_facilities.php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

include_once '../config.php';

$mod_slug = 'facilities';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Facilities</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto p-4 mt-10 bg-gray-100 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-orange-500 mb-4">Create Facilities</h2>
        <form id="create-facilities-form">
            <div class="mb-4">
                <label for="name" class="block text-gray-900 text-sm font-bold mb-2">Name</label>
                <input type="text" id="name" name="name" class="block w-full p-2 border border-gray-400 rounded-lg focus:outline-none focus:ring-orange-500 focus:border-orange-500">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-900 text-sm font-bold mb-2">Description</label>
                <textarea id="description" name="description" class="block w-full p-2 border border-gray-400 rounded-lg focus:outline-none focus:ring-orange-500 focus:border-orange-500"></textarea>
            </div>
            <div class="mb-4">
                <label for="capacity" class="block text-gray-900 text-sm font-bold mb-2">Capacity</label>
                <input type="number" id="capacity" name="capacity" class="block w-full p-2 border border-gray-400 rounded-lg focus:outline-none focus:ring-orange-500 focus:border-orange-500">
            </div>
            <div class="mb-4">
                <label for="status" class="block text-gray-900 text-sm font-bold mb-2">Status</label>
                <select id="status" name="status" class="block w-full p-2 border border-gray-400 rounded-lg focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <button type="submit" class="bg-orange-500 hover:bg-orange-700 text-gray-100 font-bold py-2 px-4 rounded-lg">Create Facilities</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#create-facilities-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '../backend/facilities.php',
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