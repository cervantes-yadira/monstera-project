<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$path = $_SERVER['DOCUMENT_ROOT'] . '/../config.php';
require_once $path;

try {
    //Instantiate our PDO databse object
    $dbh = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
//    echo 'Connected to database';
}
catch (PDOException $e) {
    die($e->getMessage());
}

//Below gets the user id
$f3 = Base::instance();
$user = $f3->get('SESSION.user');
$userId = $user->getUserId();

$sql = "SELECT * FROM Plants WHERE UserId = $userId";

$statement = $dbh->prepare($sql);

$statement->execute();
$plants = $statement->fetchAll(PDO::FETCH_ASSOC);

$plantObjects = array();

foreach ($plants as $plant) {
    // plant fields
    $nickname = $plant['Nickname'];
    $species = $plant['Species'];
    $adoptionDate = $plant['AdoptionDate'];
    $wateringPeriod = $plant['WateringPeriod'];
    $lastWatered = $plant['LastWatered'];
    $plantId = $plant['PlantId'];
    $plantImages = null;

    // grab all related plant pics from PlantPics table
    $sql = "SELECT Url FROM PlantPics WHERE PlantId = $plantId";
    $statement = $dbh->prepare($sql);
    $statement->execute();

    $plantImages = $statement->fetchAll();
    $plantImages = $plantImages[0]['Url'];

    // create plant object
    $plantObject = new Plant($userId, $nickname, $species, $wateringPeriod,
        $lastWatered,$adoptionDate, $plantId, $plantImages
    );

    // add to plantObjects array
    $plantObjects[] = $plantObject;

    echo '
        <tr>
            <td>' . $nickname . '</td>
            <td>' . $species . '</td>
            <td>' . $lastWatered . '</td>
            <td><a href="view/' . $plantId . '">view/edit</a></td>
        </tr>
    ';
}

// add plantObjects array to session array
$f3->set("SESSION.plants", $plantObjects);

// Check if the user session is set
//if ($user) {
//    echo 'Current User id is : ' . htmlspecialchars($user->getUserId(), ENT_QUOTES, 'UTF-8');
//} else {
//    echo 'No user session found.'; }
//
