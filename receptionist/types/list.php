<?php
require "../../config/database.php";

// Fetching room type data
$sql = $connexion->prepare("SELECT * FROM type_chambre");
$sql->execute();
$types = $sql->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Types de Chambres</title>
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
        .table img {
            max-width: 100px;
            height: auto;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Gestion des Types de Chambres</h2>
        <div class="mb-3 d-flex justify-content-between">
            <div>
                <a href="create.php" class="btn btn-primary"><i class="fas fa-plus"></i> Ajouter un Type</a>
            </div>
            <div>
                <a href="../configuration/index.php" class="btn btn-secondary"><i class="fas fa-tachometer-alt"></i> Retour au Tableau de Bord</a>
            </div>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID Type</th>
                    <th>Type of rooms</th>
                    <th>Description</th>
                    <th>Photo</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($types as $type): ?>
                    <tr>
                        <td><?= htmlspecialchars($type['id_type_ch']) ?></td>
                        <td><?= htmlspecialchars($type['type_chambre']) ?></td>
                        <td><?= htmlspecialchars($type['description_type']) ?></td>
                        <td>
                            <img src="../../assets/images/<?= htmlspecialchars($type['photo']) ?>" alt="Photo">
                        </td>
                        <td>
                            <a href="view.php?id_type_ch=<?= htmlspecialchars($type['id_type_ch']) ?>" class="btn btn-info btn-sm"><i class="fas fa-eye"></i> Consult</a>
                            <a href="edit.php?id_type_ch=<?= htmlspecialchars($type['id_type_ch']) ?>" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Modify</a>
                            <a href="delete.php?id_type_ch=<?= htmlspecialchars($type['id_type_ch']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this type?')"><i class="fas fa-trash"></i>Delete</a>
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
