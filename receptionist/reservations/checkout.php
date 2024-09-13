<?php
session_start();

// Redirect to login page if the user is not logged in
if (!isset($_SESSION['user'])) {
    header("Location: manager/index.php");
    exit();
}

require "../../config/database.php";

// Retrieve session data
$lastname = $_SESSION['user']['nom'] ?? 'Lastname';
$firstname = $_SESSION['user']['prenom'] ?? 'Firstname';
$image = $_SESSION['user']['image'] ?? '';

// Process check-out
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_reservation'])) {
    $id_reservation = $_POST['id_reservation'];

    try {
        // Fetch reservation details
        $stmt = $connexion->prepare("SELECT * FROM reservation 
                                    INNER JOIN client ON reservation.id_client = client.id_client
                                    INNER JOIN chambre ON reservation.id_chambre = chambre.id_chambre
                                    WHERE reservation.id_reservation = :id_reservation");
        $stmt->bindParam(':id_reservation', $id_reservation);
        $stmt->execute();
        $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($reservation) {
            // Calculate additional charges (if any)
            $additionalCharges = 0; // Implement your calculation logic here

            // Update reservation status to checked out
            $updateStmt = $connexion->prepare("UPDATE reservation SET etat = 'checked_out' WHERE id_reservation = :id_reservation");
            $updateStmt->bindParam(':id_reservation', $id_reservation);
            $updateStmt->execute();

            // Success message
            $successMessage = "Client checked out successfully.";

            // Optionally: Log the check-out action
        } else {
            // Handle error if reservation not found
            $errorMessage = "Reservation not found.";
        }
    } catch (PDOException $e) {
        die("Error checking out client: " . $e->getMessage());
    }
}

// Fetch confirmed reservations for display
try {
    $confirmedReservationsStmt = $connexion->prepare("SELECT * FROM reservation 
                                                   INNER JOIN client ON reservation.id_client = client.id_client
                                                   WHERE reservation.etat = 'confirmed'");
    $confirmedReservationsStmt->execute();
    $confirmedReservations = $confirmedReservationsStmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Error fetching confirmed reservations: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Check-Out Client</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Custom CSS -->
    <style>
        /* Custom CSS styles */
    </style>
</head>
<body>
    <div class="container">
        <h2>Check-Out Client</h2>
        <div class="mb-3">
            <a href="list.php" class="btn btn-primary"><i class="fas fa-home"></i> Back to List</a>
        </div>
        
        <!-- Display confirmed reservations -->
        <div class="card">
            <div class="card-header">
                Confirmed Reservations
            </div>
            <div class="card-body">
                <ul class="list-group">
                    <?php foreach ($confirmedReservations as $reservation): ?>
                        <li class="list-group-item">
                            <?= htmlspecialchars($reservation['nom_complet']) ?> (<?= htmlspecialchars($reservation['email']) ?>)
                            <form action="" method="post" class="float-right">
                                <input type="hidden" name="id_reservation" value="<?= $reservation['id_reservation'] ?>">
                                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-sign-out-alt"></i> Check-Out</button>
                            </form>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <!-- Display success or error message -->
        <?php if (isset($successMessage)): ?>
            <div class="alert alert-success mt-3" role="alert">
                <?= $successMessage ?>
            </div>
        <?php elseif (isset($errorMessage)): ?>
            <div class="alert alert-danger mt-3" role="alert">
                <?= $errorMessage ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Font Awesome -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>

<?php
// Close PDO connection
$connexion = null;
?>
