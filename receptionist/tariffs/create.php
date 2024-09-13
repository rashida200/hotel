<?php
require "../../config/database.php";

$message = ""; // Initialize the message variable

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prix_base_nuit = $_POST['prix_base_nuit'];
    $prix_base_passage = $_POST['prix_base_passage'];
    $n_prix_nuit = $_POST['n_prix_nuit'];
    $n_prix_passage = $_POST['n_prix_passage'];

    try {
        // Insert new room rate
        $sql = $connexion->prepare("INSERT INTO tarif_chambre (prix_base_nuit, prix_base_passage, n_prix_nuit, n_prix_passage) VALUES (:prix_base_nuit, :prix_base_passage, :n_prix_nuit, :n_prix_passage)");
        $sql->bindParam(':prix_base_nuit', $prix_base_nuit);
        $sql->bindParam(':prix_base_passage', $prix_base_passage);
        $sql->bindParam(':n_prix_nuit', $n_prix_nuit);
        $sql->bindParam(':n_prix_passage', $n_prix_passage);
        $sql->execute();

        // Set success message
        $message = "Tarif de chambre ajouté avec succès.";
        $messageType = "success";
    } catch (PDOException $e) {
        // Set error message
        $message = "Erreur: " . $e->getMessage();
        $messageType = "danger";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Tarif de Chambre</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
        .alert {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add a Room Rate</h2>
        <?php if (!empty($message)): ?>
            <div class="alert alert-<?= htmlspecialchars($messageType) ?>" role="alert">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>
        <form method="post">
            <div class="form-group">
                <label for="prix_base_nuit">Night Price</label>
                <input type="text" class="form-control" id="prix_base_nuit" name="prix_base_nuit" required>
            </div>
            <div class="form-group">
                <label for="prix_base_passage">Passage Price</label>
                <input type="text" class="form-control" id="prix_base_passage" name="prix_base_passage" required>
            </div>
            <div class="form-group">
                <label for="n_prix_nuit">New night Price</label>
                <input type="text" class="form-control" id="n_prix_nuit" name="n_prix_nuit" required>
            </div>
            <div class="form-group">
                <label for="n_prix_passage">New passage Price</label>
                <input type="text" class="form-control" id="n_prix_passage" name="n_prix_passage" required>
            </div>
            <button type="submit" class="btn btn-primary">Add</button>
            <a href="list.php" class="btn btn-secondary">Back to list</a>
        </form>
    </div>
</body>
</html>
