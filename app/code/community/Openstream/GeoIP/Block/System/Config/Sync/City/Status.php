<?php

class Openstream_GeoIP_Block_System_Config_Sync_City_Status extends Openstream_GeoIP_Block_System_Config_Sync_Status
{
	public function _construct()
	{
		$this->prefix = 'city';
		parent::_construct();
	}
}
