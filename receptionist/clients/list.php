<?php
require "../../config/database.php";

// Vérifier si un ID de client est passé en paramètre pour la suppression
if (isset($_GET['id_client'])) {
    $id_client = $_GET['id_client'];
    
    // Vérifier s'il existe des réservations en cours pour ce client
    $checkReservations = $connexion->prepare("SELECT COUNT(*) as reservation_count FROM reservation WHERE id_client = :id_client AND Etat = 'En cours'");
    $checkReservations->execute([":id_client" => $id_client]);
    $result = $checkReservations->fetch(PDO::FETCH_ASSOC);

    if ($result['reservation_count'] > 0) {
        echo "<script>alert('Operation prohibited: Customer has already made reservations in progress');</script>";
    } else {
        // Supprimer le client s'il n'y a pas de réservations en cours
        $deleteClient = $connexion->prepare("DELETE FROM client WHERE id_client = :id_client");
        $deleteClient->execute([":id_client" => $id_client]);

        if ($deleteClient->rowCount() > 0) {
            echo "<script>alert('Client successfully deleted');</script>";
            header("location:view.php");
            exit(); // Sortie après la redirection pour éviter toute exécution supplémentaire
        } else {
            echo "<script>alert('Erreur lors de la suppression du client');</script>";
        }
    }
}

// Recherche de clients par nom complet
if (isset($_GET['search'])) {
    $search = '%' . $_GET['search'] . '%';
    $sql = "SELECT * FROM client WHERE nom_complet LIKE :search";
    $stmt = $connexion->prepare($sql);
    $stmt->execute([":search" => $search]);
    $clients = $stmt->fetchAll(PDO::FETCH_OBJ);
} else {
    // Si aucun terme de recherche n'est spécifié, récupérer tous les clients
    $sql = "SELECT * FROM client";
    $stmt = $connexion->query($sql);
    $clients = $stmt->fetchAll(PDO::FETCH_OBJ);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Clients</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Your existing CSS styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
        }
        .sidebar {
            background-color: #63101A;
            color: white;
            padding: 20px;
            width: 250px;
            position: fixed;
            height: 100%;
            overflow: auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .container {
            margin-left: 270px; /* Increased margin to create space */
            padding: 20px;
            width: calc(100% - 270px);
        }
        .search-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .search-container form {
            display: flex;
            align-items: center;
        }
        .search-container input[type=text] {
            padding: 10px;
            margin-right: 10px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .search-container button {
            padding: 10px 20px; /* Adjust padding for better button size */
            background-color: #C44E4E;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            color: white;
        }
        .btn-add-client {
            margin-top: 0; /* No margin at the top */
            margin-left: 10px; /* Add margin to the left */
            background-color: #C44E4E;
            border-color: #C44E4E;
            padding: 10px 20px; /* Adjust padding for better button size */
        }
        .table {
            margin-top: 20px;
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
        }
        .sidebar {
            background-color: #63101A; /* Couleur de fond */
            color: white; /* Couleur du texte */
            /* padding: 20px; */
            width: 250px;
            position: fixed;
            height: 100%;
            overflow: auto;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .sidebar nav a {
            display: block;
            color: #fff;
            text-decoration: none;
            transition: background-color 0.3s;
            margin-top:150px
        }
        .sidebar nav a:hover {
            background-color: #ddd;
            color: #000;
        }
        .sidebar a#logout-link {
            position: absolute;
            bottom: 20px;
            left: 20px;
            color: #333;
            text-decoration: none;
            transition: color 0.3s;
        }
        .sidebar a#logout-link:hover {
            color: #C44E4E;
        }
        .container {
            margin-left: 270px;
            padding: 20px;
            width: calc(100% - 270px);
        }
        .table {
            margin-top: 20px;
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .table th, .table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }
        .table th {
            background-color: #C44E4E;
            color: white;
        }
        .table td {
            vertical-align: middle;
        }
        .table .actions a {
            margin-right: 5px;
            color: #C44E4E;
        }
        .table .actions a:hover {
            text-decoration: none;
        }
        .btn-add-client {
            margin-top: 20px;
            background-color: #C44E4E;
            border-color: #C44E4E;
        }
        .btn-add-client:hover {
            background-color: #b04343;
            border-color: #b04343;
        }
        .search-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .search-container form {
            display: flex;
            align-items: center;
        }
        .search-container input[type=text] {
            padding: 10px;
            margin-right: 10px;
            width: 300px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .search-container button {
            padding: 10px;
            background-color: #C44E4E;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            color: white;
        }
        .search-container button:hover {
            background-color: #b04343;
        }
        .view-client {
            color: #C44E4E;
            margin-left: 10px;
            cursor: pointer;
        }
        .view-client:hover {
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="sidebar">
            <nav>
                <a href="../index.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                <a href="registre.php"><i class="fas fa-users"></i> Client Register</a>
            </nav>
            <a href="../auth/logout.php" id="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>

    <div class="container">
        <h2>Liste of client</h2>
        
        <div class="search-container">
            <form action="" method="GET">
                <input type="text" placeholder="Rechercher par nom..." name="search">
                <button type="submit"><i class="fas fa-search"></i> Search</button>
            </form>
            <button class="btn btn-primary btn-add-client" onclick="window.location.href='add.php';">
                <i class="fas fa-user-plus"></i>Add a client
            </button>
        </div>

        <!-- Tableau des clients -->
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Country</th>
                    <th>City</th>
                    <th>Phone number</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><?= htmlspecialchars($client->nom_complet) ?></td>
                        <td><?= htmlspecialchars($client->pays) ?></td>
                        <td><?= htmlspecialchars($client->ville) ?></td>
                        <td><?= htmlspecialchars($client->telephone) ?></td>
                        <td class="actions">
                            <a href="edit.php?id_client=<?= $client->id_client ?>" title="Modifier">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="delete.php?id_client=<?= $client->id_client ?>" title="Supprimer" onclick="return confirm('Voulez-vous vraiment supprimer ce client?');">
                                <i class="fas fa-trash-alt"></i>
                            </a>
                            <a href="view.php?id_client=<?= $client->id_client ?>" class="view-client" title="Voir les informations">
                                <i class="fas fa-eye"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($clients)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center;">Aucun client trouvé.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <!-- Fin du tableau des clients -->
    </div>

    <!-- Script pour gérer la déconnexion -->
    <script>
        document.getElementById('logout-link').addEventListener('click', function(event) {
            event.preventDefault();
            if (confirm("Are you sure you wanna logout? ")) {
                window.location.href = '../auth/logout.php';
            }
        });
    </script>
    <!-- Fin du script pour gérer la déconnexion -->
</body>
</html>
