<?php
// Initialize session
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
</head>
<body class="h-screen bg-emerald-600 flex justify-center items-center">
    <div class="glassmorphic-card w-80 p-6 rounded-2xl bg-white bg-opacity-20 backdrop-filter backdrop-blur-md">
        <h2 class="text-2xl text-teal-500 font-bold mb-4">Login</h2>
        <form id="login-form">
            <div class="mb-4">
                <label for="username" class="block text-teal-500 font-bold mb-2">Username</label>
                <input type="text" id="username" name="username" required pattern="[A-Za-z\u0600-\u06FF0-9\s]+" class="block w-full p-2 rounded-lg bg-transparent border border-teal-500 text-teal-500">
            </div>
            <div class="mb-4">
                <label for="password" class="block text-teal-500 font-bold mb-2">Password</label>
                <input type="password" id="password" name="password" required class="block w-full p-2 rounded-lg bg-transparent border border-teal-500 text-teal-500">
            </div>
            <button type="submit" class="w-full p-2 rounded-lg bg-teal-500 text-emerald-600 font-bold hover:bg-teal-600">Login</button>
        </form>
        <p class="text-teal-500 mt-4">Don't have an account? <a href="register.php" class="text-emerald-600 hover:text-emerald-700">Register here</a></p>
        <div id="error-message" class="text-red-500 mt-4"></div>
    </div>

    <script>
        const loginForm = document.getElementById('login-form');
        const errorMessage = document.getElementById('error-message');

        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const username = document.getElementById('username').value;
            const password = document.getElementById('password').value;

            try {
                const response = await fetch('../backend/auth.php?action=login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ username, password })
                });

                const data = await response.json();
                if (data.success) {
                    window.location.href = 'dashboard.php';
                } else {
                    errorMessage.textContent = data.message;
                }
            } catch (error) {
                errorMessage.textContent = 'An error occurred: ' + error.message;
            }
        });
    </script>
</body>
</html>