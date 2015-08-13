<?php
/**
 * Created by Gauge Interactive
 * Date: 3/23/15
 * Time: 2:32 PM
 */
class GaugeInteractive_Cloudflare_Model_Cache
{
    const XML_PATH_CLOUDFLARE_ENABLED = 'cloudflare/settings/enabled';
    const XML_PATH_CLOUDFLARE_ACCOUNT_EMAIL = 'cloudflare/settings/account_email';
    const XML_PATH_CLOUDFLARE_DOMAIN = 'cloudflare/settings/domain';
    const XML_PATH_CLOUDFLARE_TOKEN = 'cloudflare/settings/token';

    public function flush()
    {
        $enabled = Mage::getStoreConfig(self::XML_PATH_CLOUDFLARE_ENABLED);
        $email = Mage::getStoreConfig(self::XML_PATH_CLOUDFLARE_ACCOUNT_EMAIL);
        $domain = Mage::getStoreConfig(self::XML_PATH_CLOUDFLARE_DOMAIN);
        $token = Mage::getStoreConfig(self::XML_PATH_CLOUDFLARE_TOKEN);

        if ($enabled) {

            try {

                $fields = array(
                    'a' => 'fpurge_ts',
                    'tkn' => $token,
                    'email' => $email,
                    'z' => $domain,
                    'v' => 1
                );
                $fields_string = '';

                //url-ify the data for the POST
                foreach ($fields as $key => $value) {
                    $fields_string .= $key . '=' . $value . '&';
                }
                rtrim($fields_string, '&');

                //open connection
                $ch = curl_init();

                //set the url, number of POST vars, POST data
                curl_setopt($ch, CURLOPT_URL, 'https://www.cloudflare.com/api_json.html');
                curl_setopt($ch, CURLOPT_POST, count($fields));
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

                //execute post
                $result = curl_exec($ch);

                //close connection
                curl_close($ch);

                $obj = json_decode($result);

                switch ($obj->{'result'}) {
                    case 'success':
                        Mage::getSingleton('core/session')->addSuccess(
                            Mage::helper('gaugeinteractive_cloudflare')->__('Cloudflare cache has been flushed.'));
                        break;
                    case 'error':
                        Mage::getSingleton('core/session')->addError(
                            Mage::helper('gaugeinteractive_cloudflare')->__($obj->{'msg'}));
                        break;
                }

            } catch (Exception $e) {
                Mage::log("Exception when flushing Cloudflare cache : " . $e->getMessage());
                Mage::getSingleton('core/session')->addError($e->getMessage());
            }
            Mage::app()->getResponse()->setRedirect(Mage::helper('adminhtml')->getUrl("adminhtml/cache"));
        }
    }
}