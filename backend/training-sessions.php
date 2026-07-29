<?php
// Import database connection
require_once 'db.php';

// Initialize database connection
$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get request method
$method = $_SERVER['REQUEST_METHOD'];

// Process request
switch ($method) {
    case 'GET':
        // Validate and sanitize input
        $sessionId = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

        // Check if session id is provided
        if ($sessionId) {
            // SQL query to retrieve a single training session
            $stmt = $pdo->prepare('SELECT * FROM training_sessions WHERE id = :id');
            $stmt->bindParam(':id', $sessionId);
            $stmt->execute();

            // Fetch and return result
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                http_response_code(200);
                header('Content-Type: application/json');
                echo json_encode($result);
            } else {
                http_response_code(404);
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Training session not found']);
            }
        } else {
            // SQL query to retrieve all training sessions
            $stmt = $pdo->prepare('SELECT * FROM training_sessions');
            $stmt->execute();

            // Fetch and return results
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($results);
        }
        break;

    case 'POST':
        // Check if user is admin
        if ($_SESSION['user_role'] !== 'admin') {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Forbidden']);
            exit;
        }

        // Get input data
        $data = json_decode(file_get_contents('php://input'), true);

        // Validate and sanitize input
        $name = filter_var($data['name'], FILTER_SANITIZE_STRING);
        $description = filter_var($data['description'], FILTER_SANITIZE_STRING);
        $date = filter_var($data['date'], FILTER_SANITIZE_STRING);
        $time = filter_var($data['time'], FILTER_SANITIZE_STRING);

        // Check if required fields are provided
        if (!$name || !$description || !$date || !$time) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Invalid request data']);
            exit;
        }

        // SQL query to create a new training session
        $stmt = $pdo->prepare('INSERT INTO training_sessions (name, description, date, time) VALUES (:name, :description, :date, :time)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':time', $time);
        $stmt->execute();

        // Return created training session
        $sessionId = $pdo->lastInsertId();
        $stmt = $pdo->prepare('SELECT * FROM training_sessions WHERE id = :id');
        $stmt->bindParam(':id', $sessionId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode($result);
        break;

    case 'PUT':
        // Check if user is admin
        if ($_SESSION['user_role'] !== 'admin') {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Forbidden']);
            exit;
        }

        // Get input data
        $data = json_decode(file_get_contents('php://input'), true);

        // Validate and sanitize input
        $sessionId = filter_var($data['id'], FILTER_SANITIZE_NUMBER_INT);
        $name = filter_var($data['name'], FILTER_SANITIZE_STRING);
        $description = filter_var($data['description'], FILTER_SANITIZE_STRING);
        $date = filter_var($data['date'], FILTER_SANITIZE_STRING);
        $time = filter_var($data['time'], FILTER_SANITIZE_STRING);

        // Check if required fields are provided
        if (!$sessionId || !$name || !$description || !$date || !$time) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Invalid request data']);
            exit;
        }

        // SQL query to update a training session
        $stmt = $pdo->prepare('UPDATE training_sessions SET name = :name, description = :description, date = :date, time = :time WHERE id = :id');
        $stmt->bindParam(':id', $sessionId);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':time', $time);
        $stmt->execute();

        // Return updated training session
        $stmt = $pdo->prepare('SELECT * FROM training_sessions WHERE id = :id');
        $stmt->bindParam(':id', $sessionId);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($result);
        break;

    case 'DELETE':
        // Check if user is admin
        if ($_SESSION['user_role'] !== 'admin') {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Forbidden']);
            exit;
        }

        // Get input data
        $sessionId = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

        // Check if session id is provided
        if (!$sessionId) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Invalid request data']);
            exit;
        }

        // SQL query to delete a training session
        $stmt = $pdo->prepare('DELETE FROM training_sessions WHERE id = :id');
        $stmt->bindParam(':id', $sessionId);
        $stmt->execute();

        // Return success response
        http_response_code(204);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Training session deleted successfully']);
        break;

    default:
        http_response_code(405);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Method not allowed']);
        break;
}

// Close database connection
$pdo = null;