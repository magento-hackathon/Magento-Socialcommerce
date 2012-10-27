<?php

class Hackathon_Socialcommerce_Model_Abstract
{
    /**
     * @return Hackathon_Socialcommerce_Model_Config
     */
    protected function _getConfig ()
    {
        return Mage::getModel('socialcommerce/config');
    }
}
