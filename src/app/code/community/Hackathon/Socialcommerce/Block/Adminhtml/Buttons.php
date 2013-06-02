<?php

class Hackathon_Socialcommerce_Block_Adminhtml_Buttons extends Mage_Adminhtml_Block_Widget_Button
{
    public function getButtons ()
    {
        $services = Mage::helper('socialcommerce')->getAvailableServices();

        foreach ($services as $service) {
            $model = Mage::getModel('socialcommerce/adapter_' . $service);
            if ($model) {
                $servicesUserProfile[] = $model->getUserProfile();
            }

        }

    }
}

