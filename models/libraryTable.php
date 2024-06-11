<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$dataLayer = new DataLayer3();

$plants = $dataLayer->getPlants();

$plantObjects = array();

foreach ($plants as $plant) {
    // plant fields
    $nickname = $plant['Nickname'];
    $species = $plant['Species'];
    $lastWatered = $plant['LastWatered'];
    $plantId = $plant['PlantId'];

    echo '
        <tr>
            <td>' . $nickname . '</td>
            <td>' . $species . '</td>
            <td>' . $lastWatered . '</td>
            <td>
                <form method="POST" action="view">
                    <input hidden="hidden" value="' . $plantId . '" name="plantId">
                    <button class="nav-item nav-link" type="submit">view/edit</button>
                </form>
            </td>
        </tr>
    ';
}

//// add plantObjects array to session array
//$f3->set("SESSION.plants", $plantObjects);

// Check if the user session is set
//if ($user) {
//    echo 'Current User id is : ' . htmlspecialchars($user->getUserId(), ENT_QUOTES, 'UTF-8');
//} else {
//    echo 'No user session found.'; }
//
