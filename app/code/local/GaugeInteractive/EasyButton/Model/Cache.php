<?php
/**
 * Created by Gauge Interactive
 * Date: 3/23/15
 * Time: 2:43 PM
 */
class GaugeInteractive_EasyButton_Model_Cache
{
    const XML_PATH_EASYBUTTON_ENABLED = 'easybutton/settings/enabled';

    public function flush()
    {
        $enabled = Mage::getStoreConfig(self::XML_PATH_EASYBUTTON_ENABLED);

        if ($enabled) {

            try {

                $cacheModules = array(
                    'GaugeInteractive_Phpfpm',
                    'GaugeInteractive_Redis',
                    'GaugeInteractive_Cloudflare',
                    'GaugeInteractive_Apc'
                );

                $installedCacheModules = array();

                foreach ($cacheModules as $cacheModule) {
                    // app/etc/modules/*.xml
                    if (Mage::helper('core')->isModuleEnabled($cacheModule)) {
                        // This cache module is enabled
                        $array = explode('_', $cacheModule);
                        array_push($installedCacheModules, strtolower($array[1]));
                    } else {
                        // This cache module is not enabled
                        Mage::log($cacheModule . ' is not installed', null, 'easybutton.log');
                    }
                }

                // within installed modules, check if they are enabled in system > configuration
                foreach ($installedCacheModules as $installedCacheModule) {
                    if (Mage::getStoreConfig($installedCacheModule . '/settings/enabled')) {
                        // This cache module is enabled

                        Mage::getModel('gaugeinteractive_' . $installedCacheModule . '/cache')->flush();
                        Mage::log($installedCacheModule . ' cache has been flushed', null, 'easybutton.log');
                    } else {
                        // This cache module is not enabled
                        Mage::log($installedCacheModule . ' is not enabled', null, 'easybutton.log');
                    }
                }

                // Flush Magento Cache
                Mage::dispatchEvent('adminhtml_cache_flush_all');
                Mage::app()->getCacheInstance()->flush();

                Mage::log('Magento cache has been flushed', null, 'easybutton.log');

                Mage::getSingleton('core/session')->addSuccess(
                    Mage::helper('gaugeinteractive_cloudflare')->__('All Magento cache has been flushed.'));

                Mage::getSingleton('core/session')->addSuccess(
                    Mage::helper('gaugeinteractive_cloudflare')->__('That was easy!'));

            } catch (Exception $e) {
                Mage::log("Exception when using Easy Button : " . $e->getMessage());
                Mage::getSingleton('core/session')->addError($e->getMessage());
            }
            Mage::app()->getResponse()->setRedirect(Mage::helper('adminhtml')->getUrl("adminhtml/cache"));
        }
    }
}