
<?php
require "../../config/database.php";

$message = "";
$messageType = "";

if (isset($_GET['id_capacite'])) {
    $id = $_GET['id_capacite'];

    // Fetch current data
    $sql = $connexion->prepare("SELECT * FROM capacite_chambre WHERE id_capacite = :id");
    $sql->bindParam(':id', $id);
    $sql->execute();
    $capacity = $sql->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Update the capacity data
        $titre_capacite = $_POST['titre_capacite'];
        $numero_capacite = $_POST['numero_capacite'];

        try {
            $sql = $connexion->prepare("UPDATE capacite_chambre SET titre_capacite = :titre_capacite, numero_capacite = :numero_capacite WHERE id_capacite = :id");
            $sql->bindParam(':titre_capacite', $titre_capacite);
            $sql->bindParam(':numero_capacite', $numero_capacite);
            $sql->bindParam(':id', $id);
            $sql->execute();

            // Set success message
            $message = "Capacité mise à jour avec succès.";
            $messageType = "success";
        } catch (PDOException $e) {
            // Set error message
            $message = "Erreur: " . $e->getMessage();
            $messageType = "danger";
        }
    }
} else {
    header("Location:list.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier la Capacité</title>
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
        <h2>Change Capacity</h2>
        <?php if (!empty($message)): ?>
            <div class="alert alert-<?= $messageType ?>" role="alert">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>
        <form method="post">
            <div class="form-group">
                <label for="titre_capacite">Title of Capacity</label>
                <input type="text" class="form-control" id="titre_capacite" name="titre_capacite" value="<?= htmlspecialchars($capacity['titre_capacite']) ?>" required>
            </div>
            <div class="form-group">
                <label for="numero_capacite">Number of persons</label>
                <input type="number" class="form-control" id="numero_capacite" name="numero_capacite" value="<?= htmlspecialchars($capacity['numero_capacite']) ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Change</button>
            <a href="list.php" class="btn btn-secondary">Cancel</a>
            <button type="button" class="btn btn-info" onclick="location.href='list.php'">Retour à la liste</button>
        </form>
    </div>
</body>
</html>
