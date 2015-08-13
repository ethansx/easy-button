<?php

class GaugeInteractive_EasyButton_Model_Source_Frequency
{
    public function toOptionArray()
    {
        $interval = array(
            array('value' => null, 'label' => '--- Select Frequency ---'),
            array('value' => '*/30 * * * *', 'label' => 'Every 30 Minutes'),
            array('value' => '01 */1 * * *', 'label' => 'Every 1 Hour'),
            array('value' => '01 */4 * * *', 'label' => 'Every 4 Hours'),
            array('value' => '01 */12 * * *', 'label' => 'Every 12 Hours'),
            array('value' => '01 0 * * *', 'label' => 'Daily At Midnight'),
            array('value' => '0 0 * * 0', 'label' => 'Every Week'), //Run once a week at midnight on Sunday morning
            array('value' => '0 0 1 * *', 'label' => 'Every Month'), //Run once a month at midnight of the first day of the month
            array('value' => 'custom', 'label' => 'Custom Cron')
        );

        return $interval;
    }
}