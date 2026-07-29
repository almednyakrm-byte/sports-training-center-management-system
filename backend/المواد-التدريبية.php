<?php
// Import database connection
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get user role
$userRole = $_SESSION['user_role'];

// Handle CRUD operations
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate and sanitize input
    $moduleId = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

    // SQL query structure: Select all or by ID
    $stmt = $pdo->prepare('SELECT * FROM المواد_التدريبية WHERE id = :id OR :id IS NULL');
    $stmt->bindParam(':id', $moduleId);
    $stmt->execute();

    // Output processing
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($rows);

} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $data = json_decode(file_get_contents('php://input'), true);
    $name = filter_var($data['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($data['description'], FILTER_SANITIZE_STRING);

    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // SQL query structure: Insert
    $stmt = $pdo->prepare('INSERT INTO المواد_التدريبية (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Output processing
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Created successfully']);

} elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Validate and sanitize input
    $data = json_decode(file_get_contents('php://input'), true);
    $id = filter_var($data['id'], FILTER_SANITIZE_NUMBER_INT);
    $name = filter_var($data['name'], FILTER_SANITIZE_STRING);
    $description = filter_var($data['description'], FILTER_SANITIZE_STRING);

    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // SQL query structure: Update
    $stmt = $pdo->prepare('UPDATE المواد_التدريبية SET name = :name, description = :description WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);
    $stmt->execute();

    // Output processing
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Updated successfully']);

} elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Validate and sanitize input
    $data = json_decode(file_get_contents('php://input'), true);
    $id = filter_var($data['id'], FILTER_SANITIZE_NUMBER_INT);

    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // SQL query structure: Delete
    $stmt = $pdo->prepare('DELETE FROM المواد_التدريبية WHERE id = :id');
    $stmt->bindParam(':id', $id);
    $stmt->execute();

    // Output processing
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Deleted successfully']);
}