<?php
/**
 * Controller class for application project.
 *
 * @author Luke Cheng
 * @author Jennifer McNiel
 * @author Yadira Cervantes
 * @version 1.0
 */

/**
 * Renders the templates and performs processing for the application's views.
 *
 * @author Luke Cheng
 * @author Jennifer McNiel
 * @author Yadira Cervantes
 * @version 1.0
 */
class Controller3
{
    private $_f3;

    /**
     * Constructs a Controller object.
     *
     * @param Base $f3 Fat-Free instance
     */
    function __construct($f3)
    {
        $this->_f3 = $f3;
    }

    /**
     * Renders the home view.
     *
     * @return void
     */
    function home(): void
    {
        $view = new Template();
        echo $view->render('views/home.html');
    }

    /**
     * Renders the sign-up view.
     *
     * @param $dbh
     * @return void
     */
    function signUp($dbh): void
    {
        // define variables
        $userName = $email = $password = $passwordConfirm = '';

        if ($_SERVER['REQUEST_METHOD'] === "POST") {

            // validate username
            if (Validate3::validUserName($_POST['userName'])) {
                // check if username is in use
                $isAvailable = Validate3::validAvailableUserName(
                    $_POST['userName']
                );

                if ($isAvailable) {
                    $userName = $_POST['userName'];
                } else {
                    $this->_f3->set('errors["userName"]',
                        "User name not available"
                    );
                }
            } else {
                $this->_f3->set('errors["userName"]',
                    "Please enter a valid user name"
                );
            }

            if (Validate3::validEmail($_POST['email'])) {
                $email = $_POST['email'];
            } else {
                $this->_f3->set('errors["email"]',
                    "Please use this format: email@example.com"
                );
            }

            // validate passwords
            $isPassValid = Validate3::validPassword($_POST['password']);

            if ($isPassValid) {
                $password = $_POST['password'];

                $isPassConfirmValid = Validate3::passwordMatch($password,
                    $_POST['password-confirm']
                );

                if ($isPassConfirmValid) {
                    // hash the valid password
                    $hashPass = password_hash($password, PASSWORD_DEFAULT);

                } else {
                    $this->_f3->set('errors["password"]',
                        "Passwords must match"
                    );
                }
            } else {
                $this->_f3->set('errors["password"]',
                    "Please enter a valid password, between 8-16 characters, 
                    must include at least 1 number"
                );
            }

            // check for errors
            if (empty($this->_f3->get('errors'))) {
                $user = new Member($userName, $email, $hashPass);
                $this->_f3->set('SESSION.user', $user);

                // send new user info to database
                $id = $GLOBALS['dataLayer']->addUser($user);
                $user->setUserId($id);

                $this->_f3->reroute('/');
            }
        }

        $view = new Template();
        echo $view->render('views/sign-up.html');
    }

    /**
     * Renders the sign-in view.
     *
     * @return void
     */
    function signIn(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            $userName = $_POST['username'];
            $plainTextPass = $_POST['password'];

            $resultUser = $GLOBALS['dataLayer']->getUser($userName);

            // check if user exists
            if ($resultUser != 0) {
                $retrievedHashPass = $resultUser['Password'];
                $verifyPass = password_verify($plainTextPass,
                    $retrievedHashPass
                );

                $resultUserName = strtolower($resultUser['UserName']);
                $userName = strtolower($userName);

                if (($userName === $resultUserName) && $verifyPass
                    && $resultUser['isDeleted'] != 1
                ) {
                    // add user to session array
                    $user = new Member($userName, $resultUser['Email'],
                        $retrievedHashPass, $resultUser['UserId']
                    );
                    $this->_f3->set('SESSION.user', $user);

                    $this->_f3->reroute('library');
                } else {
                    $this->_f3->set('errors["logIn"]',
                        "Incorrect username or password, please try again."
                    );
                }
            } else {
                $this->_f3->set('errors["logIn"]',
                    "Incorrect username or password, please try again."
                );
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
        if ($this->_f3->get("SESSION.user") !== null) {
            $this->_f3->set("SESSION.user", null);
            session_destroy();
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

        if ($user === null) {
            $this->_f3->reroute('sign-in');
        }

        $plants = $GLOBALS['dataLayer']->getPlantInfo($user);
        $userId = $user->getUserId();
        $indoorPlantObjects = [];
        $outdoorPlantObjects = [];
        $plantObjects = [];
        $reminderPlant = [];

        // Set today's date
        $today = new DateTime();
        $todayDate = $today->format('Y-m-d');

        // Turn plants from SQL query into plant objects
        foreach ($plants as $plant) {
            // grab images for the plant
            $plantId = $plant['PlantId'];
            $images = $GLOBALS['dataLayer']->getImages($plantId);
            $isIndoor = $plant['isIndoor'];

            // check if a plant has images
            empty($images) ? $images = null : $images = $images[0]["Url"];

            // Create plant object
            if ($isIndoor === "0") {
                $plantObject = new Plant_IndoorPlant(
                    $userId,
                    $plant['Nickname'],
                    $plant['Species'],
                    $plant['WateringPeriod'],
                    $plant['LastWatered'],
                    $plant['AdoptionDate'],
                    $plantId,
                    $images);

                // Add to indoorPlant Objects array
                $indoorPlantObjects[] = $plantObject;

            } else {
                $plantObject = new Plant_OutdoorPlant(
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

            $plantObjects[] = $plantObject;

            // Calculate next watering date

            $lastWateredDate = new DateTime($plant['LastWatered']);
            $nextWateredDate = clone $lastWateredDate;
            $nextWateredDate->modify(
                "+{$plant['WateringPeriod']} days"
            );
            $nextWatered = $nextWateredDate->format('Y-m-d');

            // Only add to reminderPlant array if nextWatered date is today or
            // before today
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

        // Set plantObjects and reminderPlant to session
        $this->_f3->set('SESSION.plants', $indoorPlantObjects);
        $this->_f3->set('indoorPlantTest', $indoorPlantObjects);
        $this->_f3->set('outdoorPlantTest', $outdoorPlantObjects);
        $this->_f3->set('reminderPlant', $reminderPlant);

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
        $plantName = $speciesName = $waterPeriod = $lastWatered = $adoptionDate
            = $imagePath = $location = $isIndoor = "";

        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            //handle file upload

            if ($_FILES['uploadFile']['size'] != 0) {
                $uploadFile = $_FILES['uploadFile'];
                $target_dir ="uploads/";
                $target_file = $target_dir . basename($uploadFile["name"]);

                //validate image
                $imageFileType = strtolower(pathinfo($target_file,
                    PATHINFO_EXTENSION)
                );
                $uploadOk = Validate3::validFile($uploadFile, $target_file,
                    $imageFileType
                );

                if ($uploadOk) {
                    $hasMoved = move_uploaded_file($uploadFile["tmp_name"],
                        $target_file
                    );

                    if ($hasMoved) {
                        $imagePath = $target_file;
                    }
                } else {
                    $this->_f3->set('errors["file"]', "File is not valid.");
                }

            }

            //validate all plant Info
            if (Validate3::validName($_POST['plantName'])) {
                $plantName = $_POST['plantName'];
            } else {
                $this->_f3->set('errors["plantName"]',
                    "Please enter a valid name"
                );
            }

            if (!empty($_POST['speciesName'])) {
                if (Validate3::validName($_POST['speciesName'])) {
                    $speciesName = $_POST['speciesName'];
                } else {
                    $this->_f3->set('errors["speciesName"]',
                        "Please enter a valid name (only letters no spaces)"
                    );
                }
            }

            if (Validate3::validWaterPeriod($_POST['waterPeriod'])) {
                $waterPeriod = $_POST['waterPeriod'];
            } else {
                $this->_f3->set('errors["waterPeriod"]',
                    "Please enter a number between 0-99"
                );
            }

            if (!empty($_POST['location'])) {
                if (Validate3::validName($_POST['location'])) {
                    $location = $_POST['location'];
                } else {
                    $this->_f3->set('errors["location"]',
                        "Please enter a valid location (only letters no spaces)"
                    );
                }
            }

            if ($_POST['isIndoor'] === "0" || $_POST['isIndoor'] === "1") {
                $isIndoor = $_POST['isIndoor'];
            } else {
                $this->_f3->set('errors["isIndoor"]',
                    "Please select indoor or outdoor plant type"
                );
            }

            $lastWatered = $_POST['lastWatered'];
            $adoptionDate = $_POST['adoptionDate'];

            // get member ID out of session data
            $memberId = $this->_f3->get('SESSION.user')->getUserId();

            // if no errors call sql add methods
            if (empty($this->_f3->get('errors'))) {
                if ($isIndoor === "0") {
                    $plant = new Plant_IndoorPlant($memberId, $plantName,
                        $speciesName, $waterPeriod, $lastWatered, $adoptionDate,
                        "", $imagePath
                    );
                } else {
                    $plant = new Plant_OutdoorPlant($memberId, $plantName,
                        $speciesName, $waterPeriod, $lastWatered, $location,
                        $adoptionDate, "", $imagePath
                    );
                }

                // add new plant to Plants table
                $id = $GLOBALS['dataLayer']->addPlant($plant);
                $plant->setPlantId($id);

                // add new image to PlantPics table
                if ($imagePath != '') {
                    $plantImage = new PlantImage('',
                        $plant->getPlantId(), $imagePath
                    );

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
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
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
     * Renders the contact us view.
     *
     * @return void
     */
    function contactUs()
    {
        if ($_SERVER['REQUEST_METHOD'] === "POST") {
            // initialize variables
            $name = $email = $message = '';

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

                if ($isSent) {
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
     * Renders the contact receipt view.
     *
     * @return void
     */
    function contactReceipt()
    {
        $view = new Template();
        echo $view->render('views/contact-receipt.html');
    }
}