<?php
class GaugeInteractive_Phpfpm_PhpfpmController
    extends Mage_Adminhtml_Controller_Action
{

    public function clearallAction()
    {
        Mage::getModel('gaugeinteractive_phpfpm/cache')->flush();
    }
}