<?php
session_start();
require "../config/database.php";

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['user'])) {
    header("Location: /hotel/index.php");
    exit();
}

// Retrieve session data
$nom = $_SESSION['user']['nom'] ?? 'Nom';
$prenom = $_SESSION['user']['prenom'] ?? 'Prenom';
$image = $_SESSION['user']['image'] ?? '';

// Fetch payment data (replace with real queries)
$payments = [];
$sql = "SELECT * FROM reservation 
INNER JOIN client ON reservation.id_client=client.id_client
WHERE etat='confirmed'";
$stmt = $connexion->prepare($sql);
$stmt->execute();
$payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Management</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Custom CSS for sidebar and dashboard -->
    <style>
        body {
            background-color: #C4AD8E;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .wrapper {
            display: flex;
            width: 100%;
            background-color: #FFFFFF;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            height: 100vh;
            margin: 0;
        }

        .sidebar {
            background-color: #63101A;
            width: 250px;
            padding: 20px;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            height: 100vh;
            position: fixed;
        }

        .sidebar nav {
            margin-bottom: 20px;
        }

        .sidebar button {
            width: 100%;
            padding: 10px;
            border: none;
            background-color: #B72928;
            color: white;
            text-align: left;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .sidebar button:hover {
            background-color: #8C1B1A;
        }

        .sidebar button i {
            margin-right: 10px;
        }

        .main-content {
            margin-left: 250px;
            flex-grow: 1;
            padding: 20px;
            background-color: #FFFFFF;
            overflow-y: auto;
            height: 100vh;
            box-sizing: border-box;
        }

        .header {
            background-color: #c44e4e;
            padding: 10px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 20px;
            color: white;
        }

        .profile {
            text-align: center;
            margin-bottom: 20px;
        }

        .profile img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
        }

        .sidebar nav a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 10px 0;
            transition: background-color 0.3s ease;
        }

        .sidebar nav a:hover {
            background-color: #B72928;
            border-radius: 5px;
        }

        .sidebar nav a i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .table-container {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .table-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="sidebar">
            <div class="profile">
                <img src="../assets/images/<?= htmlspecialchars($image) ?>" alt="Profile Picture">
                <h2><?= htmlspecialchars($nom . ' ' . $prenom) ?></h2>
            </div>
            <nav>
                <a href="index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
            </nav>
        </div>
        <div class="main-content">
            <div class="header">
                <h1>Welcome back, <?= htmlspecialchars($nom . ' ' . $prenom) ?>!</h1>
                <p><?= date('d F, Y') ?></p>
            </div>

            <div class="table-container">
                <h2>Payment Management</h2>
                <table class="table table-bordered">
    <thead>
        <tr>
            <th>Client</th>
            <th>Telephone</th>
            <th>Arrival date</th>
            <th>Date of departure</th>
            <th>Total amount</th>
            <th>Action</th> <!-- Nouvelle colonne pour le bouton -->
        </tr>
    </thead>
    <tbody>
        <?php foreach ($payments as $payment): ?>
            <tr>
                <td><?= htmlspecialchars($payment['nom_complet']) ?></td>
                <td><?= htmlspecialchars($payment['telephone']) ?></td>
                <td><?= htmlspecialchars($payment['date_arrivee']) ?></td>
                <td><?= htmlspecialchars($payment['date_depart']) ?></td>
                <td><?= htmlspecialchars($payment['montant_total']) ?></td>
                <td>
                    <form action="imprimer.php" method="post" target="_blank">
                        <input type="hidden" name="id_reservation" value="<?= htmlspecialchars($payment['id_reservation']) ?>">
                        <button type="submit" class="btn btn-primary btn-sm">Imprimer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
            </div>
        </div>
    </div>
    <script>
        document.querySelectorAll('.print-btn').forEach(button => {
            button.addEventListener('click', function() {
                const clientId = this.getAttribute('data-id');
                window.open(`generate_pdf.php?id=${clientId}`, '_blank');
            });
        });
    </script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>