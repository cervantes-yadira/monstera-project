<?php

class IndoorPlant extends Plant
{
    private $_adoptDate;


    public function __construct($_memberId, $_plantName, $_speciesName, $_waterPeriod, $_waterDate, $_adoptDate, $_plantId, $_files)
    {
        parent::__construct($_memberId, $_plantName, $_speciesName, $_waterPeriod, $_waterDate, $_plantId, $_files);

        $this->_adoptDate = $_adoptDate;
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



}