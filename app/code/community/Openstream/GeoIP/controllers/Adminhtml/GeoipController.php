<?php

class Openstream_GeoIP_Adminhtml_GeoipController extends Mage_Adminhtml_Controller_Action
{
    public function statusAction()
    {
        $index = $this->getRequest()->getParam('index');
        /** @var $_session Mage_Core_Model_Session */
        $_session = Mage::getSingleton('core/session');
        /** @var $info Openstream_GeoIP_Model_Info */
        $info = Mage::getModel('geoip/info');
        $_realSize = filesize($info->getArchivePath($index));
        $_totalSize = $_session->getData('_' . $index . '_geoip_file_size');
        echo $_totalSize ? $_realSize / $_totalSize * 100 : 0;
    }

    public function synchronizeAction()
    {
        $index = $this->getRequest()->getParam('index');
        /** @var $info Openstream_GeoIP_Model_Info */
        $info = Mage::getModel('geoip/info');
        $info->update($index);
    }
}
