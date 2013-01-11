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
 * Adapter for Delicious
 *
 * @category Hackathon
 * @package Hackathon_Socialcommerce
 * @author Sylvain Rayé <sylvain.raye@gmail.com>
 */
class Hackathon_Socialcommerce_Model_Adapter_Delicious extends Hackathon_Socialcommerce_Model_Abstract
    implements Hackathon_Socialcommerce_Model_Adapter_Interface
{

    /**
     * @var Zend_Service_Delicious
     */
    protected $_client;

    /**
     * Send a single post to delicious
     *
     * @param Hackathon_Socialcommerce_Model_Messagetype_SinglePost $post
     */
    public function sendSinglePost ( Hackathon_Socialcommerce_Model_Messagetype_Singlepost $post )
    {
        if ( $this->_getConfig()->isDeliciousEnabled() )
        {
            try{
                $newPost = $this->_getClient()->createNewPost($post->getCaption(), $post->getLink())
                    ->setNotes($post->getText())
                    ->save();
            } catch (Zend_Service_Delicious_Exception $e) {
                Hackathon_Socialcommerce_Log::log("Could not send to Delicious: "  . $e->getMessage());
            }
                
        }
    }

    /**
     * Get the delicious client
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
