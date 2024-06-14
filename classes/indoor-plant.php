<?php
/**
 * This file contains the IndoorPlant class.
 *
 * @author Jennifer McNiel
 * @version 1.0
 */

/**
 * Represents an indoor plant, keeping track of its last watered date.
 *
 * @author Jennifer McNiel
 * @version 1.0
 */
class Plant_IndoorPlant extends Plant
{
    private $_adoptDate;

    /**
     * Constructs an IndoorPlant object.
     *
     * @param string $_memberId
     * @param string $_plantName
     * @param string $_speciesName
     * @param string $_waterPeriod
     * @param string $_waterDate
     * @param string $_adoptDate
     * @param string $_plantId
     * @param string|null $_files
     */
    public function __construct($_memberId, $_plantName, $_speciesName,
                                $_waterPeriod, $_waterDate, $_adoptDate,
                                $_plantId, $_files
    ) {
        parent::__construct($_memberId, $_plantName, $_speciesName,
            $_waterPeriod, $_waterDate, $_plantId, $_files
        );

        $this->_adoptDate = $_adoptDate;
    }

    /**
     * Returns the adoption date of a plant.
     *
     * @return string plant adoption date
     */
    public function getAdoptDate()
    {
        return $this->_adoptDate;
    }

    /**
     * Sets the adoption date of a plant to a given value.
     *
     * @param string $adoptDate day a plant was adopted
     */
    public function setAdoptDate($adoptDate)
    {
        $this->_adoptDate = $adoptDate;
    }
}