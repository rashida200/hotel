<?php
require "../../config/database.php";

if (isset($_GET['id_tarif'])) {
    $id = $_GET['id_tarif'];

    // Fetch current data
    $sql = $connexion->prepare("SELECT * FROM tarif_chambre WHERE id_tarif = :id");
    $sql->bindParam(':id', $id);
    $sql->execute();
    $tarif = $sql->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Update the room rate data
        $prix_base_nuit = $_POST['prix_base_nuit'];
        $prix_base_passage = $_POST['prix_base_passage'];
        $n_prix_nuit = $_POST['n_prix_nuit'];
        $n_prix_passage = $_POST['n_prix_passage'];

        $sql = $connexion->prepare("UPDATE tarif_chambre SET prix_base_nuit = :prix_base_nuit, prix_base_passage = :prix_base_passage, n_prix_nuit = :n_prix_nuit, n_prix_passage = :n_prix_passage WHERE id_tarif = :id");
        $sql->bindParam(':prix_base_nuit', $prix_base_nuit);
        $sql->bindParam(':prix_base_passage', $prix_base_passage);
        $sql->bindParam(':n_prix_nuit', $n_prix_nuit);
        $sql->bindParam(':n_prix_passage', $n_prix_passage);
        $sql->bindParam(':id', $id);
        $sql->execute();

        header("Location: list.php");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Tarif de Chambre</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Modify rate of rooms</h2>
        <form method="post">
            <div class="form-group">
                <label for="prix_base_nuit">Night Price</label>
                <input type="text" class="form-control" id="prix_base_nuit" name="prix_base_nuit" value="<?= htmlspecialchars($tarif['prix_base_nuit']) ?>" required>
            </div>
            <div class="form-group">
                <label for="prix_base_passage">Passage Price</label>
                <input type="text" class="form-control" id="prix_base_passage" name="prix_base_passage" value="<?= htmlspecialchars($tarif['prix_base_passage']) ?>" required>
            </div>
            <div class="form-group">
                <label for="n_prix_nuit">New night Price</label>
                <input type="text" class="form-control" id="n_prix_nuit" name="n_prix_nuit" value="<?= htmlspecialchars($tarif['n_prix_nuit']) ?>" required>
            </div>
            <div class="form-group">
                <label for="n_prix_passage">New passage Price</label>
                <input type="text" class="form-control" id="n_prix_passage" name="n_prix_passage" value="<?= htmlspecialchars($tarif['n_prix_passage']) ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Modify</button>
            <a href="list.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>