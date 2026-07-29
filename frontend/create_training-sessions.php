<?php
// create_training-sessions.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

include_once '../config.php';
include_once '../backend/functions.php';

$mod_slug = 'training-sessions';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $location = $_POST['location'];

    $query = "INSERT INTO training_sessions (title, description, date, time, location) VALUES ('$title', '$description', '$date', '$time', '$location')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        // Redirect to list page
        header('Location: list_' . $mod_slug . '.php');
        exit;
    } else {
        echo 'Error: ' . mysqli_error($conn);
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Training Session</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto mt-10 bg-gray-900 p-8 rounded-lg shadow-lg">
        <h2 class="text-3xl text-orange-500 font-bold mb-4">Create Training Session</h2>
        <form id="create-training-session-form">
            <div class="mb-4">
                <label for="title" class="block text-gray-200 text-sm font-bold mb-2">Title</label>
                <input type="text" id="title" name="title" class="bg-gray-800 text-gray-200 border border-gray-700 rounded-lg py-2 px-4 w-full">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-200 text-sm font-bold mb-2">Description</label>
                <textarea id="description" name="description" class="bg-gray-800 text-gray-200 border border-gray-700 rounded-lg py-2 px-4 w-full h-32"></textarea>
            </div>
            <div class="mb-4">
                <label for="date" class="block text-gray-200 text-sm font-bold mb-2">Date</label>
                <input type="date" id="date" name="date" class="bg-gray-800 text-gray-200 border border-gray-700 rounded-lg py-2 px-4 w-full">
            </div>
            <div class="mb-4">
                <label for="time" class="block text-gray-200 text-sm font-bold mb-2">Time</label>
                <input type="time" id="time" name="time" class="bg-gray-800 text-gray-200 border border-gray-700 rounded-lg py-2 px-4 w-full">
            </div>
            <div class="mb-4">
                <label for="location" class="block text-gray-200 text-sm font-bold mb-2">Location</label>
                <input type="text" id="location" name="location" class="bg-gray-800 text-gray-200 border border-gray-700 rounded-lg py-2 px-4 w-full">
            </div>
            <button type="submit" class="bg-orange-500 hover:bg-orange-700 text-gray-200 font-bold py-2 px-4 rounded-lg w-full">Create Training Session</button>
        </form>
    </div>

    <script>
        const form = document.getElementById('create-training-session-form');
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            fetch('../backend/training-sessions.php', {
                method: 'POST',
                body: formData,
            })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    window.location.href = 'list_<?php echo $mod_slug; ?>.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch((error) => console.error(error));
        });
    </script>
</body>
</html>