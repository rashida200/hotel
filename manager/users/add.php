<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #C4AD8E;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .card {
            background-color: #FFFFFF;
            border-radius: 20px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 80%; /* Adjust width as needed */
            margin: auto; /* Center horizontally */
            position: relative;
        }
        .btn-custom {
            background-color: #63101A;
            color: white;
            width: 100%;
            margin-top: 20px;
        }
        .btn-custom:hover {
            background-color: #B72928;
        }
        .btn-secondary-custom {
            background-color: #0E1A27; /* New color for the second button */
            color: white;
            width: 100%;
            margin-top: 20px;
        }
        .btn-secondary-custom:hover {
            background-color: #12263F;
        }
        .form-label {
            color: #63101A;
            font-weight: bold;
        }
        .form-control {
            border-radius: 5px;
            border-color: #ccc;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .alert {
            margin-top: 20px;
        }
        .form-header {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
            color: #63101A;
        }
        .add-reservation-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: #4CAF50; /* Green color */
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            display: inline-block;
        }
        .add-reservation-btn:hover {
            background-color: #45a049; /* Darker green */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h2 class="form-header">Select Your Role: Join Us as a Manager, Receptionist, or Cashier!</h2>
            <?php
            require_once "../../config/database.php";

            if (isset($_POST["add"])) {
                if (!empty($_POST["lastname"]) && !empty($_POST["firstname"]) && !empty($_POST["username"]) && !empty($_POST["password"]) && !empty($_POST["type"]) && !empty($_POST["status"])) {

                    // Extract form data
                    $lastname = $_POST["lastname"];
                    $firstname = $_POST["firstname"];
                    $username = $_POST["username"];
                    $password = $_POST["password"];
                    $type = $_POST["type"];
                    $status = $_POST["status"];

                    // Check if image file is uploaded
                    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                        $image_name = $_FILES['image']['name'];
                        $image_tmp = $_FILES['image']['tmp_name'];
                        $image_path = "../../assets/images/"; // Define your upload directory
                        $image_destination = $image_path . $image_name;

                        // Move uploaded file to specified destination
                        if (move_uploaded_file($image_tmp, $image_destination)) {
                            // Insert user data and image path into database
                            $sqli = $connexion->prepare("INSERT INTO users_app (nom, prenom, username, password, type, etat, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
                            if ($sqli->execute([$lastname, $firstname, $username, $password, $type, $status, $image_name])) {
                                echo '<div class="alert alert-success text-center">The user has been successfully added!</div>';
                            } else {
                                echo '<div class="alert alert-danger text-center">Error inserting data into the database.</div>';
                            }
                        } else {
                            echo '<div class="alert alert-danger text-center">Error uploading image: ' . $_FILES['image']['error'] . '</div>';
                        }
                    } else {
                        echo '<div class="alert alert-danger text-center">Please select an image.</div>';
                    }
                } else {
                    echo '<div class="alert alert-danger text-center">Please fill in all fields.</div>';
                }
            }
            ?>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="lastname" class="form-label">Last Name:</label>
                    <input type="text" name="lastname" id="lastname" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="firstname" class="form-label">First Name:</label>
                    <input type="text" name="firstname" id="firstname" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="username" class="form-label">Username:</label>
                    <input type="text" name="username" id="username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="password" class="form-label">Password:</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="type" class="form-label">Role:</label>
                    <select name="type" id="type" class="form-control" required>
                        <option value="Manager">Manager</option>
                        <option value="Receptionist">Receptionist</option>
                        <option value="Cashier">Cashier</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="status" class="form-label">Status:</label>
                    <select name="status" id="status" class="form-control" required>
                        <option value="Active">Active</option>
                        <option value="Blocked">Blocked</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="image" class="form-label">Image:</label>
                    <input type="file" name="image" id="image" class="form-control">
                </div>
                <button type="submit" name="add" class="btn btn-custom">Add User</button>
            </form>

            <a href="\hotel\manager\users\list.php" class="btn btn-secondary-custom btn-block mt-3">Back to User List</a>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
