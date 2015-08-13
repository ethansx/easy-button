<?php
class GaugeInteractive_Redis_RedisController
    extends Mage_Adminhtml_Controller_Action
{
    public function clearallAction()
    {
        Mage::getModel('gaugeinteractive_redis/cache')->flush();
    }
}