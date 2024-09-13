<?php
require "../../config/database.php";

// Function to fetch all room data
function fetchRooms($connexion, $filters = [])
{
    $sql = "SELECT chambre.*, 
                   type_chambre.type_chambre, type_chambre.description_type,
                   tarif_chambre.prix_base_nuit, tarif_chambre.prix_base_passage,
                   capacite_chambre.titre_capacite, capacite_chambre.numero_capacite,
                   client.id_client, client.nom_complet, client.email,
                   reservation.etat
            FROM chambre
            LEFT JOIN type_chambre ON chambre.id_type_ch = type_chambre.id_type_ch
            LEFT JOIN tarif_chambre ON chambre.id_tarif = tarif_chambre.id_tarif
            LEFT JOIN capacite_chambre ON chambre.id_capacite = capacite_chambre.id_capacite
            LEFT JOIN reservation ON chambre.id_chambre = reservation.id_chambre
            LEFT JOIN client ON reservation.id_client = client.id_client
            WHERE 1 ";

    $params = [];

    // Apply filters if provided
    if (!empty($filters)) {
        if (!empty($filters['numero_chambre'])) {
            $sql .= "AND chambre.numero_chambre LIKE :numero_chambre ";
            $params[':numero_chambre'] = '%' . $filters['numero_chambre'] . '%';
        }

        if (!empty($filters['type_chambre'])) {
            $sql .= "AND chambre.id_type_ch = :id_type_ch ";
            $params[':id_type_ch'] = $filters['type_chambre'];
        }

        if (!empty($filters['capacite_chambre'])) {
            $sql .= "AND chambre.id_capacite = :id_capacite ";
            $params[':id_capacite'] = $filters['capacite_chambre'];
        }

        if (!empty($filters['date_debut']) && !empty($filters['date_fin'])) {
            $sql .= "AND NOT EXISTS (
                        SELECT *
                        FROM reservation
                        WHERE chambre.id_chambre = reservation.id_chambre
                          AND (reservation.date_depart >= :date_debut AND reservation.date_arrivee <= :date_fin)
                      ) ";
            $params[':date_debut'] = $filters['date_debut'];
            $params[':date_fin'] = $filters['date_fin'];
        }

        if (!empty($filters['prix_min'])) {
            $sql .= "AND tarif_chambre.prix_base_nuit >= :prix_min ";
            $params[':prix_min'] = $filters['prix_min'];
        }

        if (!empty($filters['prix_max'])) {
            $sql .= "AND tarif_chambre.prix_base_nuit <= :prix_max ";
            $params[':prix_max'] = $filters['prix_max'];
        }
    }

    $sql .= "GROUP BY chambre.id_chambre";

    $stmt = $connexion->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch all room data without filters initially
$chambresData = fetchRooms($connexion);

// Fetching distinct types and capacities for dropdowns
$stmtTypes = $connexion->query("SELECT * FROM type_chambre");
$stmtCapacites = $connexion->query("SELECT * FROM capacite_chambre");

$typesChambres = $stmtTypes->fetchAll(PDO::FETCH_ASSOC);
$capacitesChambres = $stmtCapacites->fetchAll(PDO::FETCH_ASSOC);

// Handling form submission for search
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['submit'])) {
    $filters = [
        'numero_chambre' => $_GET['numero_chambre'] ?? '',
        'type_chambre' => $_GET['type_chambre'] ?? '',
        'capacite_chambre' => $_GET['capacite_chambre'] ?? '',
        'date_debut' => $_GET['date_debut'] ?? '',
        'date_fin' => $_GET['date_fin'] ?? '',
        'prix_min' => $_GET['prix_min'] ?? '',
        'prix_max' => $_GET['prix_max'] ?? '',
    ];

    // Fetch rooms based on filters
    $chambresData = fetchRooms($connexion, $filters);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Chambres</title>
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
        .btn-info {
            background-color: #17a2b8;
            border-color: #17a2b8;
        }
        .btn-info:hover {
            background-color: #138496;
            border-color: #117a8b;
        }
        .fa {
            margin-right: 5px;
        }
        .btn-back {
            background-color: #6c757d;
            border-color: #6c757d;
        }
        .btn-back:hover {
            background-color: #5a6268;
            border-color: #545b62;
        }
        .btn-add {
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-add:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Gestion des Chambres</h2>

        <!-- Buttons for adding and going back to Dashboard -->
        <div class="mb-3 d-flex justify-content-between">
            <div>
                <a href="create.php" class="btn btn-success btn-add"><i class="fas fa-plus"></i> Add a room</a>
            </div>
            <div>
                <a href="/hotel/receptionist/index.php" class="btn btn-secondary btn-back"><i class="fas fa-arrow-left"></i> Return to Dashboard</a>
            </div>
        </div>

        <!-- Search Form -->
        <form method="get" action="" class="mb-3">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="numero_chambre">Room number</label>
                    <input type="text" class="form-control" id="numero_chambre" name="numero_chambre" value="<?= isset($_GET['numero_chambre']) ? htmlspecialchars($_GET['numero_chambre']) : '' ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="type_chambre">Type of rooms</label>
                    <select class="form-control" id="type_chambre" name="type_chambre">
                        <option value="">Tous</option>
                        <?php foreach ($typesChambres as $type): ?>
                            <option value="<?= $type['id_type_ch'] ?>" <?= (isset($_GET['type_chambre']) && $_GET['type_chambre'] == $type['id_type_ch']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($type['type_chambre']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="capacite_chambre">Room Capacity </label>
                    <select class="form-control" id="capacite_chambre" name="capacite_chambre">
                        <option value="">Tous</option>
                        <?php foreach ($capacitesChambres as $capacite): ?>
                            <option value="<?= $capacite['id_capacite'] ?>" <?= (isset($_GET['capacite_chambre']) && $_GET['capacite_chambre'] == $capacite['id_capacite']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($capacite['titre_capacite']) ?> (<?= htmlspecialchars($capacite['numero_capacite']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label>Availability Period</label>
                    <div class="row">
                        <div class="col">
                            <input type="date" class="form-control" name="date_debut" value="<?= isset($_GET['date_debut']) ? htmlspecialchars($_GET['date_debut']) : '' ?>">
                        </div>
                        <div class="col">
                            <input type="date" class="form-control" name="date_fin" value="<?= isset($_GET['date_fin']) ? htmlspecialchars($_GET['date_fin']) : '' ?>">
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="prix_min">Min Price</label>
                    <input type="number" class="form-control" id="prix_min" name="prix_min" value="<?= isset($_GET['prix_min']) ? htmlspecialchars($_GET['prix_min']) : '' ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label for="prix_max">Max Price</label>
                    <input type="number" class="form-control" id="prix_max" name="prix_max" value="<?= isset($_GET['prix_max']) ? htmlspecialchars($_GET['prix_max']) : '' ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label></label>
                    <button type="submit" name="submit" class="btn btn-primary btn-block">Search</button>
                </div>
            </div>
        </form>

        <!-- Table to display rooms -->
        <table class="table table-striped">
            <thead>
                <tr>
                <th>Room Number</th>
<th>Room Type</th>
<th>Nightly Price</th>
<th>Hourly Rate</th> <!-- If "Prix de Passage" refers to a rate for short stays -->
<th>Room Capacity</th>
<th>Number of Beds</th>
<th>Floor</th>
<th>Number of People (Adults / Children)</th>
<th>Clients</th>
<th>Actions</th>
                </tr>
            </thead>
            <tbody id="chambresList">
                <?php foreach ($chambresData as $data): ?>
                    <tr>
                        <td><?= htmlspecialchars($data['numero_chambre']) ?></td>
                        <td><?= htmlspecialchars($data['type_chambre']) ?></td>
                        <td><?= htmlspecialchars($data['prix_base_nuit']) ?> DH</td>
                        <td><?= htmlspecialchars($data['prix_base_passage']) ?> DH</td>
                        <td><?= htmlspecialchars($data['titre_capacite']) ?></td>
                        <td><?= htmlspecialchars($data['nbr_lits_chambre']) ?></td>
                        <td><?= htmlspecialchars($data['etage_chambre']) ?></td>
                        <td><?= htmlspecialchars($data['nombre_adultes_enfants_ch']) ?></td>
                        <td>
                            <?php if (!empty($data['nom_complet'])): ?>
                                <?= htmlspecialchars($data['nom_complet']) ?> (<?= htmlspecialchars($data['email']) ?>)
                            <?php else: ?>
                                No customers
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="edit.php?id_chambre=<?= htmlspecialchars($data['id_chambre']) ?>" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Modify</a>
                            <a href="delete.php?id_chambre=<?= htmlspecialchars($data['id_chambre']) ?>" class="btn btn-danger btn-sm btnDelete"><i class="fas fa-trash"></i>Delete</a>
                            <a href="view.php?id_chambre=<?= htmlspecialchars($data['id_chambre']) ?>" class="btn btn-secondary btn-sm btnDetails"><i class="fas fa-eye"></i> Détails</a>
                            <a href="history.php?id_chambre=<?= htmlspecialchars($data['id_chambre']) ?>" class="btn btn-info btn-sm"><i class="fas fa-history"></i> History</a>
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
