<?php
// Import database connection file
require_once 'db.php';

// Initialize database connection
$dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
    $pdo = new PDO($dsn, DB_USER, DB_PASSWORD, $options);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Connection failed: ' . $e->getMessage()]);
    exit;
}

// Function to validate user role
function validateUserRole($role) {
    // For demonstration purposes, assume 'admin' role is required for edits/deletions
    if ($role !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden: Only admins can perform this action']);
        exit;
    }
}

// Function to authenticate user
function authenticateUser() {
    // For demonstration purposes, assume a 'token' is sent in the request headers
    $token = $_SERVER['HTTP_TOKEN'] ?? null;
    if (!$token) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized: Token is required']);
        exit;
    }
    // Validate token (e.g., using a JWT library)
    // ...
}

// Handle GET requests
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    authenticateUser();
    $sql = 'SELECT * FROM الموظفين';
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($result);
}

// Handle POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    authenticateUser();
    validateUserRole($_SERVER['HTTP_ROLE'] ?? null);
    $data = json_decode(file_get_contents('php://input'), true);
    if (!$data) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }
    $sql = 'INSERT INTO الموظفين (name, email, phone) VALUES (:name, :email, :phone)';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $data['name']);
    $stmt->bindParam(':email', $data['email']);
    $stmt->bindParam(':phone', $data['phone']);
    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(['message' => 'Employee created successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to create employee']);
    }
}

// Handle PUT requests
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    authenticateUser();
    validateUserRole($_SERVER['HTTP_ROLE'] ?? null);
    $data = json_decode(file_get_contents('php://input'), true);
    if (!$data) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }
    $id = $data['id'];
    $sql = 'UPDATE الموظفين SET name = :name, email = :email, phone = :phone WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $data['name']);
    $stmt->bindParam(':email', $data['email']);
    $stmt->bindParam(':phone', $data['phone']);
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(['message' => 'Employee updated successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to update employee']);
    }
}

// Handle DELETE requests
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    authenticateUser();
    validateUserRole($_SERVER['HTTP_ROLE'] ?? null);
    $id = $_GET['id'];
    if (!$id) {
        http_response_code(400);
        echo json_encode(['error' => 'Employee ID is required']);
        exit;
    }
    $sql = 'DELETE FROM الموظفين WHERE id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id);
    if ($stmt->execute()) {
        http_response_code(200);
        echo json_encode(['message' => 'Employee deleted successfully']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to delete employee']);
    }
}