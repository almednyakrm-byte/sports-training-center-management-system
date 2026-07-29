<?php
// Import database connection
require_once 'db.php';

// Initialize database connection
$pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASSWORD);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Function to validate and sanitize input data
function validateInput($data) {
    // Validate and sanitize input data
    $validatedData = [];
    foreach ($data as $key => $value) {
        $validatedData[$key] = trim(htmlspecialchars($value));
    }
    return $validatedData;
}

// Handle GET requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Validate user role
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin' && $_SESSION['user_role'] !== 'user') {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Retrieve all trainees
    $stmt = $pdo->prepare('SELECT * FROM trainees');
    $stmt->execute();
    $trainees = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return trainees in JSON format
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($trainees);
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate user role
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin' && $_SESSION['user_role'] !== 'user') {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Get input data
    $inputData = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input data
    $validatedData = validateInput($inputData);

    // Check for required fields
    if (!isset($validatedData['name']) || !isset($validatedData['email'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Name and email are required']);
        exit;
    }

    // Insert new trainee
    $stmt = $pdo->prepare('INSERT INTO trainees (name, email) VALUES (:name, :email)');
    $stmt->bindParam(':name', $validatedData['name']);
    $stmt->bindParam(':email', $validatedData['email']);
    $stmt->execute();

    // Return created trainee in JSON format
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Trainee created successfully']);
}

// Handle PUT requests
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Validate user role
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Get input data
    $inputData = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input data
    $validatedData = validateInput($inputData);

    // Check for required fields
    if (!isset($validatedData['id']) || !isset($validatedData['name']) || !isset($validatedData['email'])) {
        http_response_code(400);
        echo json_encode(['error' => 'ID, name, and email are required']);
        exit;
    }

    // Update existing trainee
    $stmt = $pdo->prepare('UPDATE trainees SET name = :name, email = :email WHERE id = :id');
    $stmt->bindParam(':id', $validatedData['id']);
    $stmt->bindParam(':name', $validatedData['name']);
    $stmt->bindParam(':email', $validatedData['email']);
    $stmt->execute();

    // Return updated trainee in JSON format
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Trainee updated successfully']);
}

// Handle DELETE requests
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Validate user role
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    // Get input data
    $inputData = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input data
    $validatedData = validateInput($inputData);

    // Check for required fields
    if (!isset($validatedData['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'ID is required']);
        exit;
    }

    // Delete existing trainee
    $stmt = $pdo->prepare('DELETE FROM trainees WHERE id = :id');
    $stmt->bindParam(':id', $validatedData['id']);
    $stmt->execute();

    // Return deleted trainee in JSON format
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Trainee deleted successfully']);
}