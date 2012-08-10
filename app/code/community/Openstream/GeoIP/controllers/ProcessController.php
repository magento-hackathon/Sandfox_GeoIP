<?php

class Openstream_GeoIP_ProcessController extends Mage_Core_Controller_Front_Action
{
    public function statusAction()
    {
        /** @var $_session Mage_Core_Model_Session */
        $_session = Mage::getSingleton('core/session');
        /** @var $info Openstream_GeoIP_Model_Info */
        $info = Mage::getModel('geoip/info');

        $_realSize = filesize($info->getArchivePath());
        $_totalSize = $_session->getData('_geoip_file_size');
        echo $_realSize / $_totalSize * 100;
    }

    public function synchronizeAction()
    {
        /** @var $info Openstream_GeoIP_Model_Info */
        $info = Mage::getModel('geoip/info');
        $info->update();
    }
}