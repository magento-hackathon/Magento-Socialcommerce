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
 * Bitly Helper to shorten urls
 *
 * @category Hackathon
 * @package Hackathon_Socialcommerce
 * @author Sylvain RayŽ <sylvain.raye@gmail.com>
 */
class Hackathon_Socialcommerce_Model_Adapter_Bitly extends Mage_Core_Helper_Abstract
{
	const ENDPOINT_URL = 'https://api-ssl.bitly.com';

	private $_token;

	public function __construct ()
	{
		$this->init();
		return $this;
	}

	public function init ()
	{
		$helper = Mage::helper('socialcommerce');

		$username = $helper->getBitlyUsername();
		$password = $helper->getBitlyPassword();

		$tokenUrl = self::ENDPOINT_URL . '/oauth/access_token';

		$config = array(
				'adapter' => 'Zend_Http_Client_Adapter_Curl',
				'curl_options' => array (
						'CURLOPT_URL' => $tokenUrl,
						'CURLOPT_SSL_VERIFYPEER' => true,
						'CURLOPT_SSL_VERIFYHOST' => 2,
						'CURLOPT_FAILONERROR' => true,
						'CURLOPT_RETURNTRANSFER' => true
				)
		);

		$client = new Zend_Http_Client($tokenUrl, $config);
		$client->setAuth($username, $password);
		$this->_token = $client->request('POST')->getBody();

		return $this;
	}

    public function getToken ()
    {
    	return $this->_token;
    }
}
