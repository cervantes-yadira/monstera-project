<?php
/**
 * This file contains the Parent_Plant class.
 *
 * @author Jennifer McNiel
 * @version 1.0
 */

/**
 * Represents a plant, keeping track of its basic information.
 *
 * This class serves as a parent class for indoor and outdoor plants.
 *
 * @author Jennifer McNiel
 * @version 1.0
 */
class Plant
{
    private $_memberId, $_plantName, $_speciesName, $_waterPeriod, $_waterDate,
        $_files, $_plantId;

    /**
     * Constructs a Parent_Plant object.
     *
     * @param string $memberId The member ID associated with the plant.
     * @param string $plantName The name of the plant.
     * @param string $speciesName The species name of the plant.
     * @param string $waterPeriod The watering period of the plant.
     * @param string $waterDate The last watered date of the plant.
     * @param string $plantId The plant ID associated with the plant.
     * @param string|null $files The files/images associated with the plant.
     */
    public function __construct($memberId, $plantName, $speciesName,
                                $waterPeriod, $waterDate, $plantId,
                                $files = null
    ) {
        $this->_memberId = $memberId;
        $this->_plantName = $plantName;
        $this->_speciesName = $speciesName;
        $this->_waterPeriod = $waterPeriod;
        $this->_waterDate = $waterDate;
        $this->_plantId = $plantId;
        $this->_files = $files;
    }

    /**
     * Gets the member ID associated with the plant.
     *
     * @return mixed The member ID associated with the plant.
     */
    public function getMemberId()
    {
        return $this->_memberId;
    }

    /**
     * Sets the member ID associated with the plant.
     *
     * @param mixed $memberId The member ID to set.
     */
    public function setMemberId($memberId)
    {
        $this->_memberId = $memberId;
    }

    /**
     * Gets the name of the plant.
     *
     * @return mixed The name of the plant.
     */
    public function getPlantName()
    {
        return $this->_plantName;
    }

    /**
     * Sets the name of the plant.
     *
     * @param mixed $plantName The name to set.
     */
    public function setPlantName($plantName)
    {
        $this->_plantName = $plantName;
    }

    /**
     * Gets the species name of the plant.
     *
     * @return mixed The species name of the plant.
     */
    public function getSpeciesName()
    {
        return $this->_speciesName;
    }

    /**
     * Sets the species name of the plant.
     *
     * @param mixed $speciesName The species name to set.
     */
    public function setSpeciesName($speciesName)
    {
        $this->_speciesName = $speciesName;
    }

    /**
     * Gets the watering period of the plant.
     *
     * @return mixed The watering period of the plant.
     */
    public function getWaterPeriod()
    {
        return $this->_waterPeriod;
    }

    /**
     * Sets the watering period of the plant.
     *
     * @param mixed $waterPeriod The watering period to set.
     */
    public function setWaterPeriod($waterPeriod)
    {
        $this->_waterPeriod = $waterPeriod;
    }

    /**
     * Gets the last watered date of the plant.
     *
     * @return mixed The last watered date of the plant.
     */
    public function getWaterDate()
    {
        return $this->_waterDate;
    }

    /**
     * Sets the last watered date of the plant.
     *
     * @param mixed $waterDate The last watered date to set.
     */
    public function setWaterDate($waterDate)
    {
        $this->_waterDate = $waterDate;
    }

    /**
     * Gets the files/images associated with the plant.
     *
     * @return mixed The files/images associated with the plant.
     */
    public function getFiles()
    {
        return $this->_files;
    }

    /**
     * Sets the files/images associated with the plant.
     *
     * @param mixed $files The files/images to set.
     */
    public function setFiles($files)
    {
        $this->_files = $files;
    }

    /**
     * Gets the plant ID associated with the plant.
     *
     * @return mixed The plant ID associated with the plant.
     */
    public function getPlantId()
    {
        return $this->_plantId;
    }

    /**
     * Sets the plant ID associated with the plant.
     *
     * @param mixed $plantId The plant ID to set.
     */
    public function setPlantId($plantId)
    {
        $this->_plantId = $plantId;
    }
}