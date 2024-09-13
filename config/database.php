<?php
$host='localhost';
$username='root';
$password='';
$dbname='hotel';
$port=3306;
$charset='UTF8';
try {
    $connexion=new PDO("mysql:host=$host;dbname=$dbname;port=$port;charset=$charset;",$username,$password);
} catch (PDOException $e) {
    echo "ERROR".$e->getMessage();
}
?>