<?php

class PlantImage
{
    private $_imageId;
    private $_plantId;
    private $_path;

    /**
     * @param $_imageId
     * @param $_plantId
     * @param $_location
     */
    public function __construct($_imageId, $_plantId, $_location)
    {
        $this->_imageId = $_imageId;
        $this->_plantId = $_plantId;
        $this->_path = $_location;
    }


    /**
     * @return mixed
     */
    public function getImageId()
    {
        return $this->_imageId;
    }

    /**
     * @param mixed $imageId
     */
    public function setImageId($imageId): void
    {
        $this->_imageId = $imageId;
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

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->_path;
    }

    /**
     * @param mixed $location
     */
    public function setPath($location): void
    {
        $this->_path = $location;
    }


}