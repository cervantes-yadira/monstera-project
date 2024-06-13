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
     * @param $dbh
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
                $this->_f3->set('errors["email"]',
                    "Please use this format: email@example.com"
                );
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
                echo "User {{ @$id }} inserted successfully"; // delete after testing

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

                    $this->_f3->reroute('library');
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
     * Signs out of the website and redirects to homepage.
     *
     * @return void
     */
    function signOut()
    {
        if($this->_f3->get("SESSION.user") !== null) {
            $this->_f3->set("SESSION.user", null);
        }

        $this->_f3->reroute('/');
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
        $user = $this->_f3->get("SESSION.user");
        $plants = $GLOBALS['dataLayer']->getPlantInfo($user);
        $userId = $user->getUserId();
        $indoorPlantObjects = [];
        $outdoorPlantObjects = [];
        $plantObjects = [];

        // Set today's date
        $today = new DateTime();
        $todayDate = $today->format('Y-m-d');

        $reminderPlant = [];

        // Turn plants from SQL query into plant objects
        foreach ($plants as $plant) {
            // grab images for the plant
            $plantId = $plant['PlantId'];
            $images = $GLOBALS['dataLayer']->getImages($plantId);
            $isIndoor = $plant['isIndoor'];

            // check if a plant has images
            empty($images) ? $images = null : $images = $images[0]["Url"];

            // Create plant object
            if($isIndoor == 0){
                $plantObject = new IndoorPlant(
                    $userId,
                    $plant['Nickname'],
                    $plant['Species'],
                    $plant['WateringPeriod'],
                    $plant['LastWatered'],
                    $plant['AdoptionDate'],
                    $plantId,
                    $images);

                // Add to indoorPlantObjects array
                $indoorPlantObjects[] = $plantObject;

            }else{
                $plantObject = new OutdoorPlant(
                    $userId,
                    $plant['Nickname'],
                    $plant['Species'],
                    $plant['WateringPeriod'],
                    $plant['LastWatered'],
                    $plant['Location'],
                    $plant['AdoptionDate'],
                    $plantId,
                    $images);

                // Add to plantObjects array
                $outdoorPlantObjects[] = $plantObject;
            }

            // Add to plantObjects array
            $plantObjects[] = $plantObject;

            // Calculate next watering date

            $lastWateredDate = new DateTime($plant['LastWatered']);
            $nextWateredDate = clone $lastWateredDate;
            $nextWateredDate->modify("+{$plant['WateringPeriod']} days");
            $nextWatered = $nextWateredDate->format('Y-m-d');

            // Only add to reminderPlant array if nextWatered date is today or before today
            if ($nextWateredDate <= $today) {
                $reminderPlant[] = [
                    'Nickname' => $plantObject->getPlantName(),
                    'NextWatered' => $nextWatered,
                    'PlantId' => $plantObject->getPlantId(),
                    'Species' => $plantObject->getSpeciesName(),
                    'LastWatered' => $plantObject->getWaterDate()
                ];
            }
        }

        // Debug: Print plantObjects and reminderPlant
//        echo "plantObjects:<br>";
//        var_dump($plantObjects);
//        echo "<br>reminderPlant:<br>";
//        print_r($reminderPlant);

        // Set plantObjects and reminderPlant to session
        $this->_f3->set('indoorPlantTest', $indoorPlantObjects);
        $this->_f3->set('outdoorPlantTest', $outdoorPlantObjects);
        $this->_f3->set('reminderPlant', $reminderPlant);
        $this->_f3->set('SESSION.plants', $plantObjects);

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
        $plantName = $speciesName = $waterPeriod = $lastWatered = $adoptionDate = $imagePath = $location = $isIndoor = "";
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            //handle file upload

            if($_FILES['uploadFile']['size']!=0){
                $target_dir ="uploads/";
                $target_file = $target_dir . basename($_FILES["uploadFile"]["name"]);

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
            if(!empty($_POST['speciesName'])){
                if(Validate3::validName($_POST['speciesName'])){
                    $speciesName = $_POST['speciesName'];
                }else {
                    $this->_f3->set('errors["speciesName"]', "Please enter a valid name (only letters no spaces)");
                }
            }
            if(is_numeric($_POST['waterPeriod']) && strlen($_POST['waterPeriod']) >= 1 && strlen($_POST['waterPeriod']) <= 2){
                $waterPeriod = $_POST['waterPeriod'];
            } else {
                $this->_f3->set('errors["waterPeriod"]', "Please enter a number between 0-99");
            }
            if(!empty($_POST['location'])){
                if(Validate3::validName($_POST['location'])){
                    $location = $_POST['location'];
                }else {
                    $this->_f3->set('errors["location"]', "Please enter a valid location (only letters no spaces)");
                }
            }
            if($_POST['isIndoor']== 0 || $_POST['isIndoor']==1){
                $isIndoor = $_POST['isIndoor'];
            }else {
                $this->_f3->set('errors["isIndoor"]', "Please select indoor or outdoor plant type.");
            }

            $lastWatered = $_POST['lastWatered'];
            $adoptionDate = $_POST['adoptionDate'];

            //get member ID out of session data
            $memberId = $this->_f3->get('SESSION.user')->getUserId();


            // if no errors call sql add methods
            if (empty($this->_f3->get('errors'))) {
                if($isIndoor == 0){
                    $plant = new IndoorPlant($memberId, $plantName, $speciesName,
                        $waterPeriod, $lastWatered, $adoptionDate, "",
                        $imagePath
                    );
                }else {
                    $plant = new OutdoorPlant($memberId, $plantName, $speciesName,
                        $waterPeriod, $lastWatered, $location, $adoptionDate, "",
                        $imagePath
                    );
                }


                // add new plant to Plants table
                $id = $GLOBALS['dataLayer']->addPlant($plant);
                $plant->setPlantId($id);

                // add new image to PlantPics table
                if ($imagePath != ''){
                    $plantImage = new PlantImage('', $plant->getPlantId(), $imagePath);

                    $id = $GLOBALS['dataLayer']->addImage($plantImage);
                    $plantImage->setImageId($id);
                }
                $this->_f3->reroute('library');
            }



        }

        $view = new Template();
        echo $view->render('views/add-plant.html');
    }

    /**
     * Water plant and reset lastWatered date.
     *
     * @return void
     */
    function waterPlant(): void
    {
        $plantId = $_GET['id'];
        $plant = $GLOBALS['dataLayer']->getPlant($plantId);
        $plantObject = new Plant(
                $plant[0]['UserId'],
                $plant[0]['Nickname'],
                $plant[0]['Species'],
                $plant[0]['WateringPeriod'],
                $plant[0]['LastWatered'],
                $plant[0]['PlantId']
            );

        $GLOBALS['dataLayer']->waterPlant($plantObject);
        $this->_f3->reroute('library');

    }

    /**
     * Renders the view plant view template.
     *
     * @return void
     */
    function viewPlant(): void
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $plants = $this->_f3->get("SESSION.plants");

            // find plant in session array
            foreach ($plants as $plant) {
                if ($plant->getPlantId() === $_POST['plantId']) {
                    $this->_f3->set("SESSION.plant", $plant);
                    break;
                }
            }
        }

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

        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            // get form data
            $name = '';
            $email = '';
            $message = '';

            // check if each value is valid
            if (Validate3::validName($_POST['name'])) {
                $name = $_POST['name'];
            } else {
                $this->_f3->set("errors['name']", "Please enter a valid name");
            }

            if (Validate3::validEmail($_POST['email'])) {
                $email = $_POST['email'];
            } else {
                $this->_f3->set("errors['email']",
                    "Please use this format: email@example.com"
                );
            }

            if (Validate3::validMessage($_POST['message'])) {
                $message = $_POST['message'];
            } else {
                $this->_f3->set("errors['message']",
                    "Please enter a message within 25-2000 characters"
                );
            }

            // sends message if form data is valid
            if (empty($this->_f3->get('errors'))) {
                $recipient = "cervantes.yadira@student.greenriver.edu";
                $subject = "Message from " . $name . "<" . $email . ">";

                $isSent = mail($recipient, $subject, $message);

                if(true) {
                    // add form data to session array
                    $this->_f3->set("SESSION.name", $name);
                    $this->_f3->set("SESSION.email", $email);
                    $this->_f3->set("SESSION.message", $message);

                    $this->_f3->reroute('message-success');
                }
            }
        }

        $view = new Template();
        echo $view->render('views/contact-form.html');
    }

    /**
     * Renders the contact receipt view template.
     *
     * @return void
     */
    function contactReceipt()
    {
        $view = new Template();
        echo $view->render('views/contact-receipt.html');
    }
}