<?php
// Start the session to store user data
session_start();

// Import the database connection script
require_once 'db.php';

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    // If the user is logged in, return a JSON response with the user data
    $user_data = array(
        'status' => 'logged_in',
        'user_id' => $_SESSION['user_id'],
        'username' => $_SESSION['username']
    );
    header('Content-Type: application/json');
    echo json_encode($user_data);
    exit;
}

// Handle the login request
if (isset($_POST['action']) && $_POST['action'] == 'login') {
    // Check if the username and password are set
    if (isset($_POST['username']) && isset($_POST['password'])) {
        // Sanitize the input fields
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

        // Prepare the SQL query to check the username and password
        $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        // Check if the username exists
        if ($result->num_rows > 0) {
            // Fetch the user data
            $user_data = $result->fetch_assoc();

            // Check if the password is correct
            if (password_verify($password, $user_data['password'])) {
                // If the password is correct, log the user in
                $_SESSION['user_id'] = $user_data['id'];
                $_SESSION['username'] = $user_data['username'];

                // Return a JSON response with the user data
                $user_data = array(
                    'status' => 'logged_in',
                    'user_id' => $_SESSION['user_id'],
                    'username' => $_SESSION['username']
                );
                header('Content-Type: application/json');
                echo json_encode($user_data);
                exit;
            } else {
                // If the password is incorrect, return an error message
                $error = array(
                    'status' => 'error',
                    'message' => 'Invalid password'
                );
                header('Content-Type: application/json');
                echo json_encode($error);
                exit;
            }
        } else {
            // If the username does not exist, return an error message
            $error = array(
                'status' => 'error',
                'message' => 'Invalid username'
            );
            header('Content-Type: application/json');
            echo json_encode($error);
            exit;
        }
    } else {
        // If the username or password is missing, return an error message
        $error = array(
            'status' => 'error',
            'message' => 'Missing username or password'
        );
        header('Content-Type: application/json');
        echo json_encode($error);
        exit;
    }
}

// Handle the registration request
if (isset($_POST['action']) && $_POST['action'] == 'register') {
    // Check if the username, email, and password are set
    if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
        // Sanitize the input fields
        $username = filter_var($_POST['username'], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

        // Check if the username and email are valid
        if (preg_match('/^[a-zA-Z0-9]+$/', $username) && preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) {
            // Prepare the SQL query to check if the username or email already exists
            $stmt = $mysqli->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $result = $stmt->get_result();

            // Check if the username or email already exists
            if ($result->num_rows > 0) {
                // If the username or email already exists, return an error message
                $error = array(
                    'status' => 'error',
                    'message' => 'Username or email already exists'
                );
                header('Content-Type: application/json');
                echo json_encode($error);
                exit;
            } else {
                // If the username and email are valid, hash the password and register the user
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $mysqli->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $username, $email, $hashed_password);
                $stmt->execute();

                // Return a JSON response with the user data
                $user_data = array(
                    'status' => 'registered',
                    'username' => $username
                );
                header('Content-Type: application/json');
                echo json_encode($user_data);
                exit;
            }
        } else {
            // If the username or email is invalid, return an error message
            $error = array(
                'status' => 'error',
                'message' => 'Invalid username or email'
            );
            header('Content-Type: application/json');
            echo json_encode($error);
            exit;
        }
    } else {
        // If the username, email, or password is missing, return an error message
        $error = array(
            'status' => 'error',
            'message' => 'Missing username, email, or password'
        );
        header('Content-Type: application/json');
        echo json_encode($error);
        exit;
    }
}

// Handle the logout request
if (isset($_POST['action']) && $_POST['action'] == 'logout') {
    // Destroy the session to log the user out
    session_destroy();

    // Return a JSON response with the logout status
    $logout = array(
        'status' => 'logged_out'
    );
    header('Content-Type: application/json');
    echo json_encode($logout);
    exit;
}