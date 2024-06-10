<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$path = $_SERVER['DOCUMENT_ROOT'].'/../config.php';
require_once $path;

try{
    //Instantiate our PDO databse object
    $dbh = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
//    echo 'Connected to database';
}
//if doesnt work, catch exception
catch (PDOException $e){
    die( $e->getMessage() );
}

$sql = "SELECT Nickname, LastWatered, wateringPeriod FROM Plants";
//$sql = "SELECT Nickname, LastWatered, wateringPeriod FROM Plant WHERE UserId = :id";

$statement = $dbh->prepare($sql);

$statement->execute();
$plants = $statement->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($plants);
