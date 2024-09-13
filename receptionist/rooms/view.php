<?php
require "../../config/database.php"; 

// Fetching room details
$id_chambre = isset($_GET['id_chambre']) ? (int)$_GET['id_chambre'] : 0;
$sql = $connexion->prepare("SELECT chambre.*, 
           type_chambre.type_chambre, type_chambre.description_type,
           capacite_chambre.titre_capacite, capacite_chambre.numero_capacite,
           tarif_chambre.prix_base_nuit, tarif_chambre.prix_base_passage
    FROM chambre
    LEFT JOIN type_chambre ON chambre.id_type_ch = type_chambre.id_type_ch
    LEFT JOIN capacite_chambre ON chambre.id_capacite = capacite_chambre.id_capacite
    LEFT JOIN tarif_chambre ON chambre.id_tarif = tarif_chambre.id_tarif
    WHERE chambre.id_chambre = :id_chambre
");
$sql->bindParam(':id_chambre', $id_chambre, PDO::PARAM_INT);
$sql->execute();
$room = $sql->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Voir les Détails de la Chambre</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            color: #63101A; /* Main text color */
            font-family: Arial, sans-serif; /* Font style */
            padding-top: 20px;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .btn-secondary {
            color: #fff;
            background-color: #63101A; /* Button background color */
            border-color: #63101A;
        }
        .btn-secondary:hover {
            background-color: #4D0814; /* Button hover color */
            border-color: #4D0814;
        }
        .img-thumbnail {
            max-width: 100%;
            height: auto;
        }
        .details {
            margin-bottom: 20px;
        }
        .details p {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Room Details</h2>
        <?php if ($room): ?>
            <div class="details">
                <h4>Room number : <?= htmlspecialchars($room['numero_chambre']) ?></h4>
                <p><strong>Number of Adults and Children :</strong> <?= htmlspecialchars($room['nombre_adultes_enfants_ch']) ?></p>
                <p><strong>Room Reinforcement :</strong> <?= $room['renfort_chambre'] ? 'Oui' : 'Non' ?></p>
                <p><strong>Room Floor :</strong> <?= htmlspecialchars($room['etage_chambre']) ?></p>
                <p><strong>Number of Beds :</strong> <?= htmlspecialchars($room['nbr_lits_chambre']) ?></p>
                <p><strong>Photo :</strong></p>
                <img src="../../assets/images/<?= htmlspecialchars($room['photo']) ?>" alt="Photo de la Chambre" class="img-thumbnail">
                <p><strong>Type of rooms :</strong> <?= htmlspecialchars($room['type_chambre']) ?></p>
                <p><strong>Description :</strong> <?= htmlspecialchars($room['description_type']) ?></p>
                <p><strong>Room capacity :</strong> <?= htmlspecialchars($room['titre_capacite']) ?> (<?= htmlspecialchars($room['numero_capacite']) ?>)</p>
                <p><strong>Night Price :</strong> <?= htmlspecialchars($room['prix_base_nuit']) ?> DH</p>
                <p><strong>Passage Price :</strong> <?= htmlspecialchars($room['prix_base_passage']) ?> DH</p>
            </div>
        <?php else: ?>
            <p>Room not found.</p>
        <?php endif; ?>
        <a href="list.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to list</a>
    </div>

    <!-- JavaScript libraries (Bootstrap, jQuery) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
