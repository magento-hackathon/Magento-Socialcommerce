<?php

class Hackathon_Socialcommerce_Log
{

    public static function log ( $message )
    {
        Mage::log($message, null, 'socialcommerce.log');
    }
}
