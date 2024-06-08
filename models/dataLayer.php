<?php

class DataLayer3
{
    private $_dbh;

    /**
     * Datalayer contructor to connect to PDO DB
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
        $sql = 'INSERT INTO Plants (UserId, Nickname, Species, AdoptionDate, WateringPeriod, LastWatered) 
                VALUES (:userId, :name, :species, :adopt, :waterPeriod, :LastWater)';

        // 2 Prepare the statement
        $statement = $this->_dbh->prepare($sql);

        //3 bind the parameters
        $userId = $plant->getMemberId();
        $plantName = $plant->getPlantName();
        $species = $plant->getSpeciesName();
        $adoptDate = $plant->getAdoptDate();
        $waterPeriod = $plant->getWaterPeriod();
        $waterDate = $plant->getWaterDate();
        $statement->bindParam(':userId', $userId);
        $statement->bindParam(':name', $plantName);
        $statement->bindParam(':species', $species);
        $statement->bindParam(':adopt', $adoptDate);
        $statement->bindParam(':waterPeriod', $waterPeriod);
        $statement->bindParam(':lastWater', $waterDate);

        //4 execute the query
        $statement->execute();

        //5 (optional) process the results
        return $this->_dbh->lastInsertId();
    }


    //TODO create addImage()
}