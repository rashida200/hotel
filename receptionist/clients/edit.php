<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Client</title>
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
            color: #63101A;
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
            color: #63101A;
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
            gap: 10px;
        }
        button {
            background-color: #63101A;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #4e0c14;
        }
    </style>
    <script>
        function showSuccessPopup() {
            alert("Client information has been successfully updated!");
            window.location.href = "list.php"; // Redirect after alert
        }
    </script>
</head>
<body>
    <div class="container">
        <?php
        require_once "../../config/database.php";

        // Get the client ID from the URL parameter
        $id_client = $_GET['id_client'];

        // Fetch client data from the database
        $sqll = $connexion->prepare("SELECT * FROM client WHERE id_client = ?");
        $sqll->execute([$id_client]);
        $client = $sqll->fetch(PDO::FETCH_OBJ);

        $update_successful = false;

        // Handle form submission for updating the client
        if (isset($_POST["update"])) {
            // Check if all required fields are filled
            if (!empty($_POST["name"]) && !empty($_POST["gender"]) && !empty($_POST["birthdate"]) && !empty($_POST["age"]) && !empty($_POST["country"]) && !empty($_POST["city"]) && !empty($_POST["address"]) && !empty($_POST["phone"]) && !empty($_POST["email"])) {
                // Get the form data
                $name = $_POST["name"];
                $gender = $_POST["gender"];
                $birthdate = $_POST["birthdate"];
                $age = $_POST["age"];
                $country = $_POST["country"];
                $city = $_POST["city"];
                $address = $_POST["address"];
                $phone = $_POST["phone"];
                $email = $_POST["email"];

                // Update the client data in the database
                $sqli = $connexion->prepare("UPDATE client SET nom_complet = ?, sexe = ?, date_naissance = ?, age = ?, pays = ?, ville = ?, adresse = ?, telephone = ?, email = ? WHERE id_client = ?");
                $sqli->execute([$name, $gender, $birthdate, $age, $country, $city, $address, $phone, $email, $id_client]);

                $update_successful = true;
            }
        }
        ?>
        <?php if ($update_successful): ?>
            <script>
                showSuccessPopup();
            </script>
        <?php endif; ?>
        <form action="" method="post">
           <fieldset>
            <legend>Edit Client</legend>
            <p>
                <label for="name">Full Name:</label>
                <input type="text" name="name" id="name" value="<?= htmlspecialchars($client->nom_complet ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
            </p>
            <p>
                <label>Gender:</label>
                <input type="radio" name="gender" value="Male" id="male" <?= $client->sexe == 'Male' ? 'checked' : '' ?> required>
                <label for="male">Male</label>
                <input type="radio" name="gender" value="Female" id="female" <?= $client->sexe == 'Female' ? 'checked' : '' ?> required>
                <label for="female">Female</label>
            </p>
            <p>
                <label for="birthdate">Date of Birth:</label>
                <input type="date" name="birthdate" id="birthdate" value="<?= htmlspecialchars($client->date_naissance ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
            </p>
            <p>
                <label for="age">Age:</label>
                <input type="text" name="age" id="age" value="<?= htmlspecialchars($client->age ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
            </p>
            <p>
                <label for="country">Country:</label>
                <input type="text" name="country" id="country" value="<?= htmlspecialchars($client->pays ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
            </p>
            <p>
                <label for="city">City:</label>
                <input type="text" name="city" id="city" value="<?= htmlspecialchars($client->ville ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
            </p>
            <p>
                <label for="address">Address:</label>
                <input type="text" name="address" id="address" value="<?= htmlspecialchars($client->adresse ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
            </p>
            <p>
                <label for="phone">Phone:</label>
                <input type="tel" name="phone" id="phone" value="<?= htmlspecialchars($client->telephone ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
            </p>
            <p>
                <label for="email">Email:</label>
                <input type="text" name="email" id="email" value="<?= htmlspecialchars($client->email ?? '', ENT_QUOTES, 'UTF-8') ?>">
            </p><br>
            <div class="buttons">
                <button type="submit" name="update">Update</button>
                <button type="button" onclick="location.href='list.php';">Go to Client List</button>
            </div>
           </fieldset>
        </form>
    </div>
</body>
</html>
