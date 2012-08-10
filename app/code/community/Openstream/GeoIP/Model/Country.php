<?php

class Openstream_GeoIP_Model_Country extends Openstream_GeoIP_Model_Abstract
{
    private $country;
    private $allowed_countries = array();

    public function __construct()
    {
        parent::__construct();

        /** @var $wrapper Openstream_GeoIP_Model_Wrapper */
        $wrapper = Mage::getModel('geoip/wrapper');
        if ($wrapper->geoip_open($this->local_file, 0)) {
            $this->country = $wrapper->geoip_country_code_by_addr($_SERVER['REMOTE_ADDR']);
            $wrapper->geoip_close();

            $allowCountries = explode(',', (string)Mage::getStoreConfig('general/country/allow'));
            $this->addAllowedCountry($allowCountries);
        }
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function isCountryAllowed($country = '')
    {
        $country = $country ? $country : $this->country;
        if (count($this->allowed_countries) && $country) {
            return in_array($country, $this->allowed_countries);
        } else {
            return true;
        }
    }

    public function addAllowedCountry($countries)
    {
        $countries = is_array($countries) ? $countries : array($countries);
        $this->allowed_countries = array_merge($this->allowed_countries, $countries);

        return $this;
    }
}