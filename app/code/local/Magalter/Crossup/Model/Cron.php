<?php

class Magalter_Crossup_Model_Cron
{
    public function reindexAll()
    {
        Mage::getResourceModel('magalter_crossup/crossup')->reindexAll();
    }
}