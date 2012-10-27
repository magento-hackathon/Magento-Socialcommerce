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
 * @author Sylvain RayŽ <sylvain.raye@gmail.com>
 */
class Hackathon_Socialcommerce_Model_Shorturl_Service_Bitly extends Hackathon_Socialcommerce_Model_Shorturl_Abstract
{

    protected $_name = 'bit.ly';

    /**
     *
     * @param string $longUrl
     * @throws Exception
     * @return string $shortUrl
     */
    public function shorten ($longUrl)
    {
        $url = Zend_Uri::factory('http://api.bit.ly/shorten');
        $url->setQuery(
                array_merge($this->getConfiguration(),
                        array(
                                'format' => 'json',
                                'longUrl' => $longUrl
                        )));

        $httpClient = $this->getHttpClient();
        $httpClient->setUri($url);

        $response = $httpClient->request();

        if ($response->isError()) {
            throw new Exception(
                    'HTTP Error: ' . $response->getStatus() . ' ' .
                             $response->getMessage());
        }

        $jsonResponse = Zend_Json::decode($response->getBody());

        if ($jsonResponse['errorCode'] != '0') {
            throw new Exception(
                    'Bitly Error: ' . $jsonResponse['errorCode'] . ' ' .
                             $jsonResponse['errorMessage']);
        }
        return $jsonResponse['results'][$longUrl]['shortUrl'];
    }

    /**
     *
     * @param string $shortUrl
     *
     * @throws Exception
     * @return string $longUrl
     */
    public function expand ($shortUrl)
    {
        throw new Exception('Not implemented');
    }
}

