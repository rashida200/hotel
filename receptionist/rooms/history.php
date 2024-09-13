<?php
require "../../config/database.php"; 

// Fetching chambre details
$id_chambre = isset($_GET['id_chambre']) ? (int)$_GET['id_chambre'] : 0;
$sql = $connexion->prepare("SELECT * FROM chambre WHERE id_chambre = :id_chambre");
$sql->bindParam(':id_chambre', $id_chambre, PDO::PARAM_INT);
$sql->execute();
$chambre = $sql->fetch(PDO::FETCH_ASSOC);

// Fetching reservation history for the chambre including client's name
$date_debut = isset($_GET['date_debut']) ? $_GET['date_debut'] : '';
$date_fin = isset($_GET['date_fin']) ? $_GET['date_fin'] : '';

$sqlReservations = "SELECT r.*, c.nom_complet, c.telephone
                    FROM reservation r
                    INNER JOIN client c ON r.id_client = c.id_client
                    WHERE r.id_chambre = :id_chambre ";

// Adding date filters if provided
if (!empty($date_debut) && !empty($date_fin)) {
    $sqlReservations .= "AND r.date_arrivee >= :date_debut AND r.date_depart <= :date_fin ";
}

$sqlReservations .= "ORDER BY r.date_arrivee ASC";

$stmt = $connexion->prepare($sqlReservations);
$stmt->bindParam(':id_chambre', $id_chambre, PDO::PARAM_INT);
if (!empty($date_debut)) {
    $stmt->bindParam(':date_debut', $date_debut);
}
if (!empty($date_fin)) {
    $stmt->bindParam(':date_fin', $date_fin);
}
$stmt->execute();
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique des Réservations</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 20px;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .btn-primary {
            background-color: #C44E4E;
            border-color: #C44E4E;
        }
        .btn-primary:hover {
            background-color: #b04343;
            border-color: #b04343;
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .btn-danger:hover {
            background-color: #c82333;
            border-color: #c82333;
        }
        .btn-info {
            background-color: #17a2b8;
            border-color: #17a2b8;
        }
        .btn-info:hover {
            background-color: #138496;
            border-color: #117a8b;
        }
        .fa {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Booking History</h2>
        <?php if ($chambre): ?>
            <div class="mb-3">
                <h4>Room number : <?= htmlspecialchars($chambre['numero_chambre']) ?></h4>
            </div>

            <h4>Booking History</h4>
            <!-- Form for Date Range Search -->
            <form action="" method="get" class="mb-3">
                <input type="hidden" name="id_chambre" value="<?= htmlspecialchars($id_chambre) ?>">
                <div class="row">
                    <div class="col">
                        <label for="date_debut">From (Start date):</label>
                        <input type="date" id="date_debut" name="date_debut" class="form-control" value="<?= htmlspecialchars($date_debut) ?>">
                    </div>
                    <div class="col">
                        <label for="date_fin">As of (End date) :</label>
                        <input type="date" id="date_fin" name="date_fin" class="form-control" value="<?= htmlspecialchars($date_fin) ?>">
                    </div>
                    <div class="col">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-primary btn-block mt-2">Search</button>
                    </div>
                </div>
            </form>

            <?php if ($reservations): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                        <th>Reservation Code</th>
<th>Reservation Date and Time</th>
<th>Arrival Date</th>
<th>Departure Date</th>
<th>Number of Days</th>
<th>Number of Adults/Children</th>
<th>Total Amount</th>
<th>Status</th>
<th>Client Name</th>
<th>Phone Number</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($reservations as $reservation): ?>
                            <tr>
                                <td><?= htmlspecialchars($reservation['code_reservation']) ?></td>
                                <td><?= htmlspecialchars($reservation['date_heure_reservation']) ?></td>
                                <td><?= htmlspecialchars($reservation['date_arrivee']) ?></td>
                                <td><?= htmlspecialchars($reservation['date_depart']) ?></td>
                                <td><?= htmlspecialchars($reservation['nbr_jours']) ?></td>
                                <td><?= htmlspecialchars($reservation['nbr_adultes_enfants']) ?></td>
                                <td><?= htmlspecialchars($reservation['montant_total']) ?></td>
                                <td><?= htmlspecialchars($reservation['etat']) ?></td>
                                <td><?= htmlspecialchars($reservation['nom_complet']) ?></td>
                                <td><?= htmlspecialchars($reservation['telephone']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No reservations found for this room in the specified period.</p>
            <?php endif; ?>
        <?php else: ?>
            <p>Room not found.</p>
        <?php endif; ?>
        <div class="mt-3">
            <a href="list.php" class="btn btn-primary"><i class="fas fa-arrow-left"></i>Return to room list</a>
        </div>
    </div>

    <!-- JavaScript libraries (Bootstrap, jQuery) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
