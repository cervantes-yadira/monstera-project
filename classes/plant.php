<?php

class Plant
{
    private $_memberId;
    private $_plantName;
    private $_speciesName;
    private $_waterPeriod;
    private $_waterDate;
    private $_files;
    private $_plantId;

    /**
     * @param $_memberId
     * @param $_plantName
     * @param $_speciesName
     * @param $_waterPeriod
     * @param $_waterDate
     * @param $_plantId
     * @param $_files
     */
    public function __construct($_memberId, $_plantName, $_speciesName, $_waterPeriod, $_waterDate, $_plantId, $_files=null)
    {
        $this->_memberId = $_memberId;
        $this->_plantName = $_plantName;
        $this->_speciesName = $_speciesName;
        $this->_waterPeriod = $_waterPeriod;
        $this->_waterDate = $_waterDate;
        $this->_plantId = $_plantId;
        $this->_files = $_files;
    }

    /**
     * @return mixed
     */
    public function getMemberId()
    {
        return $this->_memberId;
    }

    /**
     * @param mixed $memberId
     */
    public function setMemberId($memberId): void
    {
        $this->_memberId = $memberId;
    }

    /**
     * @return mixed
     */
    public function getPlantName()
    {
        return $this->_plantName;
    }

    /**
     * @param mixed $plantName
     */
    public function setPlantName($plantName): void
    {
        $this->_plantName = $plantName;
    }

    /**
     * @return mixed
     */
    public function getSpeciesName()
    {
        return $this->_speciesName;
    }

    /**
     * @param mixed $speciesName
     */
    public function setSpeciesName($speciesName): void
    {
        $this->_speciesName = $speciesName;
    }

    /**
     * @return mixed
     */
    public function getWaterPeriod()
    {
        return $this->_waterPeriod;
    }

    /**
     * @param mixed $waterPeriod
     */
    public function setWaterPeriod($waterPeriod): void
    {
        $this->_waterPeriod = $waterPeriod;
    }

    /**
     * @return mixed
     */
    public function getWaterDate()
    {
        return $this->_waterDate;
    }

    /**
     * @param mixed $waterDate
     */
    public function setWaterDate($waterDate): void
    {
        $this->_waterDate = $waterDate;
    }

    /**
     * @return mixed
     */
    public function getAdoptDate()
    {
        return $this->_adoptDate;
    }

    /**
     * @param mixed $adoptDate
     */
    public function setAdoptDate($adoptDate): void
    {
        $this->_adoptDate = $adoptDate;
    }

    /**
     * @return mixed
     */
    public function getFiles()
    {
        return $this->_files;
    }

    /**
     * @param mixed $files
     */
    public function setFiles($files): void
    {
        $this->_files = $files;
    }

    /**
     * @return mixed
     */
    public function getPlantId()
    {
        return $this->_plantId;
    }

    /**
     * @param mixed $plantId
     */
    public function setPlantId($plantId): void
    {
        $this->_plantId = $plantId;
    }


}