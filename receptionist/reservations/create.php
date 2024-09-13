<?php
session_start();
require_once "../../config/database.php";

$date_arrivee = $_GET['date_arrivee'] ?? '';
$date_depart = $_GET['date_depart'] ?? '';
$nbr_personnes = $_GET['nbr_personnes'] ?? '';
$user = $_GET['client'] ?? '';
$type_chambre = $_GET['type_chambre'] ?? '';

if (isset($_GET['recherche'])) {
  if (!empty($date_arrivee) && !empty($date_depart) && !empty($nbr_personnes) && !empty($user) && !empty($type_chambre)) {
    $sql = "SELECT * FROM chambre 
                INNER JOIN type_chambre ON type_chambre.id_type_ch = chambre.id_type_ch 
                WHERE chambre.id_type_ch = ? 
                AND chambre.id_chambre NOT IN ( SELECT id_chambre FROM reservation WHERE (date_arrivee <= ? AND date_depart >= ?) ) 
                AND chambre.nombre_adultes_enfants_ch >= ?";
    $params = [$type_chambre, $date_depart, $date_arrivee, $nbr_personnes];

    $statement = $connexion->prepare($sql);
    $statement->execute($params);
    $chambres = $statement->fetchAll(PDO::FETCH_OBJ);

    if ($statement->rowCount() == 0) {
      $_SESSION["message"] = "Cette Chambre n'est pas disponible maintenant.";
    }
  } else {
    $_SESSION['alert'] = '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            Tous les champs sont obligatoires
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>';
  }
}

if (isset($_POST['reserver'])) {
  $cli = $_POST['client1'];
  $cham = $_POST['chambre'];
  $date_arri = $_POST['date_arrivee'];
  $date_dep = $_POST['date_depart'];
  $nbr_per = $_POST['nbr_personnes'];

  $stmtCode = $connexion->prepare("SELECT MAX(id_reservation) AS max_id FROM reservation");
  $stmtCode->execute();
  $result = $stmtCode->fetch(PDO::FETCH_OBJ);
  $next_id = $result->max_id + 1;
  $code_reservation = "RES" . str_pad($next_id, 3, "0", STR_PAD_LEFT);

  $date_heure_reservation = date("Y-m-d H:i:s");

  $timestamp_arrivee = strtotime($date_arrivee);
  $timestamp_depart = strtotime($date_depart);
  $timestamp_now = time();

  $nbr_jours = floor(($timestamp_depart - $timestamp_arrivee) / (60 * 60 * 24));

  $s = $connexion->prepare('SELECT * FROM chambre INNER JOIN tarif_chambre ON tarif_chambre.id_tarif = chambre.id_tarif WHERE id_chambre=?');
  $s->execute([$cham]);
  $tarif = $s->fetch(PDO::FETCH_OBJ);

  $prix_nuit = $tarif->prix_base_nuit;

  $montant_total = $prix_nuit * $nbr_jours;

  if ($timestamp_arrivee > $timestamp_now && $timestamp_depart > $timestamp_now) {
    $etat = "confirmed";
  } elseif ($timestamp_arrivee <= $timestamp_now && $timestamp_depart >= $timestamp_now) {
    $etat = "ongoing";
  } elseif ($timestamp_arrivee < $timestamp_now && $timestamp_depart < $timestamp_now) {
    $etat = "completed";
  }

  $statement = $connexion->prepare("INSERT INTO reservation VALUES(NULL,?,?,?,?,?,?,?,?,?,?)");
  $statement->execute([
    $code_reservation,
    $date_heure_reservation,
    $date_arri,
    $date_dep,
    $nbr_jours,
    $nbr_per,
    $montant_total,
    $etat,
    $cli,
    $cham
  ]);

  $_SESSION['message'] = "The insertion was completed successfully.";
  header("Location:list.php");
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
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
<button class="btn btn-primary " onclick="window.location.href='list.php';">
                    <i class="fas fa-list"></i>Back to list
                </button>
  <div id="page-content-wrapper" class="main-content w-100">

    <h5 class="text-center">ADD RESERVATION :</h5>
    <?php if (isset($_SESSION['message'])) {
      echo $_SESSION['message'];
    } ?>
    <?php if (isset($_SESSION['alert'])) {
      echo $_SESSION['alert'];
    } ?>

    <form action="" method="get">

      <h6 class="form-label mt-3">Client:</h6>
      <select name="client" class="form-control mt-2">
        <option value="">Choisir un client...</option>
        <?php
        $stmtClients = $connexion->prepare("SELECT * FROM client");
        $stmtClients->execute();
        $clients = $stmtClients->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <?php foreach ($clients as $client): ?>
          <option value="<?= $client['id_client'] ?>" <?php echo ($user == $client['id_client']) ? "selected" : "" ?>>
            <?= $client['nom_complet'] ?>
          </option>
        <?php endforeach; ?>
      </select>

      <h6 class="form-label mt-3">Date of departure:</h6>
      <input type="date" class="form-control mt-2" name="date_arrivee" value="<?= $date_arrivee ?>">

      <h6 class="form-label mt-3">Arrival date:</h6>
      <input type="date" class="form-control mt-2" name="date_depart" value="<?= $date_depart ?>">

      <h6 for="" class="form-label mt-3">Room Type :</h6>
      <select name="type_chambre" class="form-control mt-2">
        <option value="">select...</option>
        <?php
        // Fetch types of rooms from the database
        $stmtTypes = $connexion->prepare("SELECT * FROM type_chambre");
        $stmtTypes->execute();
        $types = $stmtTypes->fetchAll(PDO::FETCH_ASSOC);
        ?>
        <?php foreach ($types as $type): ?>
          <option value="<?= $type['id_type_ch'] ?>" <?php echo ($type_chambre == $type['id_type_ch']) ? "selected" : "" ?>>
            <?= $type['type_chambre'] ?>
          </option>
        <?php endforeach; ?>
      </select>

      <h6 for="" class="form-label mt-3">Number of persons : </h6>
      <input type="number" class="form-control my-2 mx-1" id="roomQuantity" placeholder="Nombre des personnes"
        name="nbr_personnes" value="<?= $nbr_personnes ?>" min="1">

      <button class="btn btn-success w-100 mt-2" name="recherche" type="submit">Search</button>
    </form>

    <?php if (isset($chambres) && !empty($chambres)): ?>
      <div class="row mt-5 " id="zrour">
        <?php foreach ($chambres as $chambre): ?>
          <div class="col-md-4">
            <div class="card mb-4">
              <img src="../../assets/images/<?= $chambre->photo ?>" alt="chambre photo" class="card-img img-fluid">
              <div class="card-body">
                <h5 class="card-title">Room N°<?= $chambre->numero_chambre ?></h5>
                <p class="card-text"><strong>Type:</strong> <?= $chambre->type_chambre ?></p>
                <p class="card-text"><strong>Capacity:</strong> <?= $chambre->nombre_adultes_enfants_ch ?> personnes
                </p>
                <p class="card-text"><strong>Floor :</strong> <?= $chambre->etage_chambre ?></p>
                <form action="" method="post" style="border:none">
                  <button class="btn btn-primary w-100" type="submit" name="reserver">To book</button>
                  <input type="hidden" name="chambre" value="<?= $chambre->id_chambre ?>">
                  <input type="hidden" name="date_arrivee" value="<?= $date_arrivee ?>">
                  <input type="hidden" name="date_depart" value="<?= $date_depart ?>">
                  <input type="hidden" name="nbr_personnes" value="<?= $nbr_personnes ?>">
                  <input type="hidden" name="client1" value="<?= $user ?>">
                </form>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
  <!-- /#page-content-wrapper -->

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>