<?php
require_once '../../config/database.php';

// Vérification de la présence de l'ID du type de chambre dans l'URL
if (isset($_GET['id_type_ch'])) {
    $id = $_GET['id_type_ch'];

    // Récupération des informations sur le type de chambre
    $sql_type = $connexion->prepare("SELECT * FROM type_chambre WHERE id_type_ch = :id");
    $sql_type->bindParam(':id', $id);
    $sql_type->execute();
    $type = $sql_type->fetch(PDO::FETCH_ASSOC);

    // Récupération des numéros des chambres ayant ce type
    $sql_chambres = $connexion->prepare("SELECT numero_chambre FROM chambre WHERE id_type_ch = :id");
    $sql_chambres->bindParam(':id', $id);
    $sql_chambres->execute();
    $chambres = $sql_chambres->fetchAll(PDO::FETCH_COLUMN);
} else {
    // Redirection si l'ID du type de chambre n'est pas fourni dans l'URL
    header("Location:list.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulter un Type de Chambre</title>
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
            margin-top: 20px;
        }
        .btn-secondary {
            background-color: hsl(356, 26%, 57%);
            border-color: hsl(356, 26%, 57%);
            color: #fff;
        }
        .btn-secondary:hover {
            background-color: #b04343;
            border-color: #b04343;
        }
        .form-group {
            margin-bottom: 20px;
        }
        img {
            margin-top: 10px;
            max-width: 100%;
            height: auto;
        }
        ul {
            list-style-type: none;
            padding-left: 0;
        }
        ul li {
            margin-bottom: 10px;
            padding-left: 20px;
            position: relative;
        }
        ul li:before {
            content: '\2022';
            color: #C44E4E;
            font-weight: bold;
            display: inline-block;
            width: 1em;
            margin-left: -1em;
            position: absolute;
            left: 0;
            top: 1px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Consult Type of rooms</h2>
        <div class="form-group">
            <label for="type_chambre">Type of rooms</label>
            <input type="text" class="form-control" id="type_chambre" name="type_chambre" value="<?= htmlspecialchars($type['type_chambre']) ?>" readonly>
        </div>
        <div class="form-group">
            <label for="description_type">Description</label>
            <textarea class="form-control" id="description_type" name="description_type" readonly><?= htmlspecialchars($type['description_type']) ?></textarea>
        </div>
        <div class="form-group">
            <label for="photo">Photo</label>
            <input type="text" class="form-control" id="photo" name="photo" value="<?= htmlspecialchars($type['photo']) ?>" readonly>
        </div>
        <img src="../../assets/images/<?= htmlspecialchars($type['photo']) ?>" alt="Photo du Type de Chambre">
        
        <!-- Affichage des numéros des chambres ayant ce type -->
        <div class="form-group">
            <label>List of Rooms with this Type</label>
            <ul>
                <?php foreach ($chambres as $chambre) : ?>
                    <li><?= htmlspecialchars($chambre) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>

        <br><br>
        <a href="list.php" class="btn btn-secondary">Back</a>
    </div>

    <!-- JavaScript libraries (Bootstrap, jQuery) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
