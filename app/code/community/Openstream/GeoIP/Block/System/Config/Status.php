<?php

class Openstream_GeoIP_Block_System_Config_Status extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    public $prefix = 'country';
    /**
     * Remove scope label
     *
     * @param  Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $element->unsScope()->unsCanUseWebsiteValue()->unsCanUseDefaultValue();
        return parent::render($element);
    }

    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        /** @var $info Openstream_GeoIP_Model_Info */
        $info = Mage::getModel('geoip/info');
        if ($date = $info->getDatFileDownloadDate($this->prefix)) {
            $format = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM);
            $date = Mage::app()->getLocale()->date(intval($date))->toString($format);
        } else {
            $date = '-';
        }
        return '<div id="' . $this->prefix . '_sync_update_date">' . $date . '</div>';
    }
}
