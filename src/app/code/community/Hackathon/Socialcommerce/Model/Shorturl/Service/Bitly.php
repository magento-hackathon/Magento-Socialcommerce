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
 * Bitly Service API
 *
 * @category Hackathon
 * @package Hackathon_Socialcommerce
 * @author Sylvain Rayé <sylvain.raye@gmail.com>
 */
class Hackathon_Socialcommerce_Model_Shorturl_Service_Bitly extends Hackathon_Socialcommerce_Model_Shorturl_Service_Abstract
{
    const ENDPOINT_URL = 'https://api-ssl.bitly.com';

    const CONFIG_PATH_DOMAIN = 'socialcommerce/urlshortenerservice_bitly/domain';

    protected $_name = 'bit.ly';

    protected $_token;

    protected $_lastResponse;

    public function init()
    {
        $config = $this->getConfiguration();
        if ($config['password']) {
            $config['password'] = Mage::helper('core')->decrypt($config['password']);
        } else if ($config['access_token']) {
            $this->_token = $config['access_token'];
            return $this;
        } else {
            return $this;
        }

        if (empty($this->_token)) {

            $url = Zend_Uri::factory(self::ENDPOINT_URL . '/oauth/access_token');

            $httpClientConfiguration = array(
                    'adapter' => 'Zend_Http_Client_Adapter_Curl',
                    'storeresponse' => true,
                    'curl_options' => array(
                            'CURLOPT_URL' => $url->__toString(),
                            'CURLOPT_SSL_VERIFYPEER' => true,
                            'CURLOPT_SSL_VERIFYHOST' => 2,
                            'CURLOPT_FAILONERROR' => true,
                            'CURLOPT_RETURNTRANSFER' => true
                    )
            );
            $client = $this->getHttpClient($httpClientConfiguration);
            $client->setUri($url);
            $client->setAuth($config['login'], $config['password']);

            $response = $client->request('POST');

            if ($response->isError()) {
                throw new Exception(
                        'HTTP Error: ' . $response->getStatus() . ' ' .
                        $response->getMessage());
            }

            $this->_token = $response->getBody();

            if (empty($this->_token)) {
                throw new Exception ('Bitly Token empty!');
            }

        }
        return $this;
    }

    /**
     * Shorten API for bitly
     *
     * @todo change the legacy API Key protocol to OAuth2 protocol
     *
     * @param string $longUrl
     * @throws Exception
     * @return string
     */
    public function shorten ($longUrl)
    {
        $this->init();

        $url = Zend_Uri::factory(self::ENDPOINT_URL . '/v3/shorten');

        $client = $this->getHttpClient();
        $response = $client->setUri($url)
            ->setParameterPost('format', 'json')
            ->setParameterPost('access_token', $this->getToken())
            ->setParameterPost('longUrl', $longUrl)
            ->setParameterPost('domain', Mage::getStoreConfig(self::CONFIG_PATH_DOMAIN))
            ->request('POST');

        if ($response->isError()) {
            throw new Exception(
                    'HTTP Error: ' . $response->getStatus() . ' ' .
                             $response->getMessage());
        }

        $jsonResponse = new Varien_Object(Zend_Json::decode($response->getBody()));

        if ($jsonResponse->getStatusCode() != '200') {
            throw new Exception(
                    'Bitly Error: ' . $jsonResponse->getStatusCode() . ' ' .
                             $jsonResponse->getStatusTxt());
        }

        $this->_lastResponse = new Varien_Object($jsonResponse->getData('data')); // attention: Bitly return a key 'data'
        return $this->_lastResponse->getUrl();
    }

    /**
     *
     * @param string $shortUrl
     *
     * @throws Exception
     * @return string
     */
    public function expand ($shortUrl)
    {
        $this->init();

        $url = Zend_Uri::factory(self::ENDPOINT_URL . '/v3/expand');

        $client = $this->getHttpClient();
        $response = $client->setUri($url)
            ->setParameterPost('format', 'json')
            ->setParameterPost('access_token', $this->getToken())
            ->setParameterPost('shortUrl', $shortUrl)
            ->request('POST');

        if ($response->isError()) {
            throw new Exception(
                    'HTTP Error: ' . $response->getStatus() . ' ' .
                             $response->getMessage());
        }

        Mage::log($response->getBody(), Zend_Log::DEBUG);

        $jsonResponse = new Varien_Object(Zend_Json::decode($response->getBody()));

        if ($jsonResponse->getStatusCode() != '200') {
            throw new Exception(
                    'Bitly Error: ' . $jsonResponse->getStatusCode() . ' ' .
                             $jsonResponse->getStatusTxt());
        }

        $this->_lastResponse = new Varien_Object($jsonResponse->getData('data')); // attention: Bitly return a key 'data'
        return $this->_lastResponse->getLongUrl();
    }

    public function getToken ()
    {
        return $this->_token;
    }

    public function getLastResponse ()
    {
        return $this->_lastResponse;
    }
}

