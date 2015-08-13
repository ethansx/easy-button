<?php
class GaugeInteractive_EasyButton_EasybuttonController
    extends Mage_Adminhtml_Controller_Action
{
    public function clearallAction()
    {
        Mage::getModel('gaugeinteractive_easybutton/cache')->flush();
    }
}