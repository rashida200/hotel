<?php
require "../../config/database.php";

if (isset($_GET['id_type_ch'])) {
    $id = $_GET['id_type_ch'];

    // Check if the room type is linked to any room
    $stmt_check = $connexion->prepare("SELECT COUNT(*) AS count FROM chambre WHERE id_type_ch = :id");
    $stmt_check->bindParam(':id', $id);
    $stmt_check->execute();
    $result = $stmt_check->fetch(PDO::FETCH_ASSOC);

    if ($result['count'] > 0) {
        // Room type is linked to at least one room, show alert and redirect
        echo '<script type="text/javascript">
                alert("Operation prohibited: Type already linked to a room.");
                window.location.href = "list.php";
              </script>';
        exit();
    }
    else {
        // Delete the room type
        $sql = $connexion->prepare("DELETE FROM type_chambre WHERE id_type_ch = :id");
        $sql->bindParam(':id', $id);
        $sql->execute();

        // Redirect to list.php after successful deletion
        header("Location: list.php");
        exit();
    }
} else {
    // Redirect to list.php if id_type_ch is not set
    header("Location: list.php");
    exit();
}
?>
