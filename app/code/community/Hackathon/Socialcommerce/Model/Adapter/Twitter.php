<?php

class Hackathon_Socialcommerce_Model_Twitter extends Hackathon_Socialcommerce_Model_Abstract
    implements Hackathon_Social_Model_Interface
{

    protected static $_connector;

    public function sendSinglePost ( Hackathon_Socialcommerce_Model_Messagetype_SinglePost $post )
    {
        $twitter = new Zend_Service_Twitter( array(
                                                  'username'    => $this->_getConfig()->getTwitterUsername(),
                                                  'accessToken' => $this->_getConfig()->getTwitterPassword()
                                             ) );

        $twitter->statusUpdate($post->getText());
        // @todo picture
        // @todo shorten url
    }
}
