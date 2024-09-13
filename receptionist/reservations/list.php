<?php
session_start();

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['user'])) {
    header("Location: manager/index.php");
    exit();
}

// Retrieve session data
$nom = $_SESSION['user']['nom'] ?? 'Nom';
$prenom = $_SESSION['user']['prenom'] ?? 'Prenom';
$image = $_SESSION['user']['image'] ?? '';
require "../../config/database.php";

// Initialize variables for search
$date_arrivee = isset($_GET['date_arrivee']) ? $_GET['date_arrivee'] : '';
$date_depart = isset($_GET['date_depart']) ? $_GET['date_depart'] : '';

// Prepare SQL query with optional date conditions
$sql = "SELECT * FROM reservation 
        INNER JOIN client ON reservation.id_client = client.id_client
        INNER JOIN chambre ON reservation.id_chambre = chambre.id_chambre";
$whereClause = [];

// Build the WHERE clause based on provided dates
$params = [];
if (!empty($date_arrivee)) {
    $whereClause[] = "date_arrivee >= :date_arrivee";
    $params[':date_arrivee'] = $date_arrivee;
}
if (!empty($date_depart)) {
    $whereClause[] = "date_depart <= :date_depart";
    $params[':date_depart'] = $date_depart;
}

// Combine WHERE clauses if any exist
if (!empty($whereClause)) {
    $sql .= " WHERE " . implode(" AND ", $whereClause);
}

try {
    // Prepare and execute query with parameters
    $stmt = $connexion->prepare($sql);
    $stmt->execute($params);

    // Fetch all results into an associative array
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error retrieving reservations: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Réservations</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        .wrapper {
            display: flex;
            height: 100vh;
        }

        .sidebar {
            background-color: #63101A;
            width: 250px;
            color: white;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100%;
            /* Make sidebar height 100% of its parent */
        }

        .sidebar .profile {
            text-align: center;
            margin-bottom: 20px;
        }

        .sidebar .profile img {
            border-radius: 50%;
            width: 100px;
        }

        .sidebar nav {
            width: 100%;
            margin-top: 100px;
            display: flex;
            flex-direction: column;
        }

        .sidebar nav a {
            color: white;
            text-decoration: none;
            display: flex;
            align-items: center;
            padding: 10px 0;
            transition: background-color 0.3s ease;
        }

        .sidebar nav a i {
            color: #63101A;
            /* Change Font Awesome icon color */
            margin-right: 10px;
        }

        .sidebar nav a:hover {
            background-color: #B72928;
            border-radius: 5px;
        }


        .main-content {
            flex-grow: 1;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
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
            margin-bottom: 20px;
            overflow-x: auto;
            flex-grow: 1;
            /* Allow the table to expand and fill remaining space */
            width: 100%;
            /* Ensure the table fills the container */
        }

        .table-container table {
            width: 100%;
            border-collapse: collapse;
        }

        .table-container th,
        .table-container td {
            padding: 8px;
            text-align: center;
            vertical-align: middle;
        }

        .table-container th {
            background-color: #343a40;
            color: white;
        }

        .table-container tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .action-icons i {
            cursor: pointer;
            margin: 0 5px;
            transition: color 0.3s ease;
        }

        .action-icons i:hover {
            color: #B72928;
        }

        .btn-primary {
            background-color: #63101A;
            border-color: #63101A;
        }

        .btn-primary:hover {
            background-color: #B72928;
            border-color: #B72928;
        }

        .search-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .search-container form {
            display: flex;
            align-items: center;
        }

        .search-container input[type="date"] {
            margin-right: 10px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .search-container input[type="date"]:focus {
            outline: none;
            border-color: #63101A;
        }

        .search-container button {
            margin-left: 10px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="sidebar">
            <div class="profile">
                <img src="../../assets/images/<?= $image ?>" alt="Profile Picture">
                <h2><?= htmlspecialchars($nom . ' ' . $prenom) ?></h2>
            </div>
            <nav>
                <a href="../index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                <a href="checkout.php"><i class="fas fa-sign-out-alt"></i> Check Out</a>
                <a href="planned.php?etat=Planifiée"><i class="fas fa-list-alt"></i> Réservations Planifiées</a>
                <a href="ongoing.php?etat=En cours"><i class="fas fa-list-alt"></i> Réservations En cours</a>
                <a href="completed.php?etat=Terminée"><i class="fas fa-list-alt"></i> Réservations Terminées</a>
            </nav>
        </div>
        <div class="main-content">
            <div class="header">
                <h1><?= htmlspecialchars($nom . ' ' . $prenom) ?></h1>
                <p><?= date('j F, Y') ?></p>
            </div>
            <div class="search-container">
                <form method="get" action="">
                    <input type="date" name="date_arrivee" value="<?= htmlspecialchars($date_arrivee) ?>"
                        placeholder="Date d'arrivée">
                    <input type="date" name="date_depart" value="<?= htmlspecialchars($date_depart) ?>"
                        placeholder="Date de départ">
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
            </div>
            <div class="table-container">
                <button class="btn btn-primary btn-add-client" onclick="window.location.href='create.php';">
                    <i class="fas fa-user-plus"></i> Add a reservation
                </button>
                <button class="btn btn-primary btn-add-client" onclick="window.location.href='list.php';">
                    <i class="fas fa-list"></i> See the whole list
                </button>

                <h2>Liste des Réservations</h2>
                <table class="table table-bordered">
                    <thead class="thead-dark">
                        <tr>
                        <th>ID</th>
                        <th>Code</th>
                        <th>Date & Time</th>
                        <th>Arrival</th>
                        <th>Departure</th>
                        <th>Days</th>
                        <th>Adults & Children</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Client</th>
                        <th>Room</th>
                        <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservations as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['id_reservation']) ?></td>
                                <td><?= htmlspecialchars($row['code_reservation']) ?></td>
                                <td><?= htmlspecialchars($row['date_heure_reservation']) ?></td>
                                <td><?= htmlspecialchars($row['date_arrivee']) ?></td>
                                <td><?= htmlspecialchars($row['date_depart']) ?></td>
                                <td><?= htmlspecialchars($row['nbr_jours']) ?></td>
                                <td><?= ($row['nbr_adultes_enfants']) ?></td>
                                <td><?= htmlspecialchars($row['montant_total']) ?></td>
                                <td><?= htmlspecialchars($row['etat']) ?></td>
                                <td><?= htmlspecialchars($row['nom_complet']) ?></td>
                                <td><?= htmlspecialchars($row['numero_chambre']) ?></td>
                                <td>
                                    <a class="btn btn-sm btn-danger" href="delete.php?id=<?=$row['id_reservation']?>"><i
                                            class="fa fa-trash"></i>
                                    </a>
                                    <a class="btn btn-sm btn-info" href="view.php?id=<?=$row['id_reservation']?>"><i
                                            class="fa fa-eye"></i>
                                    </a>
                                    <a class="btn btn-sm btn-secondary" href="edit.php?id=<?=$row['id_reservation']?>"><i
                                            class="fa fa-edit"></i>
                                    </a>
                                </td>

                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    <!-- Custom JS -->
    <script>
        // Your JavaScript code here
    </script>
</body>

</html>

<?php
// Close PDO connection
$connexion = null;
?>