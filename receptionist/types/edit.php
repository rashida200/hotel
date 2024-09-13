<?php
require "../../config/database.php";

if (isset($_GET['id_type_ch'])) {
    $id = $_GET['id_type_ch'];

    // Fetch current data
    $sql = $connexion->prepare("SELECT * FROM type_chambre WHERE id_type_ch = :id");
    $sql->bindParam(':id', $id);
    $sql->execute();
    $type = $sql->fetch(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Update the room type data
        $type_chambre = $_POST['type_chambre'];
        $description_type = $_POST['description_type'];
        $photo = $_POST['photo']; // You may want to handle file uploads differently

        $sql = $connexion->prepare("UPDATE type_chambre SET type_chambre = :type_chambre, description_type = :description_type, photo = :photo WHERE id_type_ch = :id");
        $sql->bindParam(':type_chambre', $type_chambre);
        $sql->bindParam(':description_type', $description_type);
        $sql->bindParam(':photo', $photo);
        $sql->bindParam(':id', $id);
        $sql->execute();

        header("Location:list.php");
        exit();
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
    <title>Modifier un Type de Chambre</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <h2>Modifiy The type of room</h2>
        <form method="post">
            <div class="form-group">
                <label for="type_chambre">Type of rooms</label>
                <input type="text" class="form-control" id="type_chambre" name="type_chambre" value="<?= htmlspecialchars($type['type_chambre']) ?>" required>
            </div>
            <div class="form-group">
                <label for="description_type">Description</label>
                <textarea class="form-control" id="description_type" name="description_type" required><?= htmlspecialchars($type['description_type']) ?></textarea>
            </div>
            <div class="form-group">
                <label for="photo">Photo (URL)</label>
                <input type="text" class="form-control" id="photo" name="photo" value="<?= htmlspecialchars($type['photo']) ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Modify</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>
</html>