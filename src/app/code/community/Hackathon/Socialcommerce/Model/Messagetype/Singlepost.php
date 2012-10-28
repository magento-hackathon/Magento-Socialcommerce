<?php

/**
 * Defines a single post
 */
class Hackathon_Socialcommerce_Model_Messagetype_Singlepost extends Hackathon_Socialcommerce_Model_Abstract
{

    /**
     * @var string
     */
    protected $_text;
    /**
     * @var string
     */
    protected $_picture;

    /**
     * @var string
     */
    protected $_link;

    /**
     * @var string
     */
    protected $_caption;

    /**
     * Add a picture
     *
     * @param string $picture
     */
    public function setPicture ( $picture )
    {
        $this->_picture = $picture;
    }

    /**
     * @return string
     */
    public function getPicture ()
    {
        return $this->_picture;
    }

    /**
     * @param string $text
     */
    public function setText ( $text )
    {
        $this->_text = $text;
    }

    /**
     * @return string
     */
    public function getText ()
    {
        return $this->_text;
    }

    /**
     * @param string $caption
     */
    public function setCaption ( $caption )
    {
        $this->_caption = $caption;
    }

    /**
     * @return string
     */
    public function getCaption ()
    {
        return $this->_caption;
    }

    /**
     * @param string $link
     */
    public function setLink ( $link )
    {
        $this->_link = $link;
    }

    /**
     * @return string
     */
    public function getLink ()
    {
        return $this->_link;
    }

    /**
     * Import a Magento Product
     *
     * @param Mage_Catalog_Model_Product $product
     */
    public function importProduct ( Mage_Catalog_Model_Product $product )
    {
        $name = $product->getName();
        $link =  $this->_getConfig()->shorten($product->getProductUrl());

        $map = array(
            ":name" => $name,
            ":link" => $link,
        );

        $text = strtr($this->getText(), $map);

        $this->setText($text);
        $this->setCaption($name);
        $this->setLink($link);
    }
}
