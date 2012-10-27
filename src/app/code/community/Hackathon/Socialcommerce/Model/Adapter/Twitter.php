<?php

/**
 * Adapter
 */
class Hackathon_Socialcommerce_Model_Adapter_Twitter extends Hackathon_Socialcommerce_Model_Abstract
    implements Hackathon_Social_Model_Adapter_Interface
{

    /**
     * @var Zend_Service_Twitter
     */
    protected $_client;

    /**
     * Send a single post to twitter
     *
     * @param Hackathon_Socialcommerce_Model_Messagetype_SinglePost $post
     */
    public function sendSinglePost ( Hackathon_Socialcommerce_Model_Messagetype_Singlepost $post )
    {


        $this->_getClient()->statusUpdate($post->getText());
        // @todo picture
        // @todo shorten url
    }

    /**
     * Get the twitter client
     *
     * @return Zend_Service_Twitter
     */
    protected function _getClient ()
    {

        if ( null == $this->_client )
        {
            $access = new Zend_Oauth_Token_Access();
            $access->setToken($this->_getConfig()->getTwitterAuthToken())
                ->setTokenSecret($this->_getConfig()->getTwitterTokenSecret());

            $params = array(
                'accessToken'    => $this->_getConfig()->getTwitterAccessToken(),
                'consumerKey'    => $this->_getConfig()->getTwitterConsumerKey(),
                'consumerSecret' => $this->_getConfig()->getTwitterConsumerSecret(),
            );

            $this->_client = new Zend_Service_Twitter( $params );
        }

        return $this->_client;
    }
}
