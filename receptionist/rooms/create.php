<?php
// Include database configuration file
require_once '../../config/database.php';

try {
    // Fetch types of rooms from the database
    $sqlTypes = "SELECT * FROM type_chambre";
    $stmtTypes = $connexion->prepare($sqlTypes);
    $stmtTypes->execute();
    $typesChambres = $stmtTypes->fetchAll(PDO::FETCH_ASSOC);

    // Fetch capacities from the database
    $sqlCapacities = "SELECT * FROM capacite_chambre";
    $stmtCapacities = $connexion->prepare($sqlCapacities);
    $stmtCapacities->execute();
    $capacitesChambres = $stmtCapacities->fetchAll(PDO::FETCH_ASSOC);

    // Fetch room prices from the database
    $sqlTarifs = "SELECT * FROM tarif_chambre";
    $stmtTarifs = $connexion->prepare($sqlTarifs);
    $stmtTarifs->execute();
    $tarifsChambres = $stmtTarifs->fetchAll(PDO::FETCH_ASSOC);

    $message = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Handle file upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image_name = basename($_FILES['image']['name']);
            $image_tmp = $_FILES['image']['tmp_name'];
            $image_path = "../../assets/images/"; // Define your upload directory
            $image_destination = $image_path . $image_name;

            if (move_uploaded_file($image_tmp, $image_destination)) {
                // Retrieve form data (sanitize and validate as needed)
                $numero_chambre = htmlspecialchars($_POST['numero_chambre']);
                $nombre_adultes_enfants_ch = htmlspecialchars($_POST['nombre_adultes_enfants_ch']);
                $renfort_chambre = $_POST['renfort_chambre'] === 'true' ? 1 : 0;
                $etage_chambre = htmlspecialchars($_POST['etage_chambre']);
                $nbr_lits_chambre = htmlspecialchars($_POST['nbr_lits_chambre']);
                $photo = $image_name;
                $id_type_ch = htmlspecialchars($_POST['id_type_ch']);
                $id_capacite = htmlspecialchars($_POST['id_capacite']);
                $id_tarif = htmlspecialchars($_POST['id_tarif']);

                // Prepare INSERT statement
                $sql = "INSERT INTO chambre (numero_chambre, nombre_adultes_enfants_ch, renfort_chambre, etage_chambre, nbr_lits_chambre, photo, id_type_ch, id_capacite, id_tarif) 
                        VALUES (:numero_chambre, :nombre_adultes_enfants_ch, :renfort_chambre, :etage_chambre, :nbr_lits_chambre, :photo, :id_type_ch, :id_capacite, :id_tarif)";
                $stmt = $connexion->prepare($sql);

                // Bind parameters
                $stmt->bindParam(':numero_chambre', $numero_chambre);
                $stmt->bindParam(':nombre_adultes_enfants_ch', $nombre_adultes_enfants_ch);
                $stmt->bindParam(':renfort_chambre', $renfort_chambre);
                $stmt->bindParam(':etage_chambre', $etage_chambre);
                $stmt->bindParam(':nbr_lits_chambre', $nbr_lits_chambre);
                $stmt->bindParam(':photo', $photo);
                $stmt->bindParam(':id_type_ch', $id_type_ch);
                $stmt->bindParam(':id_capacite', $id_capacite);
                $stmt->bindParam(':id_tarif', $id_tarif);

                // Execute the statement
                if ($stmt->execute()) {
                    $message = "Room added successfully!";
                } else {
                    $message = "Erreur lors de l'ajout de la chambre: " . $stmt->errorInfo()[2];
                }
            } else {
                $message = "Erreur lors du téléchargement de l'image.";
            }
        } else {
            $message = "Veuillez sélectionner une image valide.";
        }
    }
} catch (Exception $e) {
    $message = "Erreur: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>add a room</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .form-container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .form-container h2 {
            margin-bottom: 20px;
        }
        .form-group label {
            font-weight: bold;
        }
        .form-control:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0069d9;
            border-color: #0062cc;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <h2>Add a room</h2>
            <?php if (!empty($message)) : ?>
                <div class="alert alert-info"><?= $message ?></div>
            <?php endif; ?>
            <form id="addChambreForm" method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="numeroChambre">Room number</label>
                    <input type="number" class="form-control" id="numeroChambre" name="numero_chambre" maxlength="10" required>
                </div>
                <div class="form-group">
                    <label for="nombreAdultesEnfants">Number of Adults and Children</label>
                    <input type="number" class="form-control" id="nombreAdultesEnfants" name="nombre_adultes_enfants_ch" required>
                </div>
                <div class="form-group">
                    <label for="renfortChambre">Room Reinforcement</label>
                    <select class="form-control" id="renfortChambre" name="renfort_chambre" required>
                        <option value="true">Yes</option>
                        <option value="false">No</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="etageChambre">Room floor</label>
                    <input type="number" class="form-control" id="etageChambre" name="etage_chambre" required>
                </div>
                <div class="form-group">
                    <label for="nbrLitsChambre">Number of Beds</label>
                    <input type="number" class="form-control" id="nbrLitsChambre" name="nbr_lits_chambre" required>
                </div>
                <div class="form-group">
                    <label for="photo">Photo</label>
                    <input type="file" class="form-control" id="photo" name="image" accept="image/*" required>
                </div>
                <div class="form-group">
                    <label for="typeChambre">Room Type</label>
                    <select class="form-control" id="typeChambre" name="id_type_ch" required>
                        <?php foreach ($typesChambres as $type) : ?>
                            <option value="<?= $type['id_type_ch'] ?>"><?= $type['type_chambre'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="capaciteChambre">Room apacity</label>
                    <select class="form-control" id="capaciteChambre" name="id_capacite" required>
                        <?php foreach ($capacitesChambres as $capacite) : ?>
                            <option value="<?= $capacite['id_capacite'] ?>"><?= $capacite['titre_capacite'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="tarifChambre">Room Rate</label>
                    <select class="form-control" id="tarifChambre" name="id_tarif" required>
                        <?php foreach ($tarifsChambres as $tarif) : ?>
                            <option value="<?= $tarif['id_tarif'] ?>"><?= $tarif['prix_base_nuit'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary btn-block">Add Room</button>
            </form>
            <a href="list.php" class="btn btn-secondary btn-block mt-3">See the room list</a>
        </div>
    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
