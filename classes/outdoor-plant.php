<?php
/**
 * This file contains the OutdoorPlant class
 *
 * @author Jennifer McNiel
 * @version 1.0
 */

/**
 * Represents an outdoor plant, keeping track of a location and date planted.
 *
 * @author Jennifer McNiel
 * @version 1.0
 */
class Plant_OutdoorPlant extends Plant
{
    private $_location, $_plantedDate;

    /**
     * Constructs an OutdoorPlant object.
     *
     * @param string $_memberId
     * @param string $_plantName
     * @param string $_speciesName
     * @param string $_waterPeriod
     * @param string $_waterDate
     * @param string $_plantedDate
     * @param string $_plantId
     * @param string|null $_location
     * @param string|null $_files
     */
    function __construct($_memberId, $_plantName, $_speciesName, $_waterPeriod,
                         $_waterDate, $_location, $_plantedDate, $_plantId,
                         $_files
    ) {
        parent::__construct($_memberId, $_plantName, $_speciesName,
            $_waterPeriod, $_waterDate, $_plantId, $_files
        );

        $this->_location = $_location;
        $this->_plantedDate = $_plantedDate;
    }

    /**
     * Returns the location of a plant.
     *
     * @return string|null location of a plant
     */
    public function getLocation()
    {
        return $this->_location;
    }

    /**
     * Sets the adoption date of a plant to a given value.
     *
     * @param string|null $location location of a plant
     */
    public function setLocation($location)
    {
        $this->_location = $location;
    }

    /**
     * Returns the planted date of a plant.
     *
     * @return string date a plant was planted
     */
    public function getPlantedDate()
    {
        return $this->_plantedDate;
    }

    /**
     * Sets the adoption date of a plant to a given value.
     *
     * @param string $plantedDate date a plant was planted
     */
    public function setPlantedDate($plantedDate)
    {
        $this->_plantedDate = $plantedDate;
    }
}