<?php

class Hackathon_Socialcommerce_Model_Observer extends Hackathon_Socialcommerce_Model_Abstract
{

    /**
     * Event: catalog_product_save_before
     * @param Varien_Event_Observer $observer
     */
    public function onCatalogProductSaveBefore ( Varien_Event_Observer $observer )
    {
        /** @var $product Mage_Catalog_Model_Product */
        $product = $observer->getEvent()->getProduct();

        if (null != $product->getId()) {
            return false;
        }
        // no id: product is new

        /** @var $post Hackathon_Socialcommerce_Model_Messagetype_Singlepost */
        $post = Mage::getModel('socialcommerce/messagetype_singlepost');
        $post->setText($this->_getConfig()->getMessageNewProduct());
        $post->importProduct($product);

        /** @var $twitter Hackathon_Socialcommerce_Model_Adapter_Twitter */
        $twitter = Mage::getModel('socialcommerce/adapter_twitter');
        $twitter->sendSinglePost($post);
    }


    /**
     * Event: sales_convert_quote_to_order
     */
    public function onSalesConvertQuoteToOrder(Varien_Event_Observer $observer ) {
        /** @var $post Hackathon_Socialcommerce_Model_Messagetype_Singlepost */
        $post = Mage::getModel('socialcommerce/messagetype_singlepost');
        $post->setText($this->_getConfig()->getMessageNewOrder());

        /** @var $twitter Hackathon_Socialcommerce_Model_Adapter_Twitter */
        $twitter = Mage::getModel('socialcommerce/adapter_twitter');
        $twitter->sendSinglePost($post);
    }
}
