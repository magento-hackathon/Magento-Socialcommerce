<?php

/**
 * Hackathon
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Hackathon
 * @package     Hackathon_Socialcommerce
 * @copyright   Copyright (c) 2012 Hackathon
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

require "lib/facebook/base_facebook";
require "lib/facebook/facebook";

/**
 * Adapter for Facebook
 *
 * @category Hackathon
 * @package Hackathon_Socialcommerce
 * @author Sylvain Rayé <sylvain.raye@gmail.com>
 */
class Hackathon_Socialcommerce_Model_Adapter_Facebook extends Hackathon_Socialcommerce_Model_Abstract
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
        $apiPost = array(
            'access_token' => $token,
            'message'      => $post->getText(),
            'link'         => $post->getLink(),
            'caption'      => $post->getCaption(),
        );

        //and make the request
        $path   = "/" . $this->_getConfig()->getFacebookUserId() . "/feed";
        $postId = $facebook->api($path, 'POST', $apiPost);
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
