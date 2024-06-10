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

$sql = "SELECT Nickname, LastWatered, wateringPeriod FROM Plants WHERE UserId = $userId";

$statement = $dbh->prepare($sql);

$statement->execute();
$plants = $statement->fetchAll(PDO::FETCH_ASSOC);


foreach ($plants as $plant) {

    $nickname = $plant['Nickname'];
    $lastWatered = $plant['LastWatered'];
    $wateringPeriod = $plant['wateringPeriod'];

    // Calculate next watering date
    $lastWateredDate = new DateTime($lastWatered);
    $nextWateredDate = clone $lastWateredDate;
    $nextWateredDate->modify("+$wateringPeriod days");
    $nextWatered = $nextWateredDate->format('Y-m-d');

    $today = new DateTime();

    // Only echo if nextWatered date is today or after today
    if ($nextWateredDate <= $today) {
        echo '
        <li class="list-group-item">
            <i class="fa-solid fa-bell"></i>
            <a href="water" class="waterBtn">Water</a>
            ' . $nickname . ' was last watered on ' . $lastWatered . '. Next watering is due on ' . $nextWatered . ' and should be watered every ' . $wateringPeriod . ' days
        </li>
        ';
    }
//    else{
//        echo "No plant due";
//    }
}

// Check if the user session is set
if ($user) {
    echo 'Current User id is : ' . htmlspecialchars($user->getUserId(), ENT_QUOTES, 'UTF-8');
} else {
    echo 'No user session found.'; }

