<?php

class DataLayer3
{
    private $_dbh;

    /**
     * Datalayer constructor to connect to PDO DB
     */
    function __construct()
    {
        // Require database connection credentials
        require_once $_SERVER['DOCUMENT_ROOT'].'/../config.php';

        try {
            // instantiate our PDO DB object
            $this->_dbh = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
//            echo 'Connected to database!!';

        }
        catch (PDOException $e) {
            die ($e->getMessage()); // this prevents the code from continuing to execute // use for development testing
//            die ("<p>Something went wrong</p>");  // this code protects from showing errors to users
        }
    }

    function getUser($userName)
    {
        //1 define
        $sql = "SELECT * FROM PlantUsers WHERE userName = :sqlUserName";

        //2 prepare statement
        $statement = $this->_dbh->prepare($sql);

        //3 bind parameters
        $statement->bindParam(':sqlUserName', $userName);

        //4 execute query
        $statement->execute();

        //5 (optional) process the results
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    function addUser($user)
    {
        //1 define the query
        $sql = 'INSERT INTO PlantUsers (UserName, Password, Email) VALUES (:name, :pass, :email)';

        // 2 Prepare the statement
        $statement = $this->_dbh->prepare($sql);

        //3 bind the parameters
        $userName = $user->getUserName();
        $pass = $user->getPassword();
        $email = $user->getEmail();
        $statement->bindParam(':name', $userName);
        $statement->bindParam(':pass', $pass);
        $statement->bindParam(':email', $email);

        //4 execute the query
        $statement->execute();

        //5 (optional) process the results
        return $this->_dbh->lastInsertId();
    }


    function addPlant($plant)
    {
        //1 define the query
        $sql = 'INSERT INTO Plants (UserId, Nickname, Species, AdoptionDate, WateringPeriod, LastWatered, isIndoor, Location) 
                VALUES (:userId, :name, :species, :adopt, :waterPeriod, :lastWater, :isIndoor, :location)';

        // 2 Prepare the statement
        $statement = $this->_dbh->prepare($sql);

        //3 bind the parameters
        $userId = $plant->getMemberId();
        $plantName = $plant->getPlantName();
        $species = $plant->getSpeciesName();
        $adoptDate = $plant->getAdoptDate();
        $waterPeriod = $plant->getWaterPeriod();
        $waterDate = $plant->getWaterDate();
        $location = "";
        if(get_class($plant) == "OutdoorPlant"){
            $isIndoor = 0;
            $location = $plant->getLocation();
        } else {
            $isIndoor = 1;
        }

        $statement->bindParam(':userId', $userId);
        $statement->bindParam(':name', $plantName);
        $statement->bindParam(':species', $species);
        $statement->bindParam(':adopt', $adoptDate);
        $statement->bindParam(':waterPeriod', $waterPeriod);
        $statement->bindParam(':lastWater', $waterDate);
        $statement->bindParam(':isIndoor', $isIndoor);
        $statement->bindParam(':location', $location);

        //4 execute the query
        $statement->execute();

        //5 (optional) process the results
        return $this->_dbh->lastInsertId();
    }


    function addImage($image)
    {
        //1 define the query
        $sql = 'INSERT INTO PlantPics (PlantId, Url) VALUES (:plantId, :path)';

        // 2 Prepare the statement
        $statement = $this->_dbh->prepare($sql);

        //3 bind the parameters
        $plantId = $image->getPlantId();
        $imagePath = $image->getPath();

        $statement->bindParam(':plantId', $plantId);
        $statement->bindParam(':path', $imagePath);

        //4 execute the query
        $statement->execute();

        //header('Content-Type: application/json');
        //5 (optional) process the results
        return $this->_dbh->lastInsertId();
    }

    /**
     * Retrieves all images associated with a given plant from the database.
     *
     * @param $plantId string the ID of the plant
     * @return array|false an array of images, or false if an error occurred
     */
    function getImages(string $plantId)
    {
        // define query
        $sql = "SELECT Url FROM PlantPics WHERE PlantId = $plantId";

        // prepare the statement
        $statement = $this->_dbh->prepare($sql);

        // execute the query
        $statement->execute();

        // process the results
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieves all plants associated with a user from the database.
     *
     * @return array|false an array of plants, or false if an error occurred
     */
    function getPlants()
    {
        // retrieve user id
        $f3 = Base::instance();
        $user = $f3->get('SESSION.user');
        $userId = $user->getUserId();

        // define query
        $sql = "SELECT * FROM Plants WHERE UserId = $userId";

        // prepare the statement
        $statement = $this->_dbh->prepare($sql);

        // execute the query
        $statement->execute();

        // process the results
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Retrieves a plant associated with a given plant id from the database.
     *
     * @return array|false an array of plant data, or false if an error occurred
     */
    function getPlant($plantId)
    {
        // define query
        $sql = "SELECT * FROM Plants WHERE PlantId = $plantId";

        // prepare the statement
        $statement = $this->_dbh->prepare($sql);

        // execute the query
        $statement->execute();

        // process the results
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }


    /**
     * Water plant and reset lastWatered date.
     *
     * @return void
     */
    function waterPlant($plant): void
    {
        $sql = "UPDATE Plants SET LastWatered = :lastWatered WHERE PlantId = :plantId";

        $statement = $this->_dbh->prepare($sql);
        $todayDate = date('Y-m-d');
        $statement->bindParam(":lastWatered", $todayDate);

        $plantId = $plant->getPlantId();
        $statement->bindParam(":plantId", $plantId);
        $statement->execute();

        $statement->execute();
        echo "Plant watered successfully";

    }

    function getPlantInfo($user)
    {
        $sql = "SELECT PlantId, UserId, Nickname, Species, AdoptionDate, WateringPeriod, LastWatered, isIndoor, Location FROM Plants WHERE UserId = :userId";

        $statement = $this->_dbh->prepare($sql);
        $userId = $user->getUserId();
        $statement->bindParam(":userId",$userId);
        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;

    }
}

