<?php
require "../../config/database.php"; 

// Fetching capacity data
$sql = $connexion->prepare("SELECT * FROM capacite_chambre");
$sql->execute();
$capacities = $sql->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Capacités des Chambres</title>
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
        <h2>Room Capacity Management</h2>
        <div class="mb-3 d-flex justify-content-between">
            <div>
                <a href="create.php" class="btn btn-primary"><i class="fas fa-plus"></i> Ajouter une Capacité</a>
            </div>
            <div>
                <a href="../configuration/index.php" class="btn btn-secondary"><i class="fas fa-tachometer-alt"></i> Retour au Tableau de Bord</a>
            </div>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID Capacity</th> <!-- Nouvelle colonne pour l'ID de capacité -->
                    <th>Title of Capacity</th>
                    <th>Number of Capacity</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($capacities as $capacity): ?>
                    <tr>
                        <td><?= htmlspecialchars($capacity['id_capacite']) ?></td> <!-- Affichage de l'ID de capacité -->
                        <td><?= htmlspecialchars($capacity['titre_capacite']) ?></td>
                        <td><?= htmlspecialchars($capacity['numero_capacite']) ?></td>
                        <td>
                            <a href="edit.php?id_capacite=<?= htmlspecialchars($capacity['id_capacite']) ?>" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Modifier</a>
                            <a href="delete.php?id_capacite=<?= htmlspecialchars($capacity['id_capacite']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette capacité?')"><i class="fas fa-trash"></i> Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <!-- JavaScript libraries (Bootstrap, jQuery) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
