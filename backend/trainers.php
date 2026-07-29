<?php
// Import database connection
require_once 'db.php';

// Initialize database connection
$dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$pdo = new PDO($dsn, DB_USER, DB_PASSWORD, $options);

// Function to validate and sanitize input data
function validateInput($data) {
    // Validate and sanitize input data
    $validatedData = [];
    foreach ($data as $key => $value) {
        $validatedData[$key] = trim(htmlspecialchars($value));
    }
    return $validatedData;
}

// Function to check user role authorization
function checkUserRole($role) {
    // Check if user is logged in and has the required role
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== $role) {
        http_response_code(401);
        echo json_encode(['message' => 'Unauthorized access']);
        exit;
    }
}

// Handle GET requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['message' => 'Unauthorized access']);
        exit;
    }

    // Retrieve trainers data
    $stmt = $pdo->prepare('SELECT * FROM trainers');
    $stmt->execute();
    $trainers = $stmt->fetchAll();

    // Return trainers data in JSON format
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($trainers);
}

// Handle POST requests
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['message' => 'Unauthorized access']);
        exit;
    }

    // Get input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input data
    $validatedData = validateInput($data);

    // Check if required fields are present
    if (!isset($validatedData['name']) || !isset($validatedData['email'])) {
        http_response_code(400);
        echo json_encode(['message' => 'Missing required fields']);
        exit;
    }

    // Insert new trainer data
    $stmt = $pdo->prepare('INSERT INTO trainers (name, email) VALUES (:name, :email)');
    $stmt->bindParam(':name', $validatedData['name']);
    $stmt->bindParam(':email', $validatedData['email']);
    $stmt->execute();

    // Return success message in JSON format
    http_response_code(201);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Trainer created successfully']);
}

// Handle PUT requests
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Check if user is logged in and has admin role
    checkUserRole('admin');

    // Get input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input data
    $validatedData = validateInput($data);

    // Check if required fields are present
    if (!isset($validatedData['id']) || !isset($validatedData['name']) || !isset($validatedData['email'])) {
        http_response_code(400);
        echo json_encode(['message' => 'Missing required fields']);
        exit;
    }

    // Update existing trainer data
    $stmt = $pdo->prepare('UPDATE trainers SET name = :name, email = :email WHERE id = :id');
    $stmt->bindParam(':id', $validatedData['id']);
    $stmt->bindParam(':name', $validatedData['name']);
    $stmt->bindParam(':email', $validatedData['email']);
    $stmt->execute();

    // Return success message in JSON format
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Trainer updated successfully']);
}

// Handle DELETE requests
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if user is logged in and has admin role
    checkUserRole('admin');

    // Get input data
    $data = json_decode(file_get_contents('php://input'), true);

    // Validate and sanitize input data
    $validatedData = validateInput($data);

    // Check if required fields are present
    if (!isset($validatedData['id'])) {
        http_response_code(400);
        echo json_encode(['message' => 'Missing required fields']);
        exit;
    }

    // Delete existing trainer data
    $stmt = $pdo->prepare('DELETE FROM trainers WHERE id = :id');
    $stmt->bindParam(':id', $validatedData['id']);
    $stmt->execute();

    // Return success message in JSON format
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['message' => 'Trainer deleted successfully']);
}

// Handle invalid request methods
else {
    http_response_code(405);
    echo json_encode(['message' => 'Method not allowed']);
}