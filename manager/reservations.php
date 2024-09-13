<?php
session_start();

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['user'])) {
    header("Location: manager/index.php");
    exit();
}

// Database connection
require_once "../config/database.php";

// Fetch reservations
$stmt = $connexion->prepare("SELECT *
FROM reservation
INNER JOIN client ON 
    reservation.id_client = client.id_client
INNER JOIN chambre ON
    reservation.id_chambre = chambre.id_chambre
ORDER BY 
    reservation.date_depart DESC, 
    reservation.date_heure_reservation DESC;");
$stmt->execute();
$reservations = $stmt->fetchAll(PDO::FETCH_OBJ);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Tracking</title>
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
            margin: 20px;
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

        .etat {
            font-weight: bold;
        }

        .etat.pending {
            color: orange;
        }

        .etat.confirmed {
            color: green;
        }

        .etat.cancelled {
            color: red;
        }
    </style>
</head>
<body>
    <main class="main">
        <a href="index.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
        <h2>Reservation Tracking</h2>
        <table>
            <thead>
                <tr>
                    <th>Client Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $reservation): ?>
                <tr>
                    <td><?= htmlspecialchars($reservation->nom_complet) ?></td>
                    <td><?= htmlspecialchars($reservation->date_depart) ?></td>
                    <td><?= htmlspecialchars($reservation->date_heure_reservation) ?></td>
                    <td class="etat <?= htmlspecialchars($reservation->etat) ?>">
                        <?= htmlspecialchars($reservation->etat) ?>
                    </td>
                    <td><?= htmlspecialchars($reservation->numero_chambre) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Font Awesome JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
</body>
</html>
