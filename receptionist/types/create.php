<?php
require_once '../../config/database.php';

if (isset($_POST["ajouter"])) {
    $type = $_POST["type_chambre"];
    $description = $_POST["description_type"];

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_name = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $image_path = "../../assets/images/"; // Répertoire de téléchargement
        $image_destination = $image_path . $image_name;

        // Déplacer le fichier téléchargé vers sa destination
        if (move_uploaded_file($image_tmp, $image_destination)) {
            // Préparer et exécuter la requête SQL
            $sqli = $connexion->prepare("INSERT INTO type_chambre (type_chambre, description_type, photo) VALUES (?, ?, ?)");
            if ($sqli->execute([$type, $description, $image_name])) {
                echo '<div class="alert alert-success text-center">Le type de chambre a été ajouté avec succès!</div>';
            } else {
                echo '<div class="alert alert-danger text-center">Erreur lors de l\'insertion des données dans la base de données.</div>';
            }
        } else {
            echo '<div class="alert alert-danger text-center">Erreur lors du téléchargement de l\'image.</div>';
        }
    } else {
        echo '<div class="alert alert-danger text-center">Veuillez sélectionner une image.</div>';
    }
} else {
    echo '<div class="alert alert-danger text-center">please complete all fields.</div>';
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add a New Room Type</title>
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
        .form-group {
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
        .form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add a New Room Type</h2>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Room Types</label>
                <input type="text" name="type_chambre" class="form-control">
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description_type" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <label>Photo (file)</label>
                <input type="file" name="image" class="form-control">
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary" name="ajouter"><i class="fas fa-plus"></i> Add</button>
            </div>
        </form>
        <a href="list.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Back to list</a>
    </div>

    <!-- JavaScript (Bootstrap, jQuery) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
