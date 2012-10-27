<?php

class Hackathon_Socialcommerce_Log
{

    /**
     * Logs a message to socialcommerce.log
     *
     * @param $message string
     */
    public static function log ( $message )
    {
        Mage::log($message, null, 'socialcommerce.log');
    }
}
