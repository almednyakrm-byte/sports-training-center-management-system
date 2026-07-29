<?php
// edit_training-sessions.php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: list_training-sessions.php');
    exit;
}

$id = $_GET['id'];

require_once '../backend/training-sessions.php';
$trainingSession = getTrainingSession($id);

if (!$trainingSession) {
    header('Location: list_training-sessions.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Training Session</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body>
    <div class="max-w-md mx-auto mt-10 p-4 bg-gray-900 rounded-lg shadow-md">
        <h2 class="text-2xl text-orange-500 font-bold mb-4">Edit Training Session</h2>
        <form id="edit-training-session-form">
            <div class="mb-4">
                <label for="name" class="block text-orange-500 text-sm font-bold mb-2">Name</label>
                <input type="text" id="name" name="name" value="<?php echo $trainingSession['name']; ?>" class="block w-full p-2 bg-gray-100 border border-gray-200 rounded-lg focus:outline-none focus:ring-orange-500 focus:border-orange-500">
            </div>
            <div class="mb-4">
                <label for="date" class="block text-orange-500 text-sm font-bold mb-2">Date</label>
                <input type="date" id="date" name="date" value="<?php echo $trainingSession['date']; ?>" class="block w-full p-2 bg-gray-100 border border-gray-200 rounded-lg focus:outline-none focus:ring-orange-500 focus:border-orange-500">
            </div>
            <div class="mb-4">
                <label for="time" class="block text-orange-500 text-sm font-bold mb-2">Time</label>
                <input type="time" id="time" name="time" value="<?php echo $trainingSession['time']; ?>" class="block w-full p-2 bg-gray-100 border border-gray-200 rounded-lg focus:outline-none focus:ring-orange-500 focus:border-orange-500">
            </div>
            <button type="submit" class="w-full p-2 bg-orange-500 text-gray-900 font-bold rounded-lg hover:bg-orange-700 focus:outline-none focus:ring-orange-500 focus:border-orange-500">Update</button>
        </form>
    </div>

    <script>
        const form = document.getElementById('edit-training-session-form');

        form.addEventListener('submit', (e) => {
            e.preventDefault();

            const formData = new FormData(form);
            formData.append('id', <?php echo $id; ?>);

            fetch('../backend/training-sessions.php', {
                method: 'PUT',
                body: formData,
            })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    window.location.href = 'list_training-sessions.php';
                } else {
                    console.error(data.error);
                }
            })
            .catch((error) => {
                console.error(error);
            });
        });
    </script>
</body>
</html>



// ../backend/training-sessions.php
<?php
require_once 'database.php';

function getTrainingSession($id) {
    $db = new Database();
    $query = "SELECT * FROM training_sessions WHERE id = '$id'";
    $result = $db->query($query);
    return $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = $_GET['id'];
    $trainingSession = getTrainingSession($id);
    echo json_encode($trainingSession);
} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    $db = new Database();
    $query = "UPDATE training_sessions SET name = '$name', date = '$date', time = '$time' WHERE id = '$id'";
    $db->query($query);

    if ($db->affected_rows > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to update training session']);
    }
}



// ../backend/database.php
<?php
class Database {
    private $host;
    private $username;
    private $password;
    private $database;

    public function __construct() {
        $this->host = 'your_host';
        $this->username = 'your_username';
        $this->password = 'your_password';
        $this->database = 'your_database';

        $this->connect();
    }

    private function connect() {
        $this->connection = new mysqli($this->host, $this->username, $this->password, $this->database);

        if ($this->connection->connect_error) {
            die("Connection failed: " . $this->connection->connect_error);
        }
    }

    public function query($query) {
        return $this->connection->query($query);
    }

    public function affected_rows() {
        return $this->connection->affected_rows;
    }
}