<?php
// Session validation
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Get id from URL
$id = $_GET['id'];

// Include database connection
include '../backend/db.php';

// Check if id is valid
$query = "SELECT * FROM الموظفين WHERE id = '$id'";
$result = mysqli_query($conn, $query);
if (mysqli_num_rows($result) == 0) {
    header('Location: list_الموظفين.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل الموظفين</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4 pt-6 mt-10 bg-white rounded-lg shadow-md">
        <h2 class="text-3xl text-emerald-600 font-bold mb-4">تعديل الموظفين</h2>
        <form id="edit-form">
            <div class="mb-4">
                <label for="name" class="block text-emerald-600 text-sm font-bold mb-2">اسم الموظف</label>
                <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-emerald-600 rounded-lg focus:outline-none focus:ring-emerald-600 focus:border-emerald-600">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-emerald-600 text-sm font-bold mb-2">البريد الإلكتروني</label>
                <input type="email" id="email" name="email" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-emerald-600 rounded-lg focus:outline-none focus:ring-emerald-600 focus:border-emerald-600">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-emerald-600 text-sm font-bold mb-2">رقم الهاتف</label>
                <input type="text" id="phone" name="phone" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-emerald-600 rounded-lg focus:outline-none focus:ring-emerald-600 focus:border-emerald-600">
            </div>
            <button type="submit" class="bg-teal-500 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded-lg">حفظ التعديلات</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            // Fetch existing record details
            $.ajax({
                type: 'GET',
                url: '../backend/الموظفين.php?id=<?php echo $id; ?>',
                dataType: 'json',
                success: function(data) {
                    // Populate form fields
                    $('#name').val(data.name);
                    $('#email').val(data.email);
                    $('#phone').val(data.phone);
                }
            });

            // Submit form using AJAX
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/الموظفين.php',
                    data: {
                        id: '<?php echo $id; ?>',
                        name: $('#name').val(),
                        email: $('#email').val(),
                        phone: $('#phone').val()
                    },
                    success: function(data) {
                        // Redirect to list page
                        window.location.href = 'list_الموظفين.php';
                    }
                });
            });
        });
    </script>
</body>
</html>