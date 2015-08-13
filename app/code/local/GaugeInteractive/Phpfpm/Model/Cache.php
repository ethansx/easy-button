<?php
/**
 * Created by Gauge Interactive
 * Date: 3/23/15
 * Time: 2:35 PM
 */
class GaugeInteractive_Phpfpm_Model_Cache
{
    const XML_PATH_PHPFPM_ENABLED = 'phpfpm/settings/enabled';
    const XML_PATH_PHPFPM_OS = 'phpfpm/settings/os';
    const XML_PATH_PHPFPM_DAEMON = 'phpfpm/settings/daemon';


    public function flush()
    {
        $enabled = Mage::getStoreConfig(self::XML_PATH_PHPFPM_ENABLED);
        $os = Mage::getStoreConfig(self::XML_PATH_PHPFPM_OS);
        $daemon = Mage::getStoreConfig(self::XML_PATH_PHPFPM_DAEMON);

        if ($enabled) {

            try {

                $isRunning = shell_exec('pgrep ' . $daemon);

                if ($isRunning) {

                    switch ($os) {
                        case 'centos':
                            $cmdDisable = 'service ' . $daemon . ' stop';
                            shell_exec($cmdDisable);
                            $cmdEnable = 'service ' . $daemon . ' start';
                            shell_exec($cmdEnable);
                            break;
                        case 'smartos':
                            $cmdDisable = 'svcadm disable ' . $daemon;
                            shell_exec($cmdDisable);
                            $cmdEnable = 'svcadm enable ' . $daemon;
                            shell_exec($cmdEnable);
                            break;
                        case 'ubuntu':
                            $cmd = 'service ' . $daemon . ' restart';
                            shell_exec($cmd);
                            break;
                    }

                    Mage::getSingleton('core/session')->addSuccess(
                        Mage::helper('gaugeinteractive_phpfpm')->__('PHP-FPM has been restarted.'));
                } else {
                    Mage::getSingleton('core/session')->addError(
                        Mage::helper('gaugeinteractive_apc')->__('PHP-FPM is not running.'));
                }


            } catch (Exception $e) {
                Mage::log("Exception when restarting PHP-FPM : " . $e->getMessage());
                Mage::getSingleton('core/session')->addError($e->getMessage());
            }
            Mage::app()->getResponse()->setRedirect(Mage::helper('adminhtml')->getUrl("adminhtml/cache"));
        }
    }
}