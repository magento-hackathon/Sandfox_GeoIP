<?php

class Sandfox_GeoIP_Adminhtml_GeoipController extends Mage_Adminhtml_Controller_Action
{
    public function statusAction()
    {
        /** @var $_session Mage_Core_Model_Session */
        $_session = Mage::getSingleton('core/session');
        /** @var $info Sandfox_GeoIP_Model_Info */
        $info = Mage::getModel('geoip/info');

        $_realSize = filesize($info->getArchivePath());
        $_totalSize = $_session->getData('_geoip_file_size');
        echo $_totalSize ? $_realSize / $_totalSize * 100 : 0;
    }

    public function synchronizeAction()
    {
        /** @var $info Sandfox_GeoIP_Model_Info */
        $info = Mage::getModel('geoip/info');
        $info->update();
    }
}
