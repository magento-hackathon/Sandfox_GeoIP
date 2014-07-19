Overview
========

This extension allows you to use free [GeoIP Lite](http://www.maxmind.com/app/geolite) database by [MaxMind](http://www.maxmind.com/) with your Magento installation.

Installation
============

Just copy all files to your Magento installation respecting directory structure. Make sure your `var` directory is writable by web-server user (by default it should be). If you want GeoIP database to be updated automatically make sure your Magento cron-job is set up and running. Logging out and in is not required.

Configuration
=============

After installation a new field will be added to *System\Configuration\General\General\Countries Options* group indicating the date of last database update and the button to force synchronization. Note that frequent synchronizations will result you being banned at MaxMind server for several hours. MaxMind GeoIP Lite database is updated on first Thursday of the month so if you have have Magento cron-job running you will not need to force synchronization anymore but on the first run.

Usage
=====

This extension only adds MaxMind GeoIP Lite database to your Magento installation and maintains its updates. It doesn't do anything else out of the box (yet). However if comes with a set of methods you can implement in your template or extension.

    $geoIP = Mage::getSingleton('geoip/country');

    /**
     * Returns you the ISO 3166-1 code of visitors country.
     */
    echo $geoIP->getCountry();

    /**
     * Returns true or false depending if current visitors country is among allowed countries.
     * If there are no allowed countries in the system returns true.
     */
    if ($geoIP->isCountryAllowed()) {
        // ...
    }

    /**
     * Adds country (or array of countries) to list of allowed countries.
     */
    $geoIP->addAllowedCountry('DE');
    $geoIP->addAllowedCountry(array(
        'US',
        'CA'
    ));

    /**
     * Or just get a country code of a specific IP
     */
    echo $geoIP->getCountryByIp('94.230.212.77');

Please note that `geoip/country` is initialized at each Magento load. In order to save system resources please always use it as a singleton.

The list of allowed countries is initially populated from *System\Configuration\General\General\Countries Options\Alowed Countries* multi-select field of your Magento configuration. The `addAllowedCountry` method is not adding country to system configuration but adds it to internal list extension uses for `isCountryAllowed` method.

Global Restrict
===============

To globally restrict access to your site by IP consider extending the controller_front_init_before event in your own module and add dependancy to geoip

Credits
=======

Thanks a lot to MaxMind for providing a free database. Additionally this extension partly uses the code from [MaxMind GeoIP PHP API](http://www.maxmind.com/download/geoip/api/php/).
