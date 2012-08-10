<?php

class Openstream_GeoIP_Model_Info extends Openstream_GeoIP_Model_Abstract
{
    public function getDatFileDownloadDate()
    {
        return filemtime($this->local_file);
    }
}