<?php
// Start the session at the top before any output
session_start();

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['user'])) {
    header("Location: manager/index.php");
    exit();
}

// Retrieve session data
$nom = $_SESSION['user']['nom'] ?? 'Nom';
$prenom = $_SESSION['user']['prenom'] ?? 'Prenom';

// Database connection
require_once "../../config/database.php";

try {
    // Validate and retrieve the user ID
    $id_user = $_GET['id_user'] ?? null;

    if (empty($id_user)) {
        throw new Exception("User ID is not set or invalid.");
    }

    // Fetch the user details
    $stmt = $connexion->prepare("SELECT * FROM users_app WHERE id_user = ?");
    $stmt->execute([$id_user]);
    $user = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$user) {
        throw new Exception("User not found.");
    }
} catch (Exception $e) {
    $error = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View User</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0;
            font-family: Arial, sans-serif;
            height: 100vh;
            background-color: #C4AD8E;
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
            align-items: center;
        }
        .btn-custom, .btn-back {
            width: 100%;
            margin-top: 10px;
        }
        .btn-custom {
            background-color: #63101A;
            color: white;
        }
        .btn-custom:hover {
            background-color: #B72928;
        }
        .btn-back {
            background-color: #6c757d;
            color: white;
        }
        .btn-back:hover {
            background-color: #5a6268;
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
    </style>
</head>
<body>
    <div class="container">
        <h2 class="form-header">View User</h2>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php else: ?>
            <form>
                <div class="form-group">
                    <label for="nom" class="form-label">First Name:</label>
                    <input type="text" id="nom" name="nom" class="form-control" value="<?= htmlspecialchars($user->nom) ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="prenom" class="form-label">Last Name:</label>
                    <input type="text" id="prenom" name="prenom" class="form-control" value="<?= htmlspecialchars($user->prenom) ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="type" class="form-label">Role:</label>
                    <input type="text" id="type" name="type" class="form-control" value="<?= htmlspecialchars($user->type) ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" id="password" name="password" class="form-control" value="<?= htmlspecialchars($user->password) ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="username" class="form-label">Username:</label>
                    <input type="text" id="username" name="username" class="form-control" value="<?= htmlspecialchars($user->username) ?>" readonly>
                </div>
                <div class="form-group">
                    <label for="Etat" class="form-label">Status:</label>
                    <input type="text" id="Etat" name="Etat" class="form-control" value="<?= htmlspecialchars($user->etat) ?>" readonly>
                </div>
                <a href="/hotel/manager/users/list.php" class="btn btn-back">Back to users list</a>
            </form>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
