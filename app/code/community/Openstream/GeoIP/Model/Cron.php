<?php

class Openstream_GeoIP_Model_Cron
{
    public function run()
    {
        /** @var $info Openstream_GeoIP_Model_Info */
        $info = Mage::getModel('geoip/info');
        $info->update();
    }
}