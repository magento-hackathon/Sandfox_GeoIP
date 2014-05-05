<?php

class Sandfox_GeoIP_Model_Info extends Sandfox_GeoIP_Model_Abstract
{
    public function getDatFileDownloadDate()
    {
        return file_exists($this->local_file) ? filemtime($this->local_file) : 0;
    }
}