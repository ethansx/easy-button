<?php
class GaugeInteractive_Cloudflare_CloudflareController
    extends Mage_Adminhtml_Controller_Action
{
    public function clearallAction()
    {
        Mage::getModel('gaugeinteractive_cloudflare/cache')->flush();
    }
}