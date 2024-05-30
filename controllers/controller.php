<?php

/**
 * Controller class for application project
 */

class Controller3
{
    private $_f3;

    function __construct($f3)
    {
        $this->_f3 = $f3;
    }

    /**
     * renders the home view template
     * @return void
     */
    function home(): void
    {
        $view = new Template();
        echo $view->render('views/home.html');
    }

    /**
     * renders the user Sign Up view template
     * @return void
     */
    function signUp(): void
    {

        // define variables
        $userName = $email = $password = $passwordConfirm = '';

        if ($_SERVER['REQUEST_METHOD'] == "POST"){

            if(Validate3::validUserName($_POST['userName'])){
                $userName = $_POST['userName'];
            } else {
                $this->_f3->set('errors["userName"]', "Please enter a valid user name");
            }
            if (Validate3::validEmail($_POST['email'])) {
                $email = $_POST['email'];
            } else {
                $this->_f3->set('errors["email"]', "Please enter a valid email");
            }
            if(Validate3::validPassword($_POST['password'], $_POST['password-confirm'])){
                $password = $_POST['userName'];
            } else {
                $this->_f3->set('errors["userName"]', "Please enter a valid password, between 8-16 characters, must 
                include at least 1 number");
            }
            // TODO Validate that email and username is not in use


            // TODO hash the valid password


            // check no errors
            if (empty($this->_f3->get('errors'))){
                $user = new Member($userName, $email, $password);

                $this->_f3->set('SESSION.user', $user);

                //TODO send new user info to database

                $this->_f3->reroute('/');
            }

        }

        $view = new Template();
        echo $view->render('views/sign-up.html');
    }

    function signIn(): void
    {
        $view = new Template();
        echo $view->render('views/sign-in.html');
    }
}