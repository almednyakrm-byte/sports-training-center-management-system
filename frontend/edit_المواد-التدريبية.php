<?php
// Session validation
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Include database connection
require_once '../backend/db.php';

// Check if id is valid
$query = "SELECT * FROM المواد_التدريبية WHERE id = '$id'";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) == 0) {
    header('Location: list_المواد-التدريبية.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل المواد التدريبية</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4 pt-6 mt-10 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl font-bold text-emerald-600 mb-4">تعديل المواد التدريبية</h2>
        <form id="edit-form">
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">اسم المادة التدريبية</label>
                <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-md focus:outline-none focus:ring-emerald-600 focus:border-emerald-600">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">وصف المادة التدريبية</label>
                <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-md focus:outline-none focus:ring-emerald-600 focus:border-emerald-600"></textarea>
            </div>
            <button type="submit" class="py-2 px-4 bg-emerald-600 text-white rounded-md hover:bg-emerald-700 focus:outline-none focus:ring-emerald-600 focus:border-emerald-600">حفظ</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            // Fetch existing record details
            $.ajax({
                type: 'GET',
                url: '../backend/المواد-التدريبية.php?id=<?php echo $id; ?>',
                dataType: 'json',
                success: function(data) {
                    // Populate form fields
                    $('#name').val(data.name);
                    $('#description').val(data.description);
                }
            });

            // Submit form
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/المواد-التدريبية.php',
                    data: {
                        id: '<?php echo $id; ?>',
                        name: $('#name').val(),
                        description: $('#description').val()
                    },
                    success: function() {
                        window.location.href = 'list_المواد-التدريبية.php';
                    }
                });
            });
        });
    </script>
</body>
</html>