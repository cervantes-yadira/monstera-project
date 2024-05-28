<?php

/**
 * Validate data for the monstera project app
 * Jennifer McNiel 5/27/2024
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

    static function validPassword(string $password, string $passwordConfirm)
    {
        $pattern = '/^(?=.*\d)(?=.*[a-zA-Z])[a-zA-Z\d!@#$%&*_\-.]{8,16}$/';

        if (preg_match($pattern, $password)) {
            if ($password == $passwordConfirm){
                return true;
            }
        }
        return false;
    }

}
