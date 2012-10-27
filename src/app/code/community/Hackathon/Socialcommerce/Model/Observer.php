<?php

class Hackathon_Socialcommerce_Model_Observer
{

    /**
     * Event: catalog_product_save_before
     * @param Varien_Event_Observer $observer
     */
    public function onCatalogProductSaveBefore ( Varien_Event_Observer $observer )
    {
        /** @var $product Mage_Catalog_Model_Product */
        $product = $observer->getEvent()->getProduct();

        Hackathon_Socialcommerce_Log::log("Fetched before saving product ID=" . $product->getId());

        if (null == $product->getId()) {
            // no id: product is new

            /** @var $post Hackathon_Socialcommerce_Model_Messagetype_Singlepost */
            $post = Mage::getModel('socialcommerce/messagetype_singlepost');
            $post->setText("Nochmal ein neues Produkt :)");

            /** @var $twitter Hackathon_Socialcommerce_Model_Adapter_Twitter */
            $twitter = Mage::getModel('socialcommerce/adapter_twitter');
            $twitter->sendSinglePost($post);
        }
    }
}
