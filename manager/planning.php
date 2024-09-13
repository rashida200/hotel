<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Reservations</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f0f0f0;
        }
        h2 {
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }
        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: 0 auto;
        }
        form label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }
        form input[type="date"], form input[type="submit"], form input[type="button"] {
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: calc(50% - 22px);
            display: inline-block;
            box-sizing: border-box;
        }
        form input[type="submit"] {
            background-color: #63101A;
            color: white;
            border: none;
            cursor: pointer;
            width: calc(100% - 22px);
        }
        form input[type="submit"]:hover {
            background-color: #45a049;
        }
        form .back-button {
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
            width: calc(100% - 22px);
            text-align: center;
        }
        form .back-button:hover {
            background-color: #0056b3;
        }
        .results-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            margin-top: 20px;
        }
        .result-item {
            background-color: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 300px;
        }
        .result-item p {
            margin: 5px 0;
        }
        .result-item p strong {
            font-weight: bold;
        }
        .no-results {
            text-align: center;
            color: #555;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h2>Search Reservations</h2>
    <form method="post" action="">
        <label for="start_date">Start Date:</label>
        <input type="date" id="start_date" name="start_date" required><br>
        <label for="end_date">End Date:</label>
        <input type="date" id="end_date" name="end_date" required><br>
        <input type="submit" value="Search">
    </form>
    
    <form method="get" action="index.php">
        <input type="submit" class="back-button" value="Back">
    </form>

    <?php
    // Assuming $connexion is your database connection object
    require "../config/database.php";
    
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];

        // Prepare and execute SQL query
        $sqli = $connexion->prepare("SELECT reservation.code_reservation, reservation.date_arrivee, reservation.date_depart, client.nom_complet, chambre.numero_chambre 
                                    FROM reservation 
                                    INNER JOIN client ON reservation.id_client = client.id_client 
                                    INNER JOIN chambre ON reservation.id_chambre = chambre.id_chambre 
                                    WHERE reservation.date_arrivee >= :start_date AND reservation.date_depart <= :end_date");
        
        $sqli->bindParam(':start_date', $start_date);
        $sqli->bindParam(':end_date', $end_date);
        $sqli->execute();
        
        $results = $sqli->fetchAll(PDO::FETCH_ASSOC);

        if ($sqli->rowCount() > 0) {
            echo "<h2>Reservations Found:</h2>";
            echo "<div class='results-container'>";
            
            foreach ($results as $row) {
                echo "<div class='result-item'>";
                echo "<p><strong>Reservation Code:</strong> " . htmlspecialchars($row['code_reservation']) . "</p>";
                echo "<p><strong>Arrival Date:</strong> " . htmlspecialchars($row['date_arrivee']) . "</p>";
                echo "<p><strong>Departure Date:</strong> " . htmlspecialchars($row['date_depart']) . "</p>";
                echo "<p><strong>Client:</strong> " . htmlspecialchars($row['nom_complet']) . "</p>";
                echo "<p><strong>Room:</strong> " . htmlspecialchars($row['numero_chambre']) . "</p>";
                echo "</div>";
            }

            echo "</div>";
        } else {
            echo "<p class='no-results'>No reservations found for this period.</p>";
        }
    }
    ?>
</body>
</html>
