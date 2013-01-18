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

/**
 * Adapter for Twitter
 *
 * @category Hackathon
 * @package Hackathon_Socialcommerce
 * @author Sylvain Rayé <sylvain.raye@gmail.com>
 */
class Hackathon_Socialcommerce_Model_Adapter_Twitter extends Hackathon_Socialcommerce_Model_Abstract
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
        $session = Mage::getSingleton('core/session');
        $helper = Mage::helper('socialcommerce');
        
        if ( $this->_getConfig()->isTwitterEnabled() )
        {
            try
            {
                if (strlen($post->getText()) > Zend_Service_Twitter::STATUS_MAX_CHARACTERS) {
                    Hackathon_Socialcommerce_Log::log("Message is too long for twitter");
                }

                $result = $this->_getClient()->statusUpdate($post->getText());
            }
            catch ( Zend_Service_Twitter_Exception $e )
            {
                Hackathon_Socialcommerce_Log::log("Could not send to Twitter: "  . $e->getMessage());
            }
            
            if (empty($result)) {
                $session->addNotice($helper->__('Tweet not posted!'));
            } else {
                $session->addNotice($helper->__('Tweet posted! ' . $post->getText()));
                Hackathon_Socialcommerce_Log::log(print_r($result, true));
            }
        }
        
        return $this;
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
                'accessToken'    => $access,
                'consumerKey'    => $this->_getConfig()->getTwitterConsumerKey(),
                'consumerSecret' => $this->_getConfig()->getTwitterConsumerSecret(),
            );

            $this->_client = new Zend_Service_Twitter( $params );
        }

        return $this->_client;
    }
}
