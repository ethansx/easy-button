<?php
/**
 * Created by Gauge Interactive
 * Date: 3/23/15
 * Time: 2:37 PM
 */
class GaugeInteractive_Redis_Model_Cache
{
    const XML_PATH_REDIS_ENABLED = 'redis/settings/enabled';

    const XML_PATH_REDIS_SERVER_INSTANCES = 'redis/server/instances';


    public function flush()
    {
        $enabled = Mage::getStoreConfig(self::XML_PATH_REDIS_ENABLED);

        $instances = Mage::getStoreConfig(self:: XML_PATH_REDIS_SERVER_INSTANCES);


        if ($enabled) {

            try {

                // Check if PHP-Redis module is installed
                $isInstalled = extension_loaded('redis');

                if ($isInstalled) {

                    if (!empty($instances)) {

                        // remove line breaks and whitespaces
                        $instances = $this->rmLineBreakandWhiteSpace($instances);

                        $activeInstances = explode(';', trim($instances));

                        foreach ($activeInstances as $instance) {
                            $settings = explode(',', $instance);

                            if (array_key_exists(0, $settings) && array_key_exists(1, $settings) && array_key_exists(2, $settings)) {

                                $host = $settings[0];
                                $port = $settings[1];
                                $db = $settings[2];

                                // flush each Redis instance
                                $this->connectRedis($host, $port, $db);
                            }
                        }
                    }

                } else {
                    Mage::getSingleton('core/session')->addError(
                        Mage::helper('gaugeinteractive_redis')->__('PHP-Redis is not installed.'));
                }


            } catch (Exception $e) {
                Mage::log("Exception when flushing Redis cache : " . $e->getMessage());
                Mage::getSingleton('core/session')->addError($e->getMessage());
            }
            Mage::app()->getResponse()->setRedirect(Mage::helper('adminhtml')->getUrl("adminhtml/cache"));
        }
    }

    public function rmLineBreakandWhiteSpace($input)
    {
        $output = str_replace(array("\r\n", "\r"), "\n", $input);
        $lines = explode("\n", $output);
        $new_lines = array();

        foreach ($lines as $i => $line) {
            if (!empty($line))
                $new_lines[] = trim($line);
        }
        return $output = implode($new_lines);
    }

    public function connectRedis($host, $port, $db)
    {
        // Cache in Redis
        $cacheRedis = new Redis();
        $cacheRedis->connect($host, $port);
        $cacheRedis->select($db);
        $cacheResult = $cacheRedis->flushAll();

        if ($cacheResult == 1) {
            Mage::getSingleton('core/session')->addSuccess(
                Mage::helper('gaugeinteractive_redis')->__('Redis has been flushed.'));
        } else {
            Mage::getSingleton('core/session')->addError(
                Mage::helper('gaugeinteractive_redis')->__('An error occurred when flushing Redis.'));
        }
    }

}