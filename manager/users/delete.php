<?php
// Check if id_user is provided and it's a positive integer
if (isset($_GET['id_user']) && is_numeric($_GET['id_user']) && $_GET['id_user'] > 0) {
    // Include your database connection file
    require_once "../../config/database.php";

    // Prepare SQL statement to delete user by id_user
    $id_user = $_GET['id_user'];
    $sql = $connexion->prepare("DELETE FROM users_app WHERE id_user = ?");
    
    // Bind parameters and execute
    $sql->execute([$id_user]);

    // Redirect back to the user list after deletion
    header("Location:\hotel\manager\users\list.php"); // Adjust the path as necessary
    exit();
} else {
    // Redirect if id_user is not provided or invalid
    header("Location:\hotel\manager\users\list.php"); // Adjust the path as necessary
    exit();
}
?>
