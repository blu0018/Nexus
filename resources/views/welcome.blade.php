<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 350px;
            padding: 20px;
            text-align: center;
        }

        .login-container h2 {
            margin: 0 0 20px;
        }

        .login-container form {
            display: flex;
            flex-direction: column;
        }

        .login-container label, .login-container input {
            margin: 10px 0;
        }

        .login-container input {
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        .login-container button {
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 12px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .login-container button:hover {
            background-color: #0056b3;
        }

        .login-container .sign-in-options {
            margin: 20px 0;
        }

        .login-container .sign-in-options a {
            text-decoration: none;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 12px 20px;
            margin: 5px;
            display: inline-block;
        }

        .signup-link {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form action="{{ route('login') }}" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Login</button>
        </form>
        
        <div class="sign-in-options">
            <a href="#" class="sign-in-option">Sign in with Google</a>
            <a href="#" class="sign-in-option">Sign in with Facebook</a>
            <!-- Add more sign-in options as needed -->
        </div>
        
        <div class="signup-link">
            <p>Don't have an account? <a href="{{ route('signup') }}">Sign up</a></p>
        </div>
    </div>
</body>
</html>
