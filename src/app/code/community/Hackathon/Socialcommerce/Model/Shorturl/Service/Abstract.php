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
 * Abstract Class for Shorturl services
 *
 * @category Hackathon
 * @package Hackathon_Socialcommerce
 * @author Sylvain Rayé <sylvain.raye@gmail.com>
 */
abstract class Hackathon_Socialcommerce_Model_Shorturl_Service_Abstract extends Varien_Object
{

    protected $_name;

    /**
     * Get current service name
     *
     * @return string
     */
    public function getName ()
    {
        return $this->_name;
    }

    /**
     *
     * @param $configuration array
     * @return Zend_Http_Client
     */
    public function getHttpClient ($configuration = array())
    {
        if (! isset($this->_data['http_client'])) {
            $this->_data['http_client'] = new Zend_Http_Client(null,
                    array_merge($configuration,
                            array(
                                    'timeout' => 10
                            )));
        }
        return $this->_data['http_client'];
    }

    /**
     *
     * @param string $longurl
     *
     * @return Varien_Object
     */
    abstract public function shorten ($longurl);

    /**
     *
     * @param string $shorturl
     *
     * @return Varien_Object
     */
    abstract public function expand ($shorturl);
}

