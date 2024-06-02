<?php

/**
 * Controller class for application project
 */

/**
 * Renders the templates for the application's views.
 *
 * @author Luke Cheng
 * @author Jennifer McNiel
 * @author Yadira Cervantes
 * @version 1.0
 */
class Controller3
{
    private $_f3;

    function __construct($f3)
    {
        $this->_f3 = $f3;
    }

    /**
     * Renders the home view template.
     * @return void
     */
    function home(): void
    {
        $view = new Template();
        echo $view->render('views/home.html');
    }

    /**
     * Renders the user sign-up view template.
     *
     * @return void
     */
    function signUp($dbh): void
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
                // hash the valid password
                $hashPass = password_hash($password, PASSWORD_DEFAULT);

            } else {
                $this->_f3->set('errors["password"]', "Please enter a valid password, between 8-16 characters, must 
                include at least 1 number");
            }
            // TODO Validate that email and username is not in use maybe encapsulate inside validation class?
            //1 define
            $sql = "SELECT * FROM PlantUsers WHERE userName = :sqlUserName";

            //2 prepare statement
//            var_dump($dbh);
            $statement = $dbh->prepare($sql);

            //3 bind parameters
            $statement->bindParam(':sqlUserName', $userName);

            //4 execute query
            $statement->execute();

            //5 (optional) process the results
            $resultCheckEmail = $statement->fetch(PDO::FETCH_ASSOC);

            if(mysqli_num_rows($resultCheckEmail) != 0) {
                $this->_f3->set('errors["userName"]', "User name not available");
            }



            // check no errors
            if (empty($this->_f3->get('errors'))){
                $user = new Member($userName, $email, $hashPass);

                $this->_f3->set('SESSION.user', $user);

                //TODO send new user info to database
                //1 define the query
                $sql = 'INSERT INTO PlantUsers (UserName, Password, Email) VALUES (:name, :pass, :email)';

                // 2 Prepare the statement
                $statement = $dbh->prepare($sql);

                //3 bind the parameters
                $statement->bindParam(':name', $userName);
                $statement->bindParam(':pass', $hashPass);
                $statement->bindParam(':email', $email);

                //4 execute the query
                $statement->execute();

                $this->_f3->reroute('/');
            }

        }

        $view = new Template();
        echo $view->render('views/sign-up.html');
    }

    /**
     * Renders the user sign-in view template.
     *
     * @return void
     */
    function signIn(): void
    {
        //        if ($_SERVER['REQUEST_METHOD'] == "POST") {
//            $userName = $_POST['username'];
//            $plainTextPass = $_POST['password'];
//            $sqlUserPass = "SELECT `userName`, `Email`, Password  FROM PlantUsers WHERE `UserName`='$userName'
//                                                                     AND `PlantUsers`.is_deleted = 0 ";
//            $result = mysqli_query($cnxn, $sqlUserPass);  //TODO Update method of querying DB
//
//            if (mysqli_num_rows($result) === 1) {
//                $row = mysqli_fetch_assoc($result);
//                $retrievedHashPass = $row['password'];
//                $verifyPass = password_verify($plainTextPass, $retrievedHashPass);
//
//                if ((strtolower($row['userName']) === strtolower($userName)) && $verifyPass) {
//
//                    $user = new Member($userName, $row['Email']);
//
//                    $this->_f3->set('SESSION.user', $user);
//
//                    $this->_f3->reroute('/');
//                } else {
//                    $this->_f3->set('errors["logIn"]', "Incorrect username or password, please try again.");
//                }
//            } else {
//                $this->_f3->set('errors["logIn"]', "Incorrect username or password, please try again.");
//            }
//        }

        $view = new Template();
        echo $view->render('views/sign-in.html');
    }

    /**
     * Renders the plant dictionary view template.
     *
     * @return void
     */
    function plantDictionary()
    {
        $view = new Template();
        echo $view->render('views/plant-dictionary.html');
    }

    /**
     * Renders the plant library view template.
     *
     * @return void
     */
    function plantLibrary()
    {
        $view = new Template();
        echo $view->render('views/plant-library.html');
    }

    /**
     * Renders the add plant view template.
     *
     * @return void
     */
    function addPlant(): void
    {
        $view = new Template();
        echo $view->render('views/add-plant.html');
    }

    /**
     * Renders the view plant view template.
     *
     * @return void
     */
    function viewPlant(): void
    {
        $view = new Template();
        echo $view->render('views/view-plant.html');
    }

    /**
     * Renders the contact us view template.
     *
     * @return void
     */
    function contactUs()
    {
        $view = new Template();
        echo $view->render('views/contact-form.html');
    }
}