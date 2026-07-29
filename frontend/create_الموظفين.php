<?php
// Start session
session_start();

// Session validation
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
include '../backend/db.php';

// Define module slug
$mod_slug = 'الموظفين';

// Define page title
$page_title = 'Create ' . $mod_slug;

// Include header
include 'header.php';
?>

<!-- Main content -->
<main class="md:flex flex-wrap justify-center p-4">
    <div class="md:w-2/3 lg:w-1/2 xl:w-1/3 p-6 bg-white rounded-lg shadow-md">
        <h2 class="text-2xl text-emerald-600 mb-4">Create <?php echo $mod_slug; ?></h2>
        <form id="create-form">
            <div class="mb-4">
                <label for="name" class="block text-sm text-gray-600 mb-2">Name</label>
                <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-lg focus:outline-none focus:ring-emerald-600 focus:border-emerald-600" required>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm text-gray-600 mb-2">Email</label>
                <input type="email" id="email" name="email" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-lg focus:outline-none focus:ring-emerald-600 focus:border-emerald-600" required>
            </div>
            <div class="mb-4">
                <label for="phone" class="block text-sm text-gray-600 mb-2">Phone</label>
                <input type="text" id="phone" name="phone" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-lg focus:outline-none focus:ring-emerald-600 focus:border-emerald-600" required>
            </div>
            <div class="mb-4">
                <label for="address" class="block text-sm text-gray-600 mb-2">Address</label>
                <textarea id="address" name="address" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-lg focus:outline-none focus:ring-emerald-600 focus:border-emerald-600" required></textarea>
            </div>
            <button type="submit" class="w-full px-4 py-2 text-sm text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 focus:outline-none focus:ring-emerald-600 focus:ring-offset-emerald-200">Create</button>
        </form>
    </div>
</main>

<!-- Include footer -->
<?php include 'footer.php'; ?>

<!-- AJAX JavaScript -->
<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/<?php echo $mod_slug; ?>.php',
                data: $(this).serialize(),
                success: function(data) {
                    window.location.href = 'list_<?php echo $mod_slug; ?>.php';
                }
            });
        });
    });
</script>