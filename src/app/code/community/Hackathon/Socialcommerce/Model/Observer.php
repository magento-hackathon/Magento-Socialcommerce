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
 * Observer to trigger events when new product are saved or order submitted
 *
 * @category Hackathon
 * @package Hackathon_Socialcommerce
 * @author Mike
 */
class Hackathon_Socialcommerce_Model_Observer extends Hackathon_Socialcommerce_Model_Abstract
{

    /**
     * Event: catalog_product_save_after
     *
     * @param Varien_Event_Observer $observer
     */
    public function onCatalogProductSaveAfter(Varien_Event_Observer $observer)
    {
        /* @var $product Mage_Catalog_Model_Product */
        $product = $observer->getEvent()->getProduct();
        
        // Product is not new
        if ($product->getId() == $product->getOrigData('entity_id') || $product->getStatus() == Mage_Catalog_Model_Product_Status::STATUS_DISABLED) {
            return;
        }
        
        //Hackathon_Socialcommerce_Log::log('Observer On Product Save After.');
        
        /* @var $post Hackathon_Socialcommerce_Model_Messagetype_Singlepost */
        $post = Mage::getModel('socialcommerce/messagetype_singlepost');
        $post->setText($this->_getConfig()
            ->getMessageNewProduct());
        $post->importProduct($product);
        
        //Hackathon_Socialcommerce_Log::log('Post Text:' . $post->getText());
        
        /* @var $twitter Hackathon_Socialcommerce_Model_Adapter_Twitter */
        $twitter = Mage::getModel('socialcommerce/adapter_twitter');
        $twitter->sendSinglePost($post);
    }
     
    /**
     * Event: sales_convert_quote_to_order
     */
    public function onSalesConvertQuoteToOrder(Varien_Event_Observer $observer)
    {
        /** @var $post Hackathon_Socialcommerce_Model_Messagetype_Singlepost */
        $post = Mage::getModel('socialcommerce/messagetype_singlepost');
        $post->setText($this->_getConfig()
            ->getMessageNewOrder());
        
        /** @var $twitter Hackathon_Socialcommerce_Model_Adapter_Twitter */
        $twitter = Mage::getModel('socialcommerce/adapter_twitter');
        $twitter->sendSinglePost($post);
    }

    /**
     * catalog_category_save_after
     */
    public function onCatalogCategorySaveAfter(Varien_Event_Observer $observer)
    {
        /** @var $category Mage_Catalog_Model_Category */
        $category = $observer->getCategory();
        
        if ($category->getId() == $category->getOrigData('entity_id')) {
            return false;
        }
        
        /** @var $post Hackathon_Socialcommerce_Model_Messagetype_Singlepost */
        $post = Mage::getModel('socialcommerce/messagetype_singlepost');
        
        $post->setText($this->_getConfig()
            ->getMessageNewCategory());
        $post->setLink($category->getCategoryIdUrl());
        $post->setPicture($category->getImageUrl());
        
        /** @var $twitter Hackathon_Socialcommerce_Model_Adapter_Twitter */
        $twitter = Mage::getModel('socialcommerce/adapter_twitter');
        $twitter->sendSinglePost($post);
    }

    /**
     * Event:
     * - core_block_abstract_prepare_layout_after
     *
     * @param Varien_Event_Observer $observer
     */
    public function insertPostSocialButton (Varien_Event_Observer $observer)
    {
        /* @var $block Mage_Adminhtml_Block_Catalog_Product_Edit */
        $block = $observer->getEvent()->getBlock();

        if ($block->getId() == 'product_edit' && $block->getChild('save_button')) {

            $child = $block->getChild('save_button');
            $afterHtml = $child->getAfterHtml();

            $socialHtml = $child->getLayout()->createBlock('socialcommerce/adminhtml_button', 'post_social')
                ->setData(array(
                    'label'     => Mage::helper('socialcommerce')->__('Post to Social'),
                    'onclick'   => '',
                    'class' => 'save'
                ))
                ->toHtml();

            $socialButtonsHtml = '<div id="container-socialbuttons">' . $socialHtml . '</div>';

            $child->setAfterHtml($afterHtml . $socialButtonsHtml);
        }
    }
}
