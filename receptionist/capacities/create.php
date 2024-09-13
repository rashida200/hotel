<?php
session_start();
require_once "../../config/database.php"; // Assurez-vous que ce chemin est correct

$message = "";
$messageType = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer et nettoyer les données
    $titre_capacite = htmlspecialchars(trim($_POST['titre_capacite']));
    $numero_capacite = filter_var($_POST['numero_capacite'], FILTER_VALIDATE_INT);

    // Vérifier les champs obligatoires
    if (!empty($titre_capacite) && !empty($numero_capacite)) {
        try {
            // Préparer la requête SQL
            $stmt = $connexion->prepare("INSERT INTO capacite_chambre (titre_capacite, numero_capacite) VALUES (:titre, :numero)");
            $stmt->bindParam(':titre', $titre_capacite);
            $stmt->bindParam(':numero', $numero_capacite);

            // Exécuter la requête
            if ($stmt->execute()) {
                $message = "Capacité ajoutée avec succès.";
                $messageType = "success";
            } else {
                $message = "Une erreur est survenue lors de l'ajout de la capacité.";
                $messageType = "danger";
            }
        } catch (PDOException $e) {
            $message = "Erreur: " . $e->getMessage();
            $messageType = "danger";
        }
    } else {
        $message = "Veuillez remplir tous les champs.";
        $messageType = "warning";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Capacité</title>
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
        .btn-secondary {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .btn-secondary:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php if (!empty($message)): ?>
            <div class="alert alert-<?= $messageType ?> alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($message) ?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        <?php endif; ?>
        <h2>Add Capacity</h2>
        <form method="POST">
            <div class="form-group">
                <label for="titre_capacite">Title of Capacity</label>
                <input type="text" class="form-control" id="titre_capacite" name="titre_capacite" required>
            </div>
            <div class="form-group">
                <label for="numero_capacite">Number of Capacity</label>
                <input type="number" class="form-control" id="numero_capacite" name="numero_capacite" required>
            </div>
            <button type="submit" class="btn btn-primary">Add</button>
            <a href="list.php" class="btn btn-secondary ml-2">Back to List</a>
        </form>
    </div>
    <!-- JavaScript libraries (Bootstrap, jQuery) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
