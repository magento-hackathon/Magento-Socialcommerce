<?php

class Hackathon_Socialcommerce_Model_Abstract extends Varien_Object
{
    /**
     * @return Hackathon_Socialcommerce_Model_Config
     */
    protected function _getConfig ()
    {
        return Mage::getModel('socialcommerce/config');
    }
}
