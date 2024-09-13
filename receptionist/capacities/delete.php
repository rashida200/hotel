<?php
require "../../config/database.php";

if (isset($_GET['id_capacite'])) {
    $id = $_GET['id_capacite'];

    // Check if the capacity is linked to any chambre
    $sql_check = $connexion->prepare("SELECT COUNT(*) AS count_chambres FROM chambre WHERE id_capacite = :id");
    $sql_check->bindParam(':id', $id);
    $sql_check->execute();
    $result = $sql_check->fetch(PDO::FETCH_ASSOC);

    if ($result['count_chambres'] > 0) {
        // Capacity is linked to at least one chambre
        echo '<script>alert("Prohibited operation: Capacity already linked to a room."); window.history.back();</script>';
        exit();
    } else {
        // Delete the capacity
        $sql_delete = $connexion->prepare("DELETE FROM capacite_chambre WHERE id_capacite = :id");
        $sql_delete->bindParam(':id', $id);
        $sql_delete->execute();

        // Redirect with success message
        header("Location: list.php?success=deleted");
        exit();
    }
}

// If no valid id_capacite parameter is provided
header("Location: list.php"); // Redirect to list.php if no id_capacite is provided
exit();
?>
