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
                //if is valid userName then check if in use
                $resultCheckUser = $GLOBALS['dataLayer']->getUser($_POST['userName']);
                var_dump($resultCheckUser);

                if(($resultCheckUser) == 0) {
                    $userName = $_POST['userName'];
                }else {
                    $this->_f3->set('errors["userName"]', "User name not available");
                }
            } else {
                $this->_f3->set('errors["userName"]', "Please enter a valid user name");
            }
            if (Validate3::validEmail($_POST['email'])) {
                $email = $_POST['email'];
            } else {
                $this->_f3->set('errors["email"]', "Please enter a valid email");
            }
            if(Validate3::validPassword($_POST['password'])){
                if(Validate3::passwordMatch($_POST['password'], $_POST['password-confirm'])){
                    $password = $_POST['password'];
                    // hash the valid password
                    $hashPass = password_hash($password, PASSWORD_DEFAULT);

                } else {
                    $this->_f3->set('errors["password"]', "Passwords must match");
                }
            } else {
                $this->_f3->set('errors["password"]', "Please enter a valid password, between 8-16 characters, must 
                include at least 1 number");
            }


            // check no errors
            if (empty($this->_f3->get('errors'))){
                $user = new Member($userName, $email, $hashPass);
                var_dump($user);

                $this->_f3->set('SESSION.user', $user);

                // send new user info to database
                $id = $GLOBALS['dataLayer']->addUser($user);
                $user->setUserId($id);
                echo "User {{ @$user->getUserId();}} inserted successfully"; // delete after testing

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
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $userName = $_POST['username'];
            $plainTextPass = $_POST['password'];

            $resultUser = $GLOBALS['dataLayer']->getUser($userName);

            if ($resultUser != 0) {

                $retrievedHashPass = $resultUser['Password'];

                $verifyPass = password_verify($plainTextPass, $retrievedHashPass);

                if ((strtolower($resultUser['UserName']) === strtolower($userName)) && $verifyPass && $resultUser['isDeleted'] != 1) {

                    $user = new Member($userName, $resultUser['Email'], $retrievedHashPass, $resultUser['UserId']);

                    $this->_f3->set('SESSION.user', $user);
//                    var_dump($user);

                    $this->_f3->reroute('/library');
                } else {
                    $this->_f3->set('errors["logIn"]', "Incorrect username or password, please try again.");
                }
            } else {
                $this->_f3->set('errors["logIn"]', "Incorrect username or password, please try again.");
            }
        }

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
        // define variables
        $plantName = $speciesName = $waterPeriod = $lastWatered = $adoptionDate = $imagePath = "";
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            //handle file upload TODO Check if code is working
            if($_FILES["uploadFile"]["size"]!=0){

                $target_dir ="uploads/";
                $target_file = $target_dir . basename($_FILES["uploadFile"]["name"]);
//                var_dump($target_file);
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                //validate image
                $uploadOk = Validate3::validFile($_FILES["uploadFile"], $target_file, $imageFileType);
                if ($uploadOk){
                    if (move_uploaded_file($_FILES["uploadFile"]["tmp_name"], $target_file)) {
                        $imagePath = $target_file;
                    }
                }else{
                    $this->_f3->set('errors["file"]', "File is not valid.");
                }

            }

            //validate all plant Info
            if (Validate3::validName($_POST['plantName'])){
                $plantName = $_POST['plantName'];
            }else {
                $this->_f3->set('errors["plantName"]', "Please enter a valid name");
            }
            if(!empty($POST['speciesName'])){
                if(Validate3::validName($POST['speciesName'])){
                    $speciesName = $POST['speciesName'];
                }else {
                    $this->_f3->set('errors["speciesName"]', "Please enter a valid name");
                }
            }
            if(is_numeric($_POST['waterPeriod']) && strlen($_POST['waterPeriod']) >= 1 && strlen($_POST['waterPeriod']) <= 2){
                $waterPeriod = $_POST['waterPeriod'];
            } else {
                $this->_f3->set('errors["waterPeriod"]', "Please enter a number between 0-99");
            }
            //TODO Validate Dates maybe??

            //get member ID out of session data
            $memberId = $this->_f3->get('SESSION.user')->getUserId();


            //TODO if no errors call sql add methods
            if (empty($this->_f3->get('errors'))) {
                $plant = new Plant($memberId, $plantName, $speciesName, $waterPeriod, $lastWatered, $adoptionDate, "", $imagePath);

                // add new plant to Plants table
                $id = $GLOBALS['dataLayer']->addPlant($plant);
                $plant->setPlantId($id);
                echo "Plant $id inserted successfully"; // delete after testing

                // add new image to PlantPics table  TODO create an Image class??
                $id = $GLOBALS['dataLayer']->addImage($plant);
                // $this->_f3->set('ID', $id); // I don't think this is necessary?
                echo "Image $id inserted successfully"; // delete after testing

            }

            //TODO redirect to library

        }

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