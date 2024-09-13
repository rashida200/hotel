<?php
require "../../config/database.php";

if (isset($_GET['id_tarif'])) {
    $id = $_GET['id_tarif'];

    // Check if the room rate is associated with any room
    $check_sql = $connexion->prepare("SELECT COUNT(*) as count_rooms FROM chambre WHERE id_tarif = :id");
    $check_sql->bindParam(':id', $id);
    $check_sql->execute();
    $result = $check_sql->fetch(PDO::FETCH_ASSOC);

    if ($result['count_rooms'] > 0) {
        // Room rate is associated with at least one room, prevent deletion
        echo '<script>alert("Operation prohibited: Rate already applied to a room."); window.history.back();</script>';
        exit();
    } else {
        // Delete the room rate if not associated with any room
        $delete_sql = $connexion->prepare("DELETE FROM tarif_chambre WHERE id_tarif = :id");
        $delete_sql->bindParam(':id', $id);
        $delete_sql->execute();

        // Redirect to the index page after deletion
        header("Location:list.php");
        exit();
    }
} else {
    // Redirect back to the index page if id_tarif is not set
    header("Location:list.php");
    exit();
}
?>
