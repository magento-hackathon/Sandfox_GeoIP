<?php

class Openstream_GeoIP_Model_Abstract
{
    protected $local_dir, $local_file, $local_archive, $remote_archive;

    public function __construct()
    {
        $this->local_dir = 'geoip';
        $this->local_file = Mage::getBaseDir('var') . '/' . $this->local_dir . '/GeoIP.dat';
        $this->local_archive = Mage::getBaseDir('var') . '/' . $this->local_dir . '/GeoIP.dat.gz';
        $this->remote_archive = 'http://www.maxmind.com/download/geoip/database/GeoLiteCountry/GeoIP.dat.gz';
    }

    public function getArchivePath()
    {
        return $this->local_archive;
    }

    public function checkFilePermissions()
    {
        /** @var $helper Openstream_GeoIP_Helper_Data */
        $helper = Mage::helper('geoip');

        $dir = Mage::getBaseDir('var') . '/' . $this->local_dir;
        if (file_exists($dir)) {
            if (!is_dir($dir)) {
                return sprintf($helper->__('%s exists but it is file, not dir.'), 'var/' . $this->local_dir);
            } elseif ((!file_exists($this->local_file) || !file_exists($this->local_archive)) && !is_writable($dir)) {
                return sprintf($helper->__('%s exists but files are not and directory is not writable.'), 'var/' . $this->local_dir);
            } elseif (file_exists($this->local_file) && !is_writable($this->local_file)) {
                return sprintf($helper->__('%s is not writable.'), 'var/' . $this->local_dir . '/GeoIP.dat');
            } elseif (file_exists($this->local_archive) && !is_writable($this->local_archive)) {
                return sprintf($helper->__('%s is not writable.'), 'var/' . $this->local_dir . '/GeoIP.dat.gz');
            }
        } elseif (!@mkdir($dir)) {
            return  sprintf($helper->__('Can\'t create %s directory.'), 'var/' . $this->local_dir);
        }

        return '';
    }

    public function update(){
        /** @var $helper Openstream_GeoIP_Helper_Data */
        $helper = Mage::helper('geoip');

        $ret = array('status' => 'error');

        if ($permissions_error = $this->checkFilePermissions()) {
            $ret['message'] = $permissions_error;
        } else {
            $remote_file_size = $helper->getSize($this->remote_archive);
            if ($remote_file_size < 100000) {
                $ret['message'] = $helper->__('You are banned from downloading the file. Please try again in several hours.');
            } else {
                /** @var $_session Mage_Core_Model_Session */
                $_session = Mage::getSingleton('core/session');
                $_session->setData('_geoip_file_size', $remote_file_size);

                $src = fopen($this->remote_archive, 'r');
                $target = fopen($this->local_archive, 'w');
                stream_copy_to_stream($src, $target);
                fclose($target);

                if (filesize($this->local_archive)) {
                    if ($helper->unGZip($this->local_archive, $this->local_file)) {
                        $ret['status'] = 'success';
                        $format = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM);
                        $ret['date'] = Mage::app()->getLocale()->date(filemtime($this->local_file))->toString($format);
                    } else {
                        $ret['message'] = $helper->__('UnGzipping failed');
                    }
                } else {
                    $ret['message'] = $helper->__('Download failed.');
                }
            }
        }

        echo json_encode($ret);
    }
}