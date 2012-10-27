<?php

class Hackathon_Socialcommerce_Model_Abstract extends Varien_Object
{
    /**
     * @return Hackathon_Socialcommerce_Helper_Data
     */
    protected function _getConfig ()
    {
        return Mage::getHelper('socialcommerce/data');
    }
}
