<?php

class Hackathon_Socialcommerce_Model_Messagetype_SinglePost
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


}
