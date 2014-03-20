<?php

class Openstream_GeoIP_Model_Info extends Openstream_GeoIP_Model_Abstract
{
    public function getDatFileDownloadDate($index = 'country')
    {
        return file_exists($this->files[$index]['local_file']) ? filemtime($this->files[$index]['local_file']) : 0;
    }
}