<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            font-family: Arial, sans-serif;
            min-height: 100vh; /* Ensure it takes the full height */
            overflow-y: auto; /* Enable vertical scrolling if needed */
            background-color: #C4AD8E; /* Background for body */
        }
        .container {
            background-color: #FFFFFF;
            border-radius: 20px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 600px;
            width: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .btn-custom {
            background-color: #63101A;
            color: white;
            width: 100%;
            margin-top: 20px;
        }
        .btn-custom:hover {
            background-color: #B72928;
        }
        .form-label {
            color: #63101A;
            font-weight: bold;
        }
        .form-control {
            border-radius: 5px;
            border-color: #ccc;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .alert {
            margin-top: 20px;
        }
        .success {
            color: green;
            margin-bottom: 15px;
        }
        .error {
            color: red;
            margin-bottom: 15px;
        }
        .form-header {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
            color: #63101A;
        }
        .btn-back {
            background-color: #6c757d;
            color: white;
            width: 100%;
            margin-top: 10px;
        }
        .btn-back:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="form-header">Update User</h2>

        <?php
            require_once "../../config/database.php";

            try {
                $id_user = $_GET['id_user'];

                if (empty($id_user)) {
                    throw new Exception("User ID is not set or invalid.");
                }

                $sqll = $connexion->prepare("SELECT * FROM users_app WHERE id_user = ?");
                $sqll->execute([$id_user]);
                $user = $sqll->fetch(PDO::FETCH_OBJ);

                if (!$user) {
                    throw new Exception("User not found.");
                }

                $update_successful = false;

                if (isset($_POST["update"])) {
                    if (!empty($_POST["first_name"]) && !empty($_POST["last_name"]) && !empty($_POST["role"]) && !empty($_POST["password"]) && !empty($_POST["username"]) && !empty($_POST["status"])) {
                        $first_name = $_POST["first_name"];
                        $last_name = $_POST["last_name"];
                        $role = $_POST["role"];
                        $password = $_POST["password"];
                        $username = $_POST["username"];
                        $status = $_POST["status"];

                        $sqli = $connexion->prepare("UPDATE users_app SET nom = ?, prenom = ?, type = ?, password = ?, username = ?, etat = ? WHERE id_user = ?");
                        $sqli->execute([$first_name, $last_name, $role, $password, $username, $status, $id_user]);

                        $update_successful = true;
                    }
                }
            } catch (Exception $e) {
                echo "<div class='error'>" . htmlspecialchars($e->getMessage()) . "</div>";
            }
        ?>

        <?php if ($update_successful): ?>
            <div class="alert alert-success text-center">User updated successfully!</div>
        <?php endif; ?>

        <form action="" method="post">
            <div class="form-group">
                <label for="first_name" class="form-label">First Name:</label>
                <input type="text" id="first_name" name="first_name" class="form-control" value="<?php echo htmlspecialchars($user->nom); ?>">
            </div>
            <div class="form-group">
                <label for="last_name" class="form-label">Last Name:</label>
                <input type="text" id="last_name" name="last_name" class="form-control" value="<?php echo htmlspecialchars($user->prenom); ?>">
            </div>
            <div class="form-group">
                <label for="role" class="form-label">Role:</label>
                <input type="text" id="role" name="role" class="form-control" value="<?php echo htmlspecialchars($user->type); ?>">
            </div>
            <div class="form-group">
                <label for="password" class="form-label">Password:</label>
                <input type="password" id="password" name="password" class="form-control" value="<?php echo htmlspecialchars($user->password); ?>">
            </div>
            <div class="form-group">
                <label for="username" class="form-label">Username:</label>
                <input type="text" id="username" name="username" class="form-control" value="<?php echo htmlspecialchars($user->username); ?>">
            </div>
            <div class="form-group">
                <label for="status" class="form-label">Status:</label>
                <input type="text" id="status" name="status" class="form-control" value="<?php echo htmlspecialchars($user->etat); ?>">
            </div>
            <button type="submit" name="update" class="btn btn-custom">Update</button>
        </form>
        <a href="\hotel\manager\users\list.php" class="btn btn-back">Back to Users List</a>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
