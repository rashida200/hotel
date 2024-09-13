<?php
session_start();
require_once "../../config/database.php";

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['user'])) {
    header("Location: /hotel/index.php");
    exit();
}

// Retrieve session data
$nom = $_SESSION['user']['nom'] ?? 'Nom';
$prenom = $_SESSION['user']['prenom'] ?? 'Prenom';
$image = $_SESSION['user']['image'] ?? 'default_profile.jpg';

// Initialize variables for search form
$date_arrivee = isset($_GET['date_arrivee']) ? $_GET['date_arrivee'] : '';
$date_depart = isset($_GET['date_depart']) ? $_GET['date_depart'] : '';
$search_nom = isset($_GET['search_nom']) ? $_GET['search_nom'] : '';

// Fetch reservation data based on search criteria
$sql = "SELECT * FROM client
        INNER JOIN reservation ON client.id_client = reservation.id_client
        INNER JOIN chambre ON reservation.id_chambre = chambre.id_chambre";

$whereClause = ""; // Initialize where clause for search conditions

// Check if search dates are provided and build where clause accordingly
if (!empty($date_arrivee) && !empty($date_depart)) {
    // Prepare where clause to filter reservations within the specified date range
    $whereClause .= " WHERE reservation.date_arrivee >= :date_arrivee
                      AND reservation.date_depart <= :date_depart";
}

// Check if search nom is provided and append to where clause
if (!empty($search_nom)) {
    if (!empty($whereClause)) {
        $whereClause .= " AND";
    } else {
        $whereClause .= " WHERE";
    }
    $whereClause .= " client.nom_complet LIKE :search_nom";
}

// Append the where clause to the SQL query
$sql .= $whereClause;

// Prepare and execute statement
$stmt = $connexion->prepare($sql);

// Bind parameters if search dates are provided
if (!empty($date_arrivee) && !empty($date_depart)) {
    $stmt->bindParam(':date_arrivee', $date_arrivee, PDO::PARAM_STR);
    $stmt->bindParam(':date_depart', $date_depart, PDO::PARAM_STR);
}

// Bind parameter for search nom if provided
if (!empty($search_nom)) {
    $search_nom_like = "%$search_nom%";
    $stmt->bindParam(':search_nom', $search_nom_like, PDO::PARAM_STR);
}

$stmt->execute();

// Fetch all clients matching the search criteria
$clients = $stmt->fetchAll(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receptionist Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS for sidebar and additional styles -->
    <style>
        body {
            background-color: #C4AD8E;
        }

        .wrapper {
            display: flex;
            height: 100vh;
        }

        .main-content {
            flex-grow: 1;
            padding: 20px;
            background-color: #FFFFFF;
        }

        .header {
            background-color: #c44e4e;
            padding: 10px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 20px;
            color: white;
        }

        .table-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #63101A;
            color: white;
        }

        button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #63101A;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 20px;
            margin-right: 10px;
        }

        button:hover {
            background-color: #B72928;
        }

        .form-control {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 8px;
            width: 100%;
        }

        .btn-primary {
            background-color: #63101A;
            border-color: #63101A;
        }

        .btn-primary:hover {
            background-color: #B72928;
            border-color: #B72928;
        }

        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }

        .btn-success:hover {
            background-color: #218838;
            border-color: #218838;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="main-content">
            <div class="header">
                <h1>Welcome, <?= htmlspecialchars($nom . ' ' . $prenom) ?>!</h1>
                <p><?= date('d F, Y') ?></p> <!-- Affichage de la date d'aujourd'hui -->
            </div>

            <div class="table-container">
                <h2>Booking Results</h2>

                <!-- Form for search -->
                <form action="" method="GET" class="mb-4">
                    <div class="form-row">
                        <div class="col">
                            <label for="date_arrivee">Arrival date</label>
                            <input type="date" id="date_arrivee" name="date_arrivee" class="form-control" value="<?= htmlspecialchars($date_arrivee) ?>">
                        </div>
                        <div class="col">
                            <label for="date_depart">Date of departure</label>
                            <input type="date" id="date_depart" name="date_depart" class="form-control" value="<?= htmlspecialchars($date_depart) ?>">
                        </div>
                        <div class="col">
                            <label for="search_nom">Search with a name:</label>
                            <input type="text" id="search_nom" name="search_nom" class="form-control" value="<?= htmlspecialchars($search_nom) ?>">
                        </div>
                        <div class="col">
                            <button type="submit" class="btn btn-primary mt-4">Search</button>
                        </div>
                    </div>
                </form>

                <!-- Table to display reservation results -->
                <table>
                    <thead>
                        <tr>
                            <th> Client ID</th>
                            <th>Full name</th>
                            <th>Gender</th>
                            <th>Âge</th>
                            <th>Arrival date</th>
                            <th>Date of departure</th>
                            <th>N° of rooms</th>
                            <th>Price (DH)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($clients): ?>
                            <?php foreach ($clients as $client): ?>
                                <tr>
                                    <td><?= htmlspecialchars($client->id_client) ?></td>
                                    <td><?= htmlspecialchars($client->nom_complet) ?></td>
                                    <td><?= htmlspecialchars($client->sexe) ?></td>
                                    <td><?= htmlspecialchars($client->age) ?></td>
                                    <td><?= htmlspecialchars($client->date_arrivee) ?></td>
                                    <td><?= htmlspecialchars($client->date_depart) ?></td>
                                    <td><?= htmlspecialchars($client->numero_chambre) ?></td>
                                    <td><?= htmlspecialchars($client->montant_total) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8">No reservations found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- Buttons for navigation -->
                <a href="list.php" class="btn btn-primary">Back to full list</a>
                <a href="registre.php" class="btn btn-success">View all registry customers</a>
                <a href="imprimer.php" class="btn btn-primary">Print</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
