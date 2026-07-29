<?php
// edit_مراكز-التدريب.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: list_مراكز-التدريب.php');
    exit;
}

$id = $_GET['id'];

?>

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل مركز التدريب</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mx-auto p-4 pt-6 mt-10 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl text-emerald-600 mb-4">تعديل مركز التدريب</h2>
        <form id="edit-form">
            <div class="mb-4">
                <label for="name" class="block text-sm text-gray-600 mb-2">اسم المركز</label>
                <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-lg focus:outline-none focus:ring-emerald-600 focus:border-emerald-600">
            </div>
            <div class="mb-4">
                <label for="address" class="block text-sm text-gray-600 mb-2">عنوان المركز</label>
                <input type="text" id="address" name="address" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-lg focus:outline-none focus:ring-emerald-600 focus:border-emerald-600">
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-sm text-gray-600 mb-2">رقم الهاتف</label>
                <input type="text" id="phone" name="phone" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-lg focus:outline-none focus:ring-emerald-600 focus:border-emerald-600">
            </div>
            <button type="submit" class="bg-emerald-600 hover:bg-teal-500 text-white font-bold py-2 px-4 rounded-lg">حفظ التعديلات</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $.ajax({
                type: 'GET',
                url: '../backend/مراكز-التدريب.php?id=<?php echo $id; ?>',
                dataType: 'json',
                success: function(data) {
                    $('#name').val(data.name);
                    $('#address').val(data.address);
                    $('#phone').val(data.phone);
                }
            });

            $('#edit-form').submit(function(e) {
                e.preventDefault();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/مراكز-التدريب.php',
                    data: {
                        id: '<?php echo $id; ?>',
                        name: $('#name').val(),
                        address: $('#address').val(),
                        phone: $('#phone').val()
                    },
                    success: function(data) {
                        window.location.href = 'list_مراكز-التدريب.php';
                    }
                });
            });
        });
    </script>
</body>
</html>