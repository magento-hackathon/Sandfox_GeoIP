<?php

class Openstream_GeoIP_Block_System_Config_Sync_Status extends Mage_Adminhtml_Block_System_Config_Form_Field
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
    	$skinUrl = $this->getSkinUrl('images/process_spinner.gif');
		$retVal = <<<HTML

<span class="sync-indicator no-display" id="{$this->prefix}_sync_span"><img alt="Synchronize" style="margin:0 5px" src="{$skinUrl}"/><span id="{$this->prefix}_sync_message_span"></span></span>
&nbsp; <span class="no-display error" id="{$this->prefix}_sync_span_error"></span>

HTML;
        return $retVal;
    }
}
