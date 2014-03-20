<?php
class Openstream_GeoIP_Block_Adminhtml_Widget_Grid_Column_Renderer_Location extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * Returns the Country name by IP
     *
     * @param Varien_Object $row
     * @return mixed|string
     */
    protected function _getValue(Varien_Object $row)
    {
    	$retVal = 'n/a';
        $data = parent::_getValue($row);
        if (!is_null($data) && ($value = filter_var($data, FILTER_VALIDATE_IP)))
        {
        	$geoIP = Mage::getSingleton('geoip/country');
        	$retVal = $geoIP->getCountryByIp($value);
        }
        return $retVal;
    }

}