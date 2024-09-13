<?php

session_start();
require_once "../../config/database.php";

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $connexion->prepare('SELECT * FROM reservation 
                            INNER JOIN client ON client.id_client = reservation.id_client
                            INNER JOIN chambre ON chambre.id_chambre = reservation.id_chambre
                            WHERE reservation.id_reservation=?');
    $stmt->execute([$id]);

    $reservation = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    header('location:list.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la Réservation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #FAF9F9;
            font-family: 'Arial', sans-serif;
        }

        .main-content {
            margin: 20px auto;
            max-width: 1200px;
        }

        .title {
            text-align: center;
            font-size: 2em;
            margin-bottom: 20px;
            color: #4A1C40;
        }

        .back-button {
            display: block;
            margin: 20px auto;
            text-align: center;
        }

        .accordion-button {
            background-color: #4A1C40;
            color: white;
            border: none;
            transition: background-color 0.3s ease;
        }

        .accordion-button:hover {
            background-color: #63101A;
        }

        .accordion-button:not(.collapsed) {
            color: white;
            background-color: #63101A;
            box-shadow: inset 0 -1px 3px rgba(0, 0, 0, 0.2);
        }

        .accordion-item {
            border: none;
            margin-bottom: 15px;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .accordion-header button {
            text-align: left;
            padding: 15px;
            font-size: 1.2em;
        }

        .table {
            margin-bottom: 0;
        }

        .table th, .table td {
            text-align: center;
            vertical-align: middle;
        }

        .table th {
            background-color: #4A1C40;
            color: white;
            border: 1px solid #ddd;
        }

        .table td {
            border: 1px solid #ddd;
        }

        .accordion-body {
            background-color: white;
            padding: 20px;
        }

        .container img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
    </style>
</head>

<body>
    <div id="page-content-wrapper" class="main-content w-100">
        <h1 class="title">Booking Details</h1>
        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Booking Details
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <div class="container">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                    <th>Reservation ID</th>
<th>Reservation Code</th>
<th>Reservation Date & Time</th>
<th>Arrival Date</th>
<th>Departure Date</th>
<th>Number of Days</th>
<th>Number of Adults/Children</th>
<th>Total Amount</th>
<th>Status</th>
<th>Client ID</th>
<th>Room ID</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?= $reservation['id_reservation'] ?></td>
                                        <td><?= $reservation['code_reservation'] ?></td>
                                        <td><?= $reservation['date_heure_reservation'] ?></td>
                                        <td><?= $reservation['date_arrivee'] ?></td>
                                        <td><?= $reservation['date_depart'] ?></td>
                                        <td><?= $reservation['nbr_jours'] ?></td>
                                        <td><?= $reservation['nbr_adultes_enfants'] ?></td>
                                        <td><?= $reservation['montant_total'] ?></td>
                                        <td><?= $reservation['etat'] ?></td>
                                        <td><?= $reservation['id_client'] ?></td>
                                        <td><?= $reservation['id_chambre'] ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        Client Détails 
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <div class="container">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                    <th>Client ID</th>
<th>Full Name</th>
<th>Gender</th>
<th>Date of Birth</th>
<th>Age</th>
<th>Country</th>
<th>City</th>
<th>Address</th>
<th>Phone</th>
<th>Email</th>
<th>Other Details</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?= $reservation['id_client'] ?></td>
                                        <td><?= $reservation['nom_complet'] ?></td>
                                        <td><?= $reservation['sexe'] ?></td>
                                        <td><?= $reservation['date_naissance'] ?></td>
                                        <td><?= $reservation['age'] ?></td>
                                        <td><?= $reservation['pays'] ?></td>
                                        <td><?= $reservation['ville'] ?></td>
                                        <td><?= $reservation['adresse'] ?></td>
                                        <td><?= $reservation['telephone'] ?></td>
                                        <td><?= $reservation['email'] ?></td>
                                        <td><?= $reservation['autres_details'] ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Room Détails 
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <div class="container">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                    <th>Room ID</th>
<th>Room Number</th>
<th>Number of Adults/Children</th>
<th>Room Reinforcement</th>
<th>Room Floor</th>
<th>Number of Beds</th>
<th>Photo</th>
<th>Room Type ID</th>
<th>Capacity ID</th>
<th>Rate ID</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?= $reservation['id_chambre'] ?></td>
                                        <td><?= $reservation['numero_chambre'] ?></td>
                                        <td><?= $reservation['nbr_adultes_enfants'] ?></td>
                                        <td><?= ($reservation['renfort_chambre'] == 1) ? "Oui" : "Non" ?></td>
                                        <td><?= $reservation['etage_chambre'] ?></td>
                                        <td><?= $reservation['nbr_lits_chambre'] ?></td>
                                        <td><img src="../../assets/images/<?= $reservation['photo'] ?>" alt="Photo de la chambre"></td>
                                        <td><?= $reservation['id_type_ch'] ?></td>
                                        <td><?= $reservation['id_capacite'] ?></td>
                                        <td><?= $reservation['id_tarif'] ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="back-button">
            <a href="list.php" class="btn btn-primary">Back to list</a>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </div>
</body>

</html>
