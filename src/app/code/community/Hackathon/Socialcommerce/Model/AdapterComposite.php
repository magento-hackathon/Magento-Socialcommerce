<?php

class Hackathon_Socialcommerce_Model_AdapterComposite
{

    protected $_childs = array();

    function add ( Hackathon_Socialcommerce_Model_Adapter_Interface $object )
    {
        $hash                 = spl_object_hash($object);
        $this->_childs[$hash] = $object;
    }

    function sendSinglePost(Hackathon_Socialcommerce_Model_Messagetype_Singlepost $post) {
        /** @var $child Hackathon_Socialcommerce_Model_Adapter_Interface */
        foreach ($this->_childs as $child) {
            $child->sendSinglePost($post);
        }
    }
}
