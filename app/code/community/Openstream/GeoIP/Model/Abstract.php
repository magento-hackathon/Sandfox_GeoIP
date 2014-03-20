<?php

class Openstream_GeoIP_Model_Abstract
{
    protected $files;

    public function __construct()
    {
        $localDir = 'geoip';
        $this->files['country'] = (new Openstream_GeoIP_Model_File())
            ->setLocalDir($localDir)
            ->setLocalFile(Mage::getBaseDir('var') . '/' . $localDir . '/GeoIP.dat')
            ->setLocalArchive(Mage::getBaseDir('var') . '/' . $localDir . '/GeoIP.dat.gz')
            ->setRemoteArchive('http://www.maxmind.com/download/geoip/database/GeoLiteCountry/GeoIP.dat.gz');
        $this->files['city'] = (new Openstream_GeoIP_Model_File())
            ->setLocalDir($localDir)
            ->setLocalFile(Mage::getBaseDir('var') . '/' . $localDir . '/GeoLiteCity.dat')
            ->setLocalArchive(Mage::getBaseDir('var') . '/' . $localDir . '/GeoLiteCity.dat.gz')
            ->setRemoteArchive('http://geolite.maxmind.com/download/geoip/database/GeoLiteCity.dat.gz');
    }

    public function getArchivePath($index = 'country')
    {
        return $this->files[$index]['local_archive'];
    }

    public function getFilePath($index = 'country')
    {
        return $this->files[$index]['local_file'];
    }

    public function checkFilePermissions($index = 'country')
    {
        /** @var $helper Openstream_GeoIP_Helper_Data */
        $helper = Mage::helper('geoip');

        $file = $this->files[$index];
        $dir = Mage::getBaseDir('var') . '/' . $file['local_dir'];
        if (file_exists($dir)) {
            if (!is_dir($dir)) {
                return sprintf($helper->__('%s exists but it is file, not dir.'), 'var/' . $file['local_dir']);
            } elseif ((!file_exists($file['local_file']) || !file_exists($file['local_archive'])) && !is_writable($dir)) {
                return sprintf($helper->__('%s exists but files are not and directory is not writable.'), 'var/' . $file['local_dir']);
            } elseif (file_exists($file['local_file']) && !is_writable($file['local_file'])) {
                return sprintf($helper->__('%s is not writable.'), 'var/' . $file['local_dir'] . '/GeoIP.dat');
            } elseif (file_exists($file['local_archive']) && !is_writable($file['local_archive'])) {
                return sprintf($helper->__('%s is not writable.'), 'var/' . $file['local_dir'] . '/GeoIP.dat.gz');
            }
        } elseif (!@mkdir($dir)) {
            return  sprintf($helper->__('Can\'t create %s directory.'), 'var/' . $file['local_dir']);
        }

        return '';
    }

    public function update($index = 'country')
    {
        Mage::log('Updating GeoIP Database');
        /** @var $helper Openstream_GeoIP_Helper_Data */
        $helper = Mage::helper('geoip');

        $ret = array('status' => 'error');
        $file = $this->files[$index];

        if ($permissions_error = $this->checkFilePermissions()) {
            $ret['message'] = $permissions_error;
        } else {
            $remote_file_size = $helper->getSize($file['remote_archive']);
            if ($remote_file_size < 100000) {
                $ret['message'] = $helper->__('You are banned from downloading the file. Please try again in several hours.');
            } else {
                /** @var $_session Mage_Core_Model_Session */
                $_session = Mage::getSingleton('core/session');
                $_session->setData('_' . $index . '_geoip_file_size', $remote_file_size);

                $src = fopen($file['remote_archive'], 'r');
                $target = fopen($file['local_archive'], 'w');
                stream_copy_to_stream($src, $target);
                fclose($target);

                if (filesize($file['local_archive'])) {
                    if ($helper->unGZip($file['local_archive'], $file['local_file'])) {
                        $ret['status'] = 'success';
                        $format = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM);
                        $ret['date'] = Mage::app()->getLocale()->date(filemtime($file['local_file']))->toString($format);
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