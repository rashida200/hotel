<?php
require "../../config/database.php"; 

$id_chambre = isset($_GET['id_chambre']) ? intval($_GET['id_chambre']) : 0;

if ($id_chambre == 0) {
    echo "Invalid room ID.";
    exit();
}

try {
    $stmt = $connexion->prepare("SELECT * FROM chambre WHERE id_chambre = ?");
    $stmt->execute([$id_chambre]);
    $chambre = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$chambre) {
        echo "room not found.";
        exit();
    }

    // Récupération des types de chambre
    $type_chambre_query = "SELECT * FROM type_chambre";
    $type_chambre_result = $connexion->query($type_chambre_query);

    // Récupération des capacités de chambre
    $capacite_chambre_query = "SELECT * FROM capacite_chambre";
    $capacite_chambre_result = $connexion->query($capacite_chambre_query);

    // Récupération des tarifs de chambre
    $tarif_chambre_query = "SELECT * FROM tarif_chambre";
    $tarif_chambre_result = $connexion->query($tarif_chambre_query);

} catch (PDOException $e) {
    die("Error retrieving chambre: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Chambre</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
            padding: 50px;
        }
        .form-container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        .btn-go-back {
            background-color: #63101A;
            color: #fff;
            border: none;
        }
        .btn-go-back:hover {
            background-color: #4e0813;
        }
        label {
            color: #63101A;
        }
        h2 {
            color: #63101A;
            font-family: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="form-container">
                    <h2>Edit Room</h2>
                    <form action="list.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id_chambre" value="<?php echo htmlspecialchars($chambre['id_chambre']); ?>">
                        
                        <div class="form-group">
                            <label for="numero_chambre">Room number</label>
                            <input type="text" class="form-control" id="numero_chambre" name="numero_chambre" value="<?php echo htmlspecialchars($chambre['numero_chambre']); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="nombre_adultes_enfants_ch">Number of Adults and Children</label>
                            <input type="number" class="form-control" id="nombre_adultes_enfants_ch" name="nombre_adultes_enfants_ch" value="<?php echo htmlspecialchars($chambre['nombre_adultes_enfants_ch']); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="renfort_chambre">Room Reinforcement</label>
                            <input type="checkbox" id="renfort_chambre" name="renfort_chambre" <?php echo $chambre['renfort_chambre'] ? 'checked' : ''; ?>>
                        </div>
                        
                        <div class="form-group">
                            <label for="etage_chambre">Room Floor</label>
                            <input type="number" class="form-control" id="etage_chambre" name="etage_chambre" value="<?php echo htmlspecialchars($chambre['etage_chambre']); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="nbr_lits_chambre">Number of Beds</label>
                            <input type="number" class="form-control" id="nbr_lits_chambre" name="nbr_lits_chambre" value="<?php echo htmlspecialchars($chambre['nbr_lits_chambre']); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="id_tarif">Room rate</label>
                            <select class="form-control" id="id_tarif" name="id_tarif" required>
                                <?php while ($row = $tarif_chambre_result->fetch(PDO::FETCH_ASSOC)): ?>
                                    <option value="<?php echo htmlspecialchars($row['id_tarif']); ?>" <?php echo $row['id_tarif'] == $chambre['id_tarif'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($row['prix_base_nuit']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="id_capacite">Room Capacity</label>
                            <select class="form-control" id="id_capacite" name="id_capacite" required>
                                <?php while ($row = $capacite_chambre_result->fetch(PDO::FETCH_ASSOC)): ?>
                                    <option value="<?php echo htmlspecialchars($row['id_capacite']); ?>" <?php echo $row['id_capacite'] == $chambre['id_capacite'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($row['titre_capacite']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="id_type_ch">Type of rooms</label>
                            <select class="form-control" id="id_type_ch" name="id_type_ch" required>
                                <?php while ($row = $type_chambre_result->fetch(PDO::FETCH_ASSOC)): ?>
                                    <option value="<?php echo htmlspecialchars($row['id_type_ch']); ?>" <?php echo $row['id_type_ch'] == $chambre['id_type_ch'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($row['type_chambre']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>

                    <!-- Bouton pour retourner à list.php -->
                    <a href="list.php" class="btn btn-go-back mt-3">Return to list</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
// Fermer la connexion PDO (si vous n'utilisez pas de connexions persistantes)
$connexion = null;
?>
