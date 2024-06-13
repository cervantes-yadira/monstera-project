<?php

class OutdoorPlant extends Plant
{
    private $_location;
    private $_plantedDate;


    function __construct($_memberId, $_plantName, $_speciesName, $_waterPeriod, $_waterDate, $_location, $_plantedDate, $_plantId, $_files)
    {
        parent::__construct($_memberId, $_plantName, $_speciesName, $_waterPeriod, $_waterDate, $_plantId, $_files);

        $this->_location = $_location;
        $this->_plantedDate = $_plantedDate;
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->_location;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location): void
    {
        $this->_location = $location;
    }

    /**
     * @return mixed
     */
    public function getPlantedDate()
    {
        return $this->_plantedDate;
    }

    /**
     * @param mixed $_plantedDate
     */
    public function setPlantedDate($_plantedDate): void
    {
        $this->_plantedDate = $_plantedDate;
    }


}