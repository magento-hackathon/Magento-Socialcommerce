<?php

/**
 * Adapter
 */
class Hackathon_Socialcommerce_Model_Adapter_Delicious extends Hackathon_Socialcommerce_Model_Abstract
    implements Hackathon_Socialcommerce_Model_Adapter_Interface
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
        if ( $this->_getConfig()->isDeliciousEnabled() )
        {
            $newPost = $this->_getClient()->createNewPost($post->getCaption(), $post->getLink())
                ->setNotes($post->getText())
                ->save();
        }
    }

    /**
     * Get the twitter client
     *
     * @return Zend_Service_Delicious
     */
    protected function _getClient ()
    {

        if ( null == $this->_client )
        {
            $this->_client = new Zend_Service_Delicious(
                $this->_getConfig()->getDeliciousUsername(),
                $this->_getConfig()->getDeliciousPassword()
            );
        }

        return $this->_client;
    }
}
