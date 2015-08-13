<?php
/**
 * Created by Gauge Interactive
 * Date: 3/19/15
 * Time: 11:13 AM
 */
class GaugeInteractive_Phpfpm_Model_Source_Os{
    public function toOptionArray()
    {
        $interval = array(
            array('value' => null, 'label' => 'Choose a OS type'),
            array('value' => 'centos', 'label' => 'Centos'),
            array('value' => 'smartos', 'label' => 'SmartOS'),
            array('value' => 'ubuntu', 'label' => 'Ubuntu')
        );

        return $interval;
    }
}