<?php
class GaugeInteractive_Apc_ApcController
    extends Mage_Adminhtml_Controller_Action
{
    public function clearallAction()
    {
        Mage::getModel('gaugeinteractive_apc/cache')->flush();
    }
}