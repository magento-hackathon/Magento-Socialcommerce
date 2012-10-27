<?php

require "lib/facebook/base_facebook";
require "lib/facebook/facebook";

class Facebook extends Hackathon_Socialcommerce_Model_Abstract
    implements Hackathon_Socialcommerce_Model_Adapter_Interface
{

    protected $_client;

    public function sendSinglePost ( Hackathon_Socialcommerce_Model_Messagetype_Singlepost $post )
    {
        $facebook = $this->_getClient();

        $loginUrl = $facebook->getLoginUrl(
            array(
                 'canvas'    => 1,
                 'fbconnect' => 0,
                 'scope'     => 'offline_access,publish_stream'
            )
        );

        $user = $facebook->getUser();
        if ( $user )
        {
            $token = $facebook->getAccessToken();
        }

        //create message with token gained before
        $post = array(
            'access_token' => $token,
            'message'      => 'MESSAGE', /// @todo MESSAGE
            'link' => 'LINK',   /// @todo LINK
            'caption' => 'CAPTION', /// @todo caption
        );

        //and make the request
        $path = "/" . $this->_getConfig()->getFacebookUserId() . "/feed";
        $postId = $facebook->api($path, 'POST', $post);
    }

    /**
     * @return Facebook
     */
    public function _getClient ()
    {
        if ( null == $this->_client )
        {
            $this->_client = new Facebook( array(
                                                'appId'  => $this->_getConfig()->getFacebookAppId(),
                                                'secret' => $this->_getConfig()->getFacebookSecret(),
                                                'cookie' => true,
                                           ) );
        }

        return $this->_client;
    }
}
