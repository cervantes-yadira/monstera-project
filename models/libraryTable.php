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

$sql = "SELECT Nickname, Species, LastWatered FROM Plants WHERE UserId = $userId";

$statement = $dbh->prepare($sql);

$statement->execute();
$plants = $statement->fetchAll(PDO::FETCH_ASSOC);


foreach ($plants as $plant) {

    $nickname = $plant['Nickname'];
    $species = $plant['Species'];
    $lastWatered = $plant['LastWatered'];

    echo '
        <tr>
            <td>' . $nickname . '</td>
            <td>' . $species . '</td>
            <td>' . $lastWatered . '</td>
            <td><a href="view">view/edit</a></td>
        </tr>
    ';

}

// Check if the user session is set
//if ($user) {
//    echo 'Current User id is : ' . htmlspecialchars($user->getUserId(), ENT_QUOTES, 'UTF-8');
//} else {
//    echo 'No user session found.'; }
//
