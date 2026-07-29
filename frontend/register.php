<?php
// Initialize session
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="h-screen bg-emerald-600 flex justify-center items-center">
    <div class="bg-teal-500 p-10 rounded-lg shadow-lg">
        <h1 class="text-3xl text-white font-bold mb-4">Register</h1>
        <form id="register-form">
            <div class="mb-4">
                <label for="username" class="block text-white text-sm font-bold mb-2">Username</label>
                <input type="text" id="username" name="username" pattern="[A-Za-z\u0600-\u06FF0-9\s]+" required class="block w-full p-2 pl-10 text-sm text-gray-700 rounded-lg shadow-sm border-teal-300 focus:ring-teal-500 focus:border-teal-500">
                <div class="text-red-500 text-xs" id="username-error"></div>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-white text-sm font-bold mb-2">Email</label>
                <input type="email" id="email" name="email" required class="block w-full p-2 pl-10 text-sm text-gray-700 rounded-lg shadow-sm border-teal-300 focus:ring-teal-500 focus:border-teal-500">
                <div class="text-red-500 text-xs" id="email-error"></div>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-white text-sm font-bold mb-2">Password</label>
                <input type="password" id="password" name="password" required class="block w-full p-2 pl-10 text-sm text-gray-700 rounded-lg shadow-sm border-teal-300 focus:ring-teal-500 focus:border-teal-500">
                <div class="text-red-500 text-xs" id="password-error"></div>
            </div>
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2 px-4 rounded">Register</button>
        </form>
        <div class="text-green-500 text-xs" id="success-message"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#register-form').submit(function(e) {
                e.preventDefault();
                var username = $('#username').val();
                var email = $('#email').val();
                var password = $('#password').val();

                if (username === '' || email === '' || password === '') {
                    if (username === '') {
                        $('#username-error').text('Username is required');
                    } else {
                        $('#username-error').text('');
                    }
                    if (email === '') {
                        $('#email-error').text('Email is required');
                    } else {
                        $('#email-error').text('');
                    }
                    if (password === '') {
                        $('#password-error').text('Password is required');
                    } else {
                        $('#password-error').text('');
                    }
                } else {
                    $.ajax({
                        type: 'POST',
                        url: '../backend/auth.php?action=register',
                        data: {
                            username: username,
                            email: email,
                            password: password
                        },
                        success: function(response) {
                            if (response === 'success') {
                                $('#success-message').text('Registration successful');
                                $('#username').val('');
                                $('#email').val('');
                                $('#password').val('');
                            } else {
                                $('#success-message').text(response);
                            }
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>