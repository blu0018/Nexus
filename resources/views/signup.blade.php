<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup Form</title>
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

        .signup-container {
            background-color: #fff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 350px;
            padding: 20px;
            text-align: center;
        }

        .signup-container h2 {
            margin: 0 0 20px;
        }

        .signup-container form {
            display: flex;
            flex-direction: column;
        }

        .signup-container label, .signup-container input {
            margin: 10px 0;
        }

        .signup-container input {
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
        }

        .signup-container button {
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 12px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .signup-container button:hover {
            background-color: #0056b3;
        }

        .signup-container .login-link {
            margin-top: 20px;
        }

        .login-link a {
            text-decoration: none;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 12px 20px;
            display: inline-block;
            transition: background-color 0.2s;
        }

        .login-link a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="signup-container">
        <h2>Signup</h2>
        <form action="{{ route('signup') }}" method="post">
            <label for="first-name">First Name:</label>
            <input type="text" id="first-name" name="first-name" required>
            
            <label for="last-name">Last Name:</label>
            <input type="text" id="last-name" name="last-name" required>
            
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            
            <label for="confirm-password">Confirm Password:</label>
            <input type="password" id="confirm-password" name="confirm-password" required>
            
            <button type="submit">Signup</button>
        </form>
        
        <div class="login-link">
            <p>Already have an account? <a href="{{ route('login') }}">Login</a></p>
        </div>
    </div>
</body>
</html>
