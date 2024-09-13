<?php
require "../../config/database.php";

// Fetching room rates data
$sql = $connexion->prepare("SELECT * FROM tarif_chambre");
$sql->execute();
$tarifs = $sql->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Tarifs des Chambres</title>
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
        .fa {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Management of Room Rates</h2>
        <div class="mb-3">
            <a href="create.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add a rate</a>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
<th>Rate ID</th>
<th>Base Nightly Price</th>
<th>Base Hourly Rate</th> <!-- "Prix Base Passage" refers to a rate for short stays or hourly rates -->
<th>New Nightly Price</th>
<th>New Hourly Rate</th>
<th>Actions</th>
</tr>
            </thead>
            <tbody>
                <?php foreach ($tarifs as $tarif): ?>
                    <tr>
                    <td><?= htmlspecialchars($tarif['id_tarif']) ?></td>
                        <td><?= htmlspecialchars($tarif['prix_base_nuit']) ?></td>
                        <td><?= htmlspecialchars($tarif['prix_base_passage']) ?></td>
                        <td><?= htmlspecialchars($tarif['n_prix_nuit']) ?></td>
                        <td><?= htmlspecialchars($tarif['n_prix_passage']) ?></td>
                        <td>
                            <a href="edit.php?id_tarif=<?= htmlspecialchars($tarif['id_tarif']) ?>" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Modify</a>
                            <a href="delete.php?id_tarif=<?= htmlspecialchars($tarif['id_tarif']) ?>" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <a href="../configuration/index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    </div>
    <!-- JavaScript libraries (Bootstrap, jQuery) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
