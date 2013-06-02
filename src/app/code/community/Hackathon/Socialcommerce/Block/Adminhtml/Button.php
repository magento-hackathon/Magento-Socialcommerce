<?php

class Hackathon_Socialcommerce_Block_Adminhtml_Button extends Mage_Adminhtml_Block_Widget_Button
{
    /**
     * Retrieve the user profile for each enabled social service
     *
     * @return array
     */
    public function getServicesUserProfile ()
    {
        $servicesUserProfile = array();
        $services = Mage::helper('socialcommerce')->getAvailableServices();

        foreach ($services as $service) {
            $model = Mage::getModel('socialcommerce/adapter_' . $service);
            if ($model) {
                $servicesUserProfile[] = $model->getUserProfile();
            }
        }

        return $servicesUserProfile;
    }

    public function getAfterHtml()
    {
        return $this->getData('after_html') . $this->getLayout()->createBlock('core/template')
            ->setTemplate ('socialcommerce/buttons.phtml')
            ->setData(array(
                'user_profiles' => $this->getServicesUserProfile()
            ))
            ->toHtml();
    }
}

