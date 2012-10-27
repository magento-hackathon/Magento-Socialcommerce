<?php

/**
 * Interface for every adapter
 */
interface Hackathon_Socialcommerce_Model_Adapter_Interface
{
    public function sendSinglePost(Hackathon_Socialcommerce_Model_Messagetype_Singlepost $post);
}
