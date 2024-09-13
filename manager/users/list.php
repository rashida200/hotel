<?php
session_start();

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['user'])) {
    header("Location: manager/index.php");
    exit();
}

// Retrieve session data
$firstName = $_SESSION['user']['nom'] ?? 'First Name';
$lastName = $_SESSION['user']['prenom'] ?? 'Last Name';
// Database connection
require_once "../../config/database.php";

// Fetch users
$stmt = $connexion->prepare("SELECT * FROM users_app");
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List with Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CSS for icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .main {
            margin: 20px; /* Adjusted margin to fill the space */
            padding: 20px;
        }

        .back-btn {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            margin-bottom: 20px;
            transition: background-color 0.3s;
        }

        .back-btn:hover {
            background-color: #0056b3;
            text-decoration: none;
        }

        .add-btn {
            display: inline-block;
            background-color: #C44E4E;
            color: white;
            padding: 10px 20px;
            border-radius: 4px;
            text-decoration: none;
            transition: background-color 0.3s;
        }

        .add-btn:hover {
            background-color: #b04343;
            text-decoration: none;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        table th {
            background-color: #f2f2f2;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f2f2f2;
        }

        .action-links a {
            color: #333;
            text-decoration: none;
            margin-right: 5px;
            transition: color 0.3s;
        }

        .action-links a:hover {
            color: #B72928;
        }

        .dashboard {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }

        .statistic {
            text-align: center;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
            flex: 1;
            margin-right: 10px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .statistic h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }

        .statistic p {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin: 0;
        }
    </style>
</head>
<body>
    <main class="main">
        <a href="../index.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        <h2>User List</h2>
        <a href="add.php" class="add-btn"><i class="fas fa-user-plus"></i> Add User</a>
        <table>
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user->nom) ?></td>
                    <td><?= htmlspecialchars($user->prenom) ?></td>
                    <td><?= htmlspecialchars($user->username) ?></td>
                    <td><?= htmlspecialchars($user->type) ?></td>
                    <td class="action-links">
                        <a href="view.php?id_user=<?= htmlspecialchars($user->id_user) ?>" title="View"><i class="fas fa-eye"></i></a>
                        <a href="edit.php?id_user=<?= htmlspecialchars($user->id_user) ?>" title="Edit"><i class="fas fa-edit"></i></a>
                        <?php if ($user->type !== 'Manager'): ?>
                        <a href="delete.php?id_user=<?= htmlspecialchars($user->id_user) ?>" title="Delete" onclick="return confirm('Are you sure you want to delete this user?')"><i class="fas fa-trash"></i></a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="dashboard">
            <div class="statistic">
                <h3>Total Users</h3>
                <p><?= count($users) ?></p>
            </div>
            <div class="statistic">
                <h3>Active Users</h3>
                <p><?= count(array_filter($users, function($user) { return $user->etat == 'active'; })) ?></p>
            </div>
            <div class="statistic">
                <h3>Inactive Users</h3>
                <p><?= count(array_filter($users, function($user) { return $user->etat == 'Bloqué'; })) ?></p>
            </div>
        </div>
    </main>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Font Awesome JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
</body>
</html>
