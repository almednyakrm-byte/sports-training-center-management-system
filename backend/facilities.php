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

// Handle GET requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate and sanitize input
    $facilityId = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);

    // SQL query structure
    if ($facilityId) {
        $stmt = $pdo->prepare('SELECT * FROM facilities WHERE id = :id');
        $stmt->bindParam(':id', $facilityId);
    } else {
        $stmt = $pdo->prepare('SELECT * FROM facilities');
    }

    // Execute query
    $stmt->execute();

    // Output processing
    $facilities = $stmt->fetchAll(PDO::FETCH_ASSOC);
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($facilities);
}

// Handle POST requests
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Read input
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $name = filter_var($input['name'] ?? null, FILTER_SANITIZE_STRING);
    $description = filter_var($input['description'] ?? null, FILTER_SANITIZE_STRING);

    // Check for required fields
    if (!$name || !$description) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // SQL query structure
    $stmt = $pdo->prepare('INSERT INTO facilities (name, description) VALUES (:name, :description)');
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);

    // Execute query
    if ($stmt->execute()) {
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Facility created successfully']);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Failed to create facility']);
    }
}

// Handle PUT requests
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Read input
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $facilityId = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT);
    $name = filter_var($input['name'] ?? null, FILTER_SANITIZE_STRING);
    $description = filter_var($input['description'] ?? null, FILTER_SANITIZE_STRING);

    // Check for required fields
    if (!$facilityId || (!$name && !$description)) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // SQL query structure
    $stmt = $pdo->prepare('UPDATE facilities SET name = COALESCE(:name, name), description = COALESCE(:description, description) WHERE id = :id');
    $stmt->bindParam(':id', $facilityId);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':description', $description);

    // Execute query
    if ($stmt->execute()) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Facility updated successfully']);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Failed to update facility']);
    }
}

// Handle DELETE requests
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if user is admin
    if ($userRole !== 'admin') {
        http_response_code(403);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }

    // Read input
    $input = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input
    $facilityId = filter_var($input['id'] ?? null, FILTER_VALIDATE_INT);

    // Check for required fields
    if (!$facilityId) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Invalid request']);
        exit;
    }

    // SQL query structure
    $stmt = $pdo->prepare('DELETE FROM facilities WHERE id = :id');
    $stmt->bindParam(':id', $facilityId);

    // Execute query
    if ($stmt->execute()) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Facility deleted successfully']);
    } else {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Failed to delete facility']);
    }
}

// Handle invalid requests
else {
    http_response_code(405);
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Method not allowed']);
}