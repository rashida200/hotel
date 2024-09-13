<?php
// Database configuration
require "../../config/database.php";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $nom = $_POST['nom'];
    $sexe = $_POST['sexe'];
    $date = $_POST['date'];
    $age = $_POST['age'];
    $pays = $_POST['pays'];
    $ville = $_POST['ville'];
    $adresse = $_POST['adresse'];
    $telephone = $_POST['telephone'];
    $email = $_POST['email'];
    $autre = $_POST['autre'];

    // Prepare the SQL statement
    $stmt = $connexion->prepare("INSERT INTO client (nom_complet, sexe, date_naissance, age, pays, ville, adresse, telephone, email, autres_details) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Execute the query with an array of parameters
    if ($stmt->execute([$nom, $sexe, $date, $age, $pays, $ville, $adresse, $telephone, $email, $autre])) {
        echo "<script>alert('Client added successfully.'); window.location.href = 'list.php';</script>";
    } else {
        echo "Error: " . $stmt->errorInfo()[2];
    }

    // Close the statement
    $stmt = null;
}

// Close the connection (optional if PDO does it automatically at script end)
$connexion = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Client</title>
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
        }
        input[type="radio"] {
            margin-right: 10px;
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
        <form action="" method="post">
            <fieldset>
                <legend>Add Client</legend>
                <p>
                    <label for="nom">Full Name:</label>
                    <input type="text" name="nom" id="nom" required>
                </p>
                <p>
                    <label>Gender:</label>
                    <input type="radio" name="sexe" value="Male" id="homme" required>
                    <label for="homme">Male</label>
                    <input type="radio" name="sexe" value="Female" id="femme" required>
                    <label for="femme">Female</label>
                </p>
                <p>
                    <label for="date">Date of Birth:</label>
                    <input type="date" name="date" id="date" required>
                </p>
                <p>
                    <label for="age">Age:</label>
                    <input type="text" name="age" id="age" required>
                </p>
                <p>
                    <label for="pays">Country:</label>
                    <input type="text" name="pays" id="pays" required>
                </p>
                <p>
                    <label for="ville">City:</label>
                    <input type="text" name="ville" id="ville" required>
                </p>
                <p>
                    <label for="adresse">Address:</label>
                    <input type="text" name="adresse" id="adresse" required>
                </p>
                <p>
                    <label for="telephone">Phone:</label>
                    <input type="tel" name="telephone" id="telephone" required>
                </p>
                <p>
                    <label for="email">Email:</label>
                    <input type="text" name="email" id="email">
                </p>
                <p>
                    <label for="autre">Additional Details:</label>
                    <textarea name="autre" id="autre"></textarea>
                </p>
                <div class="buttons">
                    <button type="submit" name="ajout">Add</button>
                    <button type="button" onclick="location.href='list.php';">Back to List</button>
                </div>
            </fieldset>
        </form>
    </div>
</body>
</html>
