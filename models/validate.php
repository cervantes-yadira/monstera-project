<?php

require_once ('../models/dataLayer.php');

/**
 * Validate data for the monstera project app
 * 5/27/2024
 * @author Jennifer McNiel
 * @author Yadira Cervantes
 */
class Validate3
{

    /**
     * checks to see that a string is a valid username
     * @param $userName string name of user
     * @return bool
     */
    static function validUserName(string $userName): bool
    {
        return !empty($userName);
    }

    /**
     * checks the database for a given username
     * @param $username string name of user
     * @return bool true if username is available, false otherwise
     */
    static function validAvailableUserName(string $username): bool
    {
        $dataLayer = new DataLayer3();
        $isTaken = $dataLayer->getUser($username);

        if(!$isTaken) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * returns true if an email address is valid
     * @param $email string email address to be validated
     * @return bool
     */
    static function validEmail(string $email): bool
    {
        $sanEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
        if (!filter_var($sanEmail, FILTER_VALIDATE_EMAIL))
            return false;
        else
            return true;
    }

    static function validPassword(string $password)
    {
        $pattern = '/^(?=.*\d)(?=.*[a-zA-Z])[a-zA-Z\d!@#$%&*_\-.]{8,16}$/';

        if (preg_match($pattern, $password)) {
            return true;
        }
        return false;
    }

    static function passwordMatch(string $password, string $passwordConfirm)
    {
        if ($password == $passwordConfirm){
            return true;
        }
        return false;
    }

    static function validName(string $name): bool
    {
        return ctype_alpha($name);
    }
    /**
     * validates file including the size, target path validity and ext type
     * @param array $fileData the File object in the POST to be validated
     * @param string $targetFile the target location for the file
     * @param string $fileType the extension of the file
     * @return bool
     */
    static function validFile(array $fileData, string $targetFile, string $fileType): bool
    {
        $uploadOk = true;
        $check = getimagesize($fileData["tmp_name"]);
        if(!$check) {
            echo "File is not an image.";
            $uploadOk = false;
        }

        if (file_exists($targetFile)){
            echo "Sorry, file already exists.";
            $uploadOk = false;
        }

        if ($fileData["size"] > 1000000){
            echo "Sorry, your file is too large.";
            $uploadOk = false;
        }

        if($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg") {
            echo "Sorry, only JPG, JPEG & PNG  files are allowed.";
            $uploadOk = false;
        }

        return $uploadOk;
    }
}
