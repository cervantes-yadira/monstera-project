<?php
/**
 * This file contains the PlantImage class.
 *
 * @author Jennifer McNiel
 * @version 1.0
 */

/**
 *  Represents a single image of a plant.
 *
 * @author Jennifer McNiel
 * @version 1.0
 */
class PlantImage
{
    private $_imageId, $_plantId, $_path;

    /**
     * Constructs a PlantImage object.
     *
     * @param string $_imageId
     * @param string $_plantId
     * @param string $_path
     */
    public function __construct($_imageId, $_plantId, $_path)
    {
        $this->_imageId = $_imageId;
        $this->_plantId = $_plantId;
        $this->_path = $_path;
    }

    /**
     * Gets an images id.
     *
     * @return string an images id
     */
    public function getImageId()
    {
        return $this->_imageId;
    }

    /**
     * Sets an images id to a given id.
     *
     * @param string $imageId a new image id
     */
    public function setImageId($imageId)
    {
        $this->_imageId = $imageId;
    }

    /**
     * Gets an images plant id.
     *
     * @return string an images plant id
     */
    public function getPlantId()
    {
        return $this->_plantId;
    }

    /**
     * Sets an images associated plant id to a given id.
     *
     * @param string $plantId a new plant id
     */
    public function setPlantId($plantId)
    {
        $this->_plantId = $plantId;
    }

    /**
     * Returns an images file path.
     *
     * @return string an images file path
     */
    public function getPath()
    {
        return $this->_path;
    }

    /**
     * Sets an images file path to a given path.
     *
     * @param string $path a new file path
     */
    public function setPath($path)
    {
        $this->_path = $path;
    }
}