<?php
require "../../config/database.php";

if (isset($_GET['id_client'])) {
    $id_client = $_GET['id_client'];
    
    // Check if the client has confirmed reservations
    $checkReservations = $connexion->prepare("SELECT COUNT(*) AS reservation_count FROM reservation WHERE id_client = :id_client AND etat = 'confirmed'");
    $checkReservations->execute([":id_client" => $id_client]);
    $result = $checkReservations->fetch(PDO::FETCH_ASSOC);

    if ($result['reservation_count'] > 0) {
        echo "<script>alert('Opération interdite : Le client a des réservations confirmées en cours.');</script>";
        echo "<script>window.location.href='list.php';</script>";
        exit(); // Stop further execution
    } else {
        // No confirmed reservations, proceed with deletion
        $deleteClient = $connexion->prepare("DELETE FROM client WHERE id_client = :id_client");
        $deleteClient->execute([":id_client" => $id_client]);

        if ($deleteClient->rowCount() > 0) {
            echo "<script>alert('Client supprimé avec succès.');</script>";
            echo "<script>window.location.href='list.php';</script>";
            exit(); // Stop further execution after redirect
        } else {
            echo "<script>alert('Erreur lors de la suppression du client.');</script>";
            echo "<script>window.location.href='list.php';</script>";
            exit(); // Stop further execution after redirect
        }
    }
}
?>
