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
 * Social Commerce Data Helper
 *
 * @category Hackathon
 * @package Hackathon_Socialcommerce
 * @author Sylvain Ray� <sylvain.raye@gmail.com>
 */
class Hackathon_Socialcommerce_Helper_Data extends Mage_Core_Helper_Abstract
{

    public function getTwitterConsumerKey ()
    {
        return Mage::getStoreConfig('socialcommerce/twitter/consumer_key');
    }

    public function getTwitterConsumerSecret ()
    {
        return Mage::helper('core')->decrypt(
                Mage::getStoreConfig('socialcommerce/twitter/consumer_secret'));
    }

    public function getTwitterAuthToken ()
    {
        return Mage::getStoreConfig('socialcommerce/twitter/auth_token');
    }

    public function getTwitterTokenSecret ()
    {
        return Mage::helper('core')->decrypt(
                Mage::getStoreConfig('socialcommerce/twitter/token_secret'));
    }

    public function getFacebookAppId() {
        return Mage::getStoreConfig('socialcommerce/facebook/app_id');
    }

    public function getFacebookSecret() {
        return Mage::helper('core')->decrypt(
            Mage::getStoreConfig('socialcommerce/facebook/secret'));
    }

    public function getFacebookUserId() {
        return Mage::getStoreConfig('socialcommerce/facebook/user_id');
    }

    public function getMessageNewOrder() {
        return Mage::getStoreConfig('socialcommerce/messages/new_order');
    }

    public function getMessageNewCategory() {
        return Mage::getStoreConfig('socialcommerce/messages/new_category');
    }

    public function getMessageNewProduct() {
        return Mage::getStoreConfig('socialcommerce/messages/new_product');
    }

    public function isTwitterEnabled() {
        return (1 == Mage::getStoreConfig('socialcommerce/twitter/active'));
    }

    public function getDeliciousUsername() {
        return Mage::getStoreConfig('socialcommerce/delicious/username');
    }

    public function getDeliciousPassword() {
        return Mage::getStoreConfig('socialcommerce/delicious/password');
    }

    public function isDeliciousEnabled() {
        return (1 == Mage::getStoreConfig('socialcommerce/delicious/active'));
    }

    /**
     * Get a short url string from a long one thanks to external service
     *
     * @param $longUrl string
     * @return mixed
     */
    public function shorten ($longUrl)
    {
        /* @var $shortUrlModel Hackathon_Socialcommerce_Model_Shorturl */
        $shortUrlModel = Mage::getModel('socialcommerce/shorturl');
        $shortUrlModel->loadByLongUrl($longUrl);
        if (! $shortUrlModel->getId()) {

            $service = $this->getShorturlService();
            try {
                $shortUrlModel->setShortUrl($service->shorten($longUrl))
                    ->setLongUrl($longUrl)
                    ->setService($service->getName())
                    ->save();
            } catch (Exception $e) {
                Mage::logException($e);
                $shortUrlModel->setShortUrl($longUrl);
            }
        }
        return $shortUrlModel->getShortUrl();
    }

    /**
     * Get a short url string from a long one thanks to external service
     *
     * @param $longUrl string
     * @return mixed
     */
    public function expand ($shortUrl)
    {
        /* @var $shortUrlModel Hackathon_Socialcommerce_Model_Shorturl */
        $shortUrlModel = Mage::getModel('socialcommerce/shorturl');
        $shortUrlModel->loadByShortUrl($shortUrl);
        if (! $shortUrlModel->getId()) {

            $service = $this->getShorturlService();
            try {
                $shortUrlModel->setLongUrl($service->expand($shortUrl))
                    ->setShortUrl($shortUrl)
                    ->setService($service->getName())
                    ->save();
            } catch (Exception $e) {
                Mage::logException($e);
                $shortUrlModel->setLongUrl($shortUrl);
            }
        }
        return $shortUrlModel->getLongUrl();
    }



    /**
     * Parameter $service is tring of the service name. e.g. 'bitly'
     * Parameter $configuration is an array of configuration available for the HttpClient Class and for the service
     *
     * @param $service string
     * @param $configuration array
     * @return Hackathon_Socialcommerce_Model_Shorturl_Service_Abstract
     */
    public function getShorturlService ($service = null, $configuration = array())
    {
        return Mage::getModel('socialcommerce/shorturl_factory')->create($service, $configuration);
    }
}