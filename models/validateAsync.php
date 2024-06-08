<?php
// turn on error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

// require necessary files
require_once('validate.php');

// echoes if username is valid
if (isset($_POST['username'])) {
    $isNotEmpty = Validate3::validUserName($_POST['username']);
    $isUnique = Validate3::validAvailableUserName($_POST['username']);

    if ($isNotEmpty === false) {
        echo "Please enter a username";
    } elseif ($isUnique === false) {
        echo "Username already taken, please enter a new username";
    } else {
        echo "";
    }
}

// echoes if email is valid
if (isset($_POST['email'])) {
    $isValid = Validate3::validEmail($_POST['email']);

    if ($isValid === false) {
        echo "Please use this format: email@example.com";
    } else {
        echo "";
    }
}

// echoes if password is valid
if (isset($_POST['passwordConfirm']) && isset($_POST['password'])) {
    $password = $_POST['password'];
    $passwordConfirm = $_POST['passwordConfirm'];

    if (!empty($password) && !empty($passwordConfirm)) {
        $isValid = Validate3::validPassword($password, $passwordConfirm);

        if ($isValid === false) {
            echo "Please enter a valid password, between 8-16 characters, must include at least 1 number";
        } else {
            echo "";
        }
    } else {
        echo "Please enter both passwords";
    }
}

?>