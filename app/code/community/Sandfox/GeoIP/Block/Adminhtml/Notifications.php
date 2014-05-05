<?php

class Sandfox_GeoIP_Block_Adminhtml_Notifications extends Mage_Adminhtml_Block_Template
{
    public function checkFilePermissions()
    {
        /** @var $info Sandfox_GeoIP_Model_Info */
        $info = Mage::getModel('geoip/info');
        return $info->checkFilePermissions();
    }
}
