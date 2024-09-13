<?php
require "../../config/database.php";

// Check if a client ID is passed as a parameter
if (isset($_GET['id_client'])) {
    $id_client = $_GET['id_client'];

    // Retrieve client information from the database
    $stmt = $connexion->prepare("SELECT * FROM client WHERE id_client = :id_client");
    $stmt->execute([":id_client" => $id_client]);
    $client = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$client) {
        echo "<script>alert('Customer not found'); window.location.href = 'list.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('Missing Client ID'); window.location.href = 'list.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Client Details</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        fieldset {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 5px;
        }
        legend {
            font-size: 1.5em;
            color: #C44E4E;
            padding: 0 10px;
            width: auto;
        }
        p {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="date"],
        input[type="tel"],
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-sizing: border-box;
            background-color: #f8f9fa;
            cursor: not-allowed;
        }
        textarea {
            height: 100px;
        }
        .buttons {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        button {
            background-color: #C44E4E;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #b04343;
        }
    </style>
</head>
<body>
    <div class="container">
        <form>
            <fieldset>
                <legend>View Client Details</legend>
                <p>
                    <label for="nom">Full Name:</label>
                    <input type="text" id="nom" value="<?= htmlspecialchars($client->nom_complet) ?>" disabled>
                </p>
                <p>
                    <label>Gender:</label>
                    <input type="text" value="<?= htmlspecialchars($client->sexe) ?>" disabled>
                </p>
                <p>
                    <label for="date">Date of Birth:</label>
                    <input type="date" id="date" value="<?= htmlspecialchars($client->date_naissance) ?>" disabled>
                </p>
                <p>
                    <label for="age">Age:</label>
                    <input type="text" id="age" value="<?= htmlspecialchars($client->age) ?>" disabled>
                </p>
                <p>
                    <label for="pays">Country:</label>
                    <input type="text" id="pays" value="<?= htmlspecialchars($client->pays) ?>" disabled>
                </p>
                <p>
                    <label for="ville">City:</label>
                    <input type="text" id="ville" value="<?= htmlspecialchars($client->ville) ?>" disabled>
                </p>
                <p>
                    <label for="adresse">Address:</label>
                    <input type="text" id="adresse" value="<?= htmlspecialchars($client->adresse) ?>" disabled>
                </p>
                <p>
                    <label for="telephone">Phone:</label>
                    <input type="tel" id="telephone" value="<?= htmlspecialchars($client->telephone) ?>" disabled>
                </p>
                <p>
                    <label for="email">Email:</label>
                    <input type="text" id="email" value="<?= htmlspecialchars($client->email) ?>" disabled>
                </p>
                <p>
                    <label for="autre">Additional Details:</label>
                    <textarea id="autre" disabled><?= htmlspecialchars($client->autres_details) ?></textarea>
                </p>
                <div class="buttons">
                    <button type="button" onclick="location.href='list.php';">Back to List</button>
                </div>
            </fieldset>
        </form>
    </div>
</body>
</html>
