<?php

class Openstream_GeoIP_Block_System_Config_City_Status extends Openstream_GeoIP_Block_System_Config_Status
{
    public function _construct()
    {
        $this->prefix = 'city';
        parent::_construct();
    }
}
