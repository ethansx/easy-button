<?php
/**
 * Created by Gauge Interactive
 * Date: 3/23/15
 * Time: 2:25 PM
 */
class GaugeInteractive_Apc_Model_Cache
{
    const XML_PATH_APC_ENABLED = 'apc/settings/enabled';

    public function flush()
    {
        $enabled = Mage::getStoreConfig(self::XML_PATH_APC_ENABLED);

        if ($enabled) {

            try {

                $isInstalled = extension_loaded('apc');

                if ($isInstalled) {
                    apc_clear_cache();
                    apc_clear_cache('user');
                    apc_clear_cache('opcode');

                    Mage::getSingleton('core/session')->addSuccess(
                        Mage::helper('gaugeinteractive_apc')->__('APC cache has been flushed.'));
                } else {
                    Mage::getSingleton('core/session')->addError(
                        Mage::helper('gaugeinteractive_apc')->__('APC is not installed.'));
                }

            } catch (Exception $e) {
                Mage::log("Exception when flushing APC cache : " . $e->getMessage());
                Mage::getSingleton('core/session')->addError($e->getMessage());
            }
            Mage::app()->getResponse()->setRedirect(Mage::helper('adminhtml')->getUrl("adminhtml/cache"));
        }
    }
}