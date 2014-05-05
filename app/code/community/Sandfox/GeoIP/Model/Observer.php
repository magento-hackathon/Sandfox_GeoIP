<?php

class Sandfox_GeoIP_Model_Observer
{
    public function controllerFrontInitBefore($observer)
    {
        Mage::getModel('geoip/country');
    }
}