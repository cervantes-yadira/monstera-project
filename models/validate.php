<?php
/**
 * This file contains the Validate class.
 *
 * @author Jennifer Mcniel
 * @author Yadira Cervantes
 * @version 1.0
 */

require_once ('dataLayer.php');

/**
 * This class validates user input.
 *
 * @author Jennifer McNiel
 * @author Yadira Cervantes
 * @version 1.0
 */
class Validate3
{
    /**
     * Validates username by checking if it's empty.
     *
     * @param string $userName name of user
     * @return bool true if username is valid, false otherwise
     */
    static function validUserName($userName)
    {
        return !empty($userName);
    }

    /**
     * Validates username by checking if it's already taken.
     *
     * @param string $username name of user
     * @return bool true if username is available, false otherwise
     */
    static function validAvailableUserName($username)
    {
        $dataLayer = new DataLayer();
        $isTaken = $dataLayer->getUser($username);

        if (!$isTaken) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Validates email by checking if it's a valid format.
     *
     * @param string $email email address to be validated
     * @return bool true if email is valid, false otherwise
     */
    static function validEmail(string $email)
    {
        $sanEmail = filter_var($email, FILTER_SANITIZE_EMAIL);

        if (!filter_var($sanEmail, FILTER_VALIDATE_EMAIL)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Validates password by checking if it's a valid format.
     *
     * @param string $password password to be validated
     * @return bool true if password is valid, false otherwise
     */
    static function validPassword(string $password)
    {
        $pattern = '/^(?=.*\d)(?=.*[a-zA-Z])[a-zA-Z\d!@#$%&*_\-.]{8,16}$/';

        if (preg_match($pattern, $password)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Validates confirmation password by checking equality to the password.
     *
     * @param string $password first password
     * @param string $passwordConfirm password to be validated
     * @return bool true if password is valid, false otherwise
     */
    static function passwordMatch(string $password, string $passwordConfirm)
    {
        if ($password === $passwordConfirm){
            return true;
        } else {
            return false;
        }
    }

    /**
     * Validates name by checking if it consists only of alphabetic characters.
     *
     * @param string $name name to be validated
     * @return bool true if name is valid, false otherwise
     */
    static function validName(string $name)
    {
        return ctype_alpha($name);
    }

    /**
     * Validates file checking the size, target path validity, and ext type.
     *
     * @param array $fileData the File object in the POST to be validated
     * @param string $targetFile the target location for the file
     * @param string $fileType the extension of the file
     * @return bool true if image is valid, false otherwise
     */
    static function validFile(array $fileData, string $targetFile,
                              string $fileType
    ) {
        $uploadOk = true;
        $check = getimagesize($fileData["tmp_name"]);

        if (!$check) {
            echo "File is not an image.";
            $uploadOk = false;
        }

        if (file_exists($targetFile)){
            echo "Sorry, file already exists.";
            $uploadOk = false;
        }

        if ($fileData["size"] > 10000000){
            echo "Sorry, your file is too large.";
            $uploadOk = false;
        }

        if ($fileType != "jpg" && $fileType != "png" && $fileType != "jpeg") {
            echo "Sorry, only JPG, JPEG & PNG  files are allowed.";
            $uploadOk = false;
        }

        return $uploadOk;
    }

    /**
     * Validates a message by checking its length and filtering it.
     *
     * @param string $message message to be validated
     * @return string|false string if valid, false otherwise
     */
    static function validMessage(string $message)
    {
        // sanitize the message
        $message = htmlspecialchars($message);
        $message = filter_var($message, FILTER_SANITIZE_ADD_SLASHES);

        // get the message length
        $length = strlen($message);

        // check if message meets length requirements
        if ($length < 2000 && $length > 25) {
            return $message;
        } else {
            return false;
        }
    }
}