<?php
require "../../config/database.php"; 

$id_chambre = isset($_GET['id_chambre']) ? intval($_GET['id_chambre']) : 0;

if ($id_chambre == 0) {
    echo '<div id="alert-danger" class="alert alert-danger" role="alert">ID de chambre invalide.</div>';
    exit();
}

try {
    // Vérifier s'il y a des réservations pour cette chambre
    $stmt_reservation = $connexion->prepare("SELECT COUNT(*) AS count_reservations FROM reservation WHERE id_chambre = ?");
    $stmt_reservation->execute([$id_chambre]);
    $result_reservation = $stmt_reservation->fetch(PDO::FETCH_ASSOC);

    if ($result_reservation['count_reservations'] > 0) {
        echo '<script>
                alert("This room has current reservations. Unable to delete.");
                window.location.href = "list.php";
              </script>';
        return;
    }

    // Si aucune réservation trouvée, procéder à la suppression de la chambre
    $stmt_delete = $connexion->prepare("DELETE FROM chambre WHERE id_chambre = ?");
    $stmt_delete->execute([$id_chambre]);

    echo '<div id="alert-success" class="alert alert-success" role="alert">The room was successfully deleted.</div>';

} catch (PDOException $e) {
    echo '<div id="alert-danger" class="alert alert-danger" role="alert">Erreur: ' . $e->getMessage() . '</div>';
    exit();
}
?>

<!-- Exemple de lien pour retourner à la liste -->
<a href="list.php" id="btn-retour" class="btn btn-secondary">Return to list</a>
