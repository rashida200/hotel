<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="" method="post">
        <p><b>Veuillez entrer le nom complet du client:</b><input type="text" name="nom" required></p>
        <p><b>Veuillez entrer le pays du client:</b><input type="text" name="pays" required></p>
        <p><b>Veuillez entrer la ville du client:</b><input type="text" name="ville" required></p>
        <button type="submit" name="chercher">Chercher</button>
    </form>

    <?php
    require_once "../../config/database.php";
    if (isset($_POST["chercher"])) {
        try {
            $name = $_POST["nom"];
            $pays = $_POST["pays"];
            $ville = $_POST["ville"];

            $sql = $connexion->prepare("SELECT client.Nom_complet, client.Pays, client.Ville, client.Telephone, reservation.Date_arrivée, reservation.Date_départ 
                FROM client 
                LEFT JOIN reservation ON client.id_client = reservation.id_client 
                WHERE client.Nom_complet = ? AND client.Pays = ? AND client.Ville = ?
            ");
            $sql->execute([$name, $pays, $ville]);

            $clients = $sql->fetchAll(PDO::FETCH_OBJ);

            if ($clients) {
                echo "<h2>Les informations sur le client $nom:</h2>";
                echo "<table border='1'>";
                echo "<tr>";
                echo "<th>Nom complet</th>";
                echo "<th>Pays</th>";
                echo "<th>Ville</th>";
                echo "<th>Numéro de Téléphone</th>";
                echo "<th>Date d'arrivée</th>";
                echo "<th>Date de départ</th>";
                echo "</tr>";

                foreach ($clients as $client) {
                    echo "<tr>";
                    echo "<td>{$client->Nom_complet}</td>";
                    echo "<td>{$client->Pays}</td>";
                    echo "<td>{$client->Ville}</td>";
                    echo "<td>{$client->Telephone}</td>";
                    echo "<td>{$client->Date_arrivée}</td>";
                    echo "<td>{$client->Date_départ}</td>";
                    echo "</tr>";
                }

                echo "</table>";
            } else {
                echo "<script>alert('Aucun client trouvé.')</script>";
            }
        } catch (PDOException $e) {
            echo "Erreur: " . $e->getMessage();
        }
    }
    ?>
</body>
</html>
