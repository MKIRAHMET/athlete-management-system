<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="style.css">
    <title>Login</title>
    <style>
       body {
            margin: 0;
            font-family: 'Arial', sans-serif;
            background-color: black; /* Change background color to black */
            color: white; /* Change text color to white */
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Adjusted to use viewport height */
        }

        .centered-form {
            text-align: center;
            max-width: 300px; /* Adjust the maximum width as needed */
            width: 100%;
            padding: 20px;
            border: 1px solid red; /* Change border color to red */
            border-radius: 5px;
            background-color: black; /* Change background color to black */
        }

        .centered-form form input[type="text"],
        .centered-form form input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
            box-sizing: border-box;
            border: 2px solid red; /* Change input border color to red */
            border-radius: 5px;
            background-color: black; /* Change input background color to black */
            color: red; /* Change input text color to red */
        }

        .centered-form form input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            box-sizing: border-box;
            border: none;
            border-radius: 5px;
            background-color: red; /* Change submit button background color to red */
            color: white; /* Change submit button text color to white */
            cursor: pointer;
        }

        .centered-form form input[type="submit"]:hover {
            background-color: darkred; /* Darken the background color on hover */
        }

        .logo {
            text-align: center;
            padding: 20px;
        }

        .logo img {
            width: 200px; /* Adjust the width of your logo */
            height: auto;
        }

        /* Media query for smaller screens (e.g., smartphones) */
        @media screen and (max-width: 768px) {
            .logo img {
                width: 150px; /* Adjusted width for smaller screens */
            }
        }
    </style>
</head>
<body>
    <?php
    // Display funny message if present in the URL
    if (isset($_GET['msg'])) {
        $funnyMessage = $_GET['msg'];
        echo '<p style="color: red; text-align: center;">' . $funnyMessage . '</p>';
    }
    ?>
    <div class="logo">
        <img src="images/logo.png" alt="Website Logo" class="logo">
    </div>
    <div class="container">
        <div class="centered-form">
            <form method="post" action="admin/essentials/login_process.php">
                <h2>Login</h2>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required><br><br>
        
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required><br><br>
        
                <input type="submit" value="Login">
            </form>
        </div>
    </div>
</body>
</html>
