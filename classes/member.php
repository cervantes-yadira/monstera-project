<?php
/**
 * This file contains the Member class.
 *
 * @author Jennifer McNiel
 * @version 1.0
 */

/**
 * Represents a website member, keeping track of their id and credentials.
 *
 * @author Jennifer McNiel
 * @version 1.0
 */
class Member
{
    private $_userName, $_email, $_password, $_userId;

    /**
     * Constructs a Member object.
     *
     * @param string $_userName a members username
     * @param string $_email a members email address
     * @param string $_password a members account password
     * @param string $_userId a members user id
     */
    public function __construct( $_userName,  $_email, $_password,
                                 $_userId = ''
    ) {
        $this->_userName = $_userName;
        $this->_email = $_email;
        $this->_password = $_password;
        $this->_userId = $_userId;
    }

    /**
     * Gets a members username.
     *
     * @return string a members username
     */
    public function getUserName()
    {
        return $this->_userName;
    }

    /**
     * Sets a members username to a given username.
     *
     * @param string $userName a new username
     */
    public function setUserName($userName)
    {
        $this->_userName = $userName;
    }

    /**
     * Gets a members email address.
     *
     * @return string a members email address
     */
    public function getEmail()
    {
        return $this->_email;
    }

    /**
     * Sets a members email address to a given email address.
     *
     * @param string $email a new email address
     */
    public function setEmail($email)
    {
        $this->_email = $email;
    }

    /**
     * Gets a members account password.
     *
     * @return string a members password
     */
    public function getPassword()
    {
        return $this->_password;
    }

    /**
     * Sets a members account password to a given password.
     *
     * @param string $password a new password
     */
    public function setPassword($password)
    {
        $this->_password = $password;
    }

    /**
     * Gets a members user id.
     *
     * @return string a members user id
     */
    public function getUserId()
    {
        return $this->_userId;
    }

    /**
     * Sets a members user id to a given user id.
     *
     * @param string $userId a new user id
     */
    public function setUserId($userId)
    {
        $this->_userId = $userId;
    }
}