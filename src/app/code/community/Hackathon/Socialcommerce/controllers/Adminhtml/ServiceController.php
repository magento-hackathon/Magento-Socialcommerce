<?php

class Hackathon_Socialcommerce_Adminhtml_ServiceController extends Mage_Core_Controller_Front_Action
{
    public function postAction ()
    {
        $sendServices = $this->getRequest()->getParam('services');
        $availableServices = Mage::helper('socialcommerce')->getAvailableServices();

        try {
            foreach($availableServices as $service) {
                $post = Mage::getModel('socialcommerce/messagetype_singlepost');
                $post->setText(Mage::helper('socialcommerce')->getMessageNewOrder());

                if (in_array($service, $sendServices)) {
                    /** @var $adapter Hackathon_Socialcommerce_Model_Adapter_Interface */
                    $adapter = Mage::getModel('socialcommerce/adapter_' . $service);
                    $adapter->sendSinglePost($post);
                }
            }
        } catch (Exception $e) {
            $error = $e->getMessage();

        }

        if (empty($error)) {
            $response = array('success' => true);
        } else {
            $response = array('error' => true, 'message' => $this->__('An error occured.' . $error));
        }

        $this->getResponse()->setHeader('Content-Type', 'application/json');
        $this->getResponse()->setBody(json_encode($response));
    }
}