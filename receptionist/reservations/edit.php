<?php
session_start();
require_once "../../config/database.php";

// Check if reservation ID is provided via GET
$reservation_id = $_GET['id'] ?? '';

if (empty($reservation_id)) {
  $_SESSION['alert'] = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                          ID de réservation non spécifié.
                          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
  header("Location: list.php");
  exit;
}

// Fetch reservation details based on ID
$stmt = $connexion->prepare("SELECT * FROM reservation WHERE id_reservation = ?");
$stmt->execute([$reservation_id]);
$reservation = $stmt->fetch(PDO::FETCH_OBJ);

if (!$reservation) {
  $_SESSION['alert'] = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                          Réservation introuvable.
                          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>';
  header("Location: list.php");
  exit;
}

// Handle form submission for updating reservation
if (isset($_POST['modifier'])) {
  $date_arrivee = $_POST['date_arrivee'];
  $date_depart = $_POST['date_depart'];
  $nbr_personnes = $_POST['nbr_personnes'];
  $client = $_POST['client'];
  $chambre = $_POST['chambre'];

  // Validate and update reservation in the database
  $stmtUpdate = $connexion->prepare("UPDATE reservation SET date_arrivee=?, date_depart=?, nbr_adultes_enfants=?, id_client=?, id_chambre=? WHERE id_reservation=?");
  $stmtUpdate->execute([$date_arrivee, $date_depart, $nbr_adultes_enfants, $client, $chambre, $reservation_id]);

  $_SESSION['message'] = "The reservation has been successfully updated.";
  header("Location: list.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Modifier Réservation</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #F7F3F0;
    }
    .main-content {
      margin: 20px;
    }
    h5 {
      color: #63101A;
      font-weight: bold;
    }
    .btn-success {
      background-color: #63101A;
      border-color: #63101A;
    }
    .btn-success:hover {
      background-color: #8B242B;
      border-color: #8B242B;
    }
    .card {
      border-color: #63101A;
    }
    .card-title {
      color: #63101A;
      font-weight: bold;
    }
    .btn-primary {
      background-color: #63101A;
      border-color: #63101A;
    }
    .btn-primary:hover {
      background-color: #8B242B;
      border-color: #8B242B;
    }
    .alert-danger {
      color: #842029;
      background-color: #F8D7DA;
      border-color: #F5C2C7;
    }
  </style>
</head>

<body>

  <div id="page-content-wrapper" class="main-content w-100">

    <h5 class="text-center">MODIFY RESERVATION:</h5>
    <?php if (isset($_SESSION['message'])) {
      echo $_SESSION['message'];
    } ?>
    <?php if (isset($_SESSION['alert'])) {
      echo $_SESSION['alert'];
    } ?>

    <form action="" method="post">

      <h6 class="form-label mt-3">Client:</h6>
      <select name="client" class="form-control mt-2">
        <option value="">Select a client...</option>
        <?php
        $stmtClients = $connexion->prepare("SELECT * FROM client");
        $stmtClients->execute();
        $clients = $stmtClients->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <?php foreach ($clients as $client): ?>
          <option value="<?= $client['id_client'] ?>" <?php echo ($reservation->id_client == $client['id_client']) ? "selected" : "" ?>>
            <?= $client['nom_complet'] ?>
          </option>
        <?php endforeach; ?>
      </select>

      <h6 class="form-label mt-3"> arrival date:</h6>
      <input type="date" class="form-control mt-2" name="date_arrivee" value="<?= $reservation->date_arrivee ?>">

      <h6 class="form-label mt-3">Date of departure:</h6>
      <input type="date" class="form-control mt-2" name="date_depart" value="<?= $reservation->date_depart ?>">

      <h6 for="" class="form-label mt-3">Number of persons : </h6>
      <input type="number" class="form-control my-2 mx-1" id="roomQuantity" placeholder="Nombre des personnes"
        name="nbr_personnes" value="<?= $reservation->nbr_adultes_enfants ?>" min="1">

      <input type="hidden" name="chambre" value="<?= $reservation->id_chambre ?>">
      
      <button class="btn btn-primary w-100 mt-2" name="modifier" type="submit">To modify</button><br><br>
    
    </form>
    <button class="btn btn-primary" onclick="window.location.href='list.php';">
                    <i class="fas fa-list"></i> Back to list
                </button>
  </div>
  <!-- /#page-content-wrapper -->

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
