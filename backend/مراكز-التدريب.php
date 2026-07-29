<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Define table name
$table_name = 'مراكز التدريب';

// Define columns
$columns = [
    'id' => 'id',
    'name' => 'name',
    'address' => 'address',
    'phone' => 'phone',
    'email' => 'email',
];

// Define validation rules
$validation_rules = [
    'name' => 'required',
    'address' => 'required',
    'phone' => 'required',
    'email' => 'required|email',
];

// Validate input data
foreach ($validation_rules as $field => $rules) {
    if (isset($input[$field])) {
        $input[$field] = trim($input[$field]);
        if (empty($input[$field])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input: ' . $field . ' is required']);
            exit;
        }
    }
}

// Sanitize input data
foreach ($input as $field => $value) {
    if (isset($columns[$field])) {
        $input[$field] = $pdo->quote($value);
    }
}

// Handle GET request
if (isset($_GET['id'])) {
    // Get single record
    $stmt = $pdo->prepare("SELECT * FROM $table_name WHERE id = :id");
    $stmt->bindParam(':id', $_GET['id']);
    $stmt->execute();
    $record = $stmt->fetch();
    if ($record) {
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($record);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Record not found']);
    }
} elseif (isset($_GET['all'])) {
    // Get all records
    $stmt = $pdo->prepare("SELECT * FROM $table_name");
    $stmt->execute();
    $records = $stmt->fetchAll();
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode($records);
} else {
    // Handle POST, PUT, DELETE requests
    if (isset($_SERVER['REQUEST_METHOD']) && in_array($_SERVER['REQUEST_METHOD'], ['POST', 'PUT', 'DELETE'])) {
        // Check if user is admin
        if ($_SESSION['role'] !== 'admin' && (isset($_SERVER['REQUEST_METHOD']) && in_array($_SERVER['REQUEST_METHOD'], ['PUT', 'DELETE']))) {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden access']);
            exit;
        }

        // Handle POST request
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Insert new record
            $stmt = $pdo->prepare("INSERT INTO $table_name (name, address, phone, email) VALUES (:name, :address, :phone, :email)");
            $stmt->bindParam(':name', $input['name']);
            $stmt->bindParam(':address', $input['address']);
            $stmt->bindParam(':phone', $input['phone']);
            $stmt->bindParam(':email', $input['email']);
            $stmt->execute();
            http_response_code(201);
            echo json_encode(['message' => 'Record created successfully']);
        }

        // Handle PUT request
        elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
            // Update existing record
            $stmt = $pdo->prepare("UPDATE $table_name SET name = :name, address = :address, phone = :phone, email = :email WHERE id = :id");
            $stmt->bindParam(':id', $_GET['id']);
            $stmt->bindParam(':name', $input['name']);
            $stmt->bindParam(':address', $input['address']);
            $stmt->bindParam(':phone', $input['phone']);
            $stmt->bindParam(':email', $input['email']);
            $stmt->execute();
            http_response_code(200);
            echo json_encode(['message' => 'Record updated successfully']);
        }

        // Handle DELETE request
        elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
            // Delete existing record
            $stmt = $pdo->prepare("DELETE FROM $table_name WHERE id = :id");
            $stmt->bindParam(':id', $_GET['id']);
            $stmt->execute();
            http_response_code(200);
            echo json_encode(['message' => 'Record deleted successfully']);
        }
    }
}