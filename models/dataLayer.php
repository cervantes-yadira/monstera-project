<?php
/**
 * This file contains the DataLayer3 class.
 *
 * @author Jennifer McNiel
 * @author Luke Cheng
 * @author Yadira Cervantes
 * @version 1.0
 */

/**
 * Adds, removes, accesses, and updates elements in the database.
 *
 * @author Jennifer McNiel
 * @author Luke Cheng
 * @author Yadira Cervantes
 * @version 1.0
 */
class DataLayer3
{
    private $_dbh;

    /**
     * Constructs a DataLayer3 object.
     */
    function __construct()
    {
        // Require database connection credentials
        require_once $_SERVER['DOCUMENT_ROOT'].'/../config.php';

        try {
            // instantiate our PDO DB object
            $this->_dbh = new PDO(DB_DSN, DB_USERNAME,
                DB_PASSWORD
            );
        }
        catch (PDOException $e) {
            // this prevents the code from continuing to execute
            die ($e->getMessage());
        }
    }

    /**
     * Gets a specified user from the database.
     *
     * @param mixed $userName a members username
     * @return mixed query result
     */
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

    /**
     * Adds a specified user to the database.
     *
     * @param Member $user a Member object
     * @return false|string false if query failed, a string user id otherwise
     */
    function addUser($user)
    {
        //1 define the query
        $sql = 'INSERT INTO PlantUsers (UserName, Password, Email) 
                    VALUES (:name, :pass, :email)';

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

    /**
     * Adds a specified plant to the database.
     *
     * @param Plant $plant a Plant (or its child) object
     * @return false|string false if query failed, a string plant id otherwise
     */
    function addPlant($plant)
    {
        //1 define the query
        $sql = 'INSERT INTO Plants (UserId, Nickname, Species, AdoptionDate, 
                    WateringPeriod, LastWatered, isIndoor, Location) 
                    VALUES (:userId, :name, :species, :adopt, :waterPeriod, 
                            :lastWater, :isIndoor, :location)';

        // 2 Prepare the statement
        $statement = $this->_dbh->prepare($sql);

        //3 bind the parameters
        $userId = $plant->getMemberId();
        $plantName = $plant->getPlantName();
        $species = $plant->getSpeciesName();
        $adoptDate = "";
        $waterPeriod = $plant->getWaterPeriod();
        $waterDate = $plant->getWaterDate();
        $location = "";

        if (get_class($plant) === "Plant_OutdoorPlant") {
            $isIndoor = 1;
            $location = $plant->getLocation();
            $adoptDate = $plant->getPlantedDate();
        } elseif (get_class($plant) === "Plant_IndoorPlant") {
            $adoptDate = $plant->getAdoptDate();
            $isIndoor = 0;
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

    /**
     * Adds a specified plant image to the database.
     *
     * @param PlantImage $image a PlantImage object
     * @return false|string false if query failed, a string image id otherwise
     */
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

        //5 (optional) process the results
        return $this->_dbh->lastInsertId();
    }

    /**
     * Gets all images associated with a given plant from the database.
     *
     * @param mixed $plantId a plant id
     * @return array|false false if query failed, otherwise an array of images
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
     * Gets all plants associated with a user from the database.
     *
     * @return array|false false if query failed, otherwise an array of plants
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
     * Gets a plant associated with a given plant id from the database.
     *
     * @return array|false false if query failed, otherwise an array of data
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
     * Update the last watered date of a given plant in the database.
     *
     * @param Plant $plant a Plant (or its child) object
     * @return void
     */
    function waterPlant($plant): void
    {
        $sql = "UPDATE Plants SET LastWatered = :lastWatered 
                    WHERE PlantId = :plantId";

        $statement = $this->_dbh->prepare($sql);

        $today = new DateTime();
        $todayDate = $today->format('Y-m-d');
        $statement->bindParam(":lastWatered", $todayDate);

        $plantId = $plant->getPlantId();
        $statement->bindParam(":plantId", $plantId, PDO::PARAM_INT);

        $statement->execute();
    }

    /**
     * Get all plants associated with a member in sorted order.
     *
     * @param Member $user a Member object
     * @return array|false false if query failed, otherwise an array of plants
     */
    function getPlantInfo($user)
    {
        $sql = "SELECT PlantId, UserId, Nickname, Species, AdoptionDate, 
                    WateringPeriod, LastWatered, isIndoor, Location FROM Plants 
                    WHERE UserId = :userId ORDER BY Nickname ASC";

        $statement = $this->_dbh->prepare($sql);
        $userId = $user->getUserId();
        $statement->bindParam(":userId",$userId);
        $statement->execute();

        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
}

