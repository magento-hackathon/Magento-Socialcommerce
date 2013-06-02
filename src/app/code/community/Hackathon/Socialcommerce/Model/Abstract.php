<?php

/**
 * Abstract for every model
 */
class Hackathon_Socialcommerce_Model_Abstract extends Varien_Object
{
    /**
     * Get the configuration
     *
     * @return Hackathon_Socialcommerce_Helper_Data
     */
    protected function _getConfig ()
    {
        return Mage::helper('socialcommerce');
    }
}
