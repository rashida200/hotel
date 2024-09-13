<?php
session_start(); // Start session

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once "config/database.php";
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    // Prepare the SQL statement
    $stmt = $connexion->prepare("SELECT * FROM users_app WHERE username = ? AND password = ?");
    $stmt->execute([$username, $password]);

    if ($stmt->rowCount() >= 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if the user's account is blocked
        if ($user['etat'] === 'Bloqué') {
            echo json_encode(["error" => "Votre compte est bloqué. Veuillez contacter l'administrateur."]);
            exit();
        } else {
            // Store user data in session
            $_SESSION['user'] = $user;

            // Redirect based on user type
            $redirectUrl = '';
            switch ($user['type']) {
                case 'Manager':
                    $redirectUrl = "manager/index.php";
                    break;
                case 'Réceptionniste': // Assuming this is "Receptionist"
                    $redirectUrl = "receptionist/index.php";
                    break;
                case 'Caissier': // Assuming this is "Cashier"
                    $redirectUrl = "cashier/index.php";
                    break;
                default:
                    echo json_encode(["error" => "Unknown user type."]);
                    exit();
            }
            echo json_encode(["redirect" => $redirectUrl]);
            exit();
        }
    } else {
        echo json_encode(["error" => "Invalid username or password."]);
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body, html {
            margin: 0;
            padding: 0;
            height: 100%;
            width: 100%;
            overflow: hidden; /* Prevent scrolling */
        }
        .background {
            background-image: url('img1.jpg'); /* Replace with your image URL */
            background-size: 100% 100%; /* Cover entire viewport */
            background-repeat: no-repeat;
            background-position: center;
            height: 100%;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            overflow-y: auto; /* Add overflow-y to enable vertical scrolling */
        }
        .navbar {
            padding: 10px 20px;
            margin-bottom: 20px;
            width: 100%;
            background-color: rgba(255, 255, 255, 0); /* Transparent background */
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar-brand {
            color: #F7E6C2; /* Change text color */
            font-weight: bold;
            font-size: 24px;
            cursor: pointer;
            transition: color 0.3s;
        }
        .navbar-brand:hover {
            color: #ffb302; /* Hover color */
        }
        .signup-link {
            color: #F7E6C2;
            text-decoration: none;
            font-weight: bold;
            cursor: pointer;
            transition: color 0.3s;
        }
        .signup-link:hover {
            color: #CDAE64; /* Hover color */
        }
        .center-content {
            display: flex;
            justify-content: center; /* Center content horizontally */
            width: 100%;
            padding: 20px;
        }
        .form-container {
            flex: 1;
            display: none; /* Hide forms by default */
            justify-content: center;
            align-items: center;
            position: relative; /* Needed for the close button */
        }
        .form {
            max-width: 500px;
            width: 100%;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.9); /* Light background color */
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            position: relative;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .form-group button {
            width: 100%;
            padding: 10px;
            background-color: #CDAE64;
            color: #535719;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .form-group button:hover {
            background-color: #8A8156;
        }
        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #000;
            font-weight: bold;
        }
        .alert {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="background">
        <nav class="navbar">
            <a class="navbar-brand" href="#" id="login-link">Login</a>
        </nav>

        <div class="center-content">
            <div class="form-container" id="login-container">
                <form class="form" id="login-form">
                    <button type="button" class="close-btn" onclick="closeForm('login-container')">×</button>
                    <h2>Login</h2>
                    <div class="alert alert-danger" id="login-error" style="display: none;"></div>
                    <div class="form-group">
                        <label for="login-username">Username</label>
                        <input type="text" class="form-control" id="login-username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="login-password">Password</label>
                        <input type="password" class="form-control" id="login-password" name="password" required>
                    </div>
                    <div class="form-group">
                        <button type="submit">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('login-link').addEventListener('click', function() {
            document.getElementById('login-container').style.display = 'flex';
        });

        function closeForm(containerId) {
            document.getElementById(containerId).style.display = 'none';
        }

        document.getElementById('login-form').addEventListener('submit', function(event) {
            event.preventDefault();
            var formData = new FormData(this);

            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    document.getElementById('login-error').style.display = 'block';
                    document.getElementById('login-error').textContent = data.error;
                } else if (data.redirect) {
                    window.location.href = data.redirect;
                }
            })
            .catch(error => console.error('Error:', error));
        });
    </script>
</body>
</html>