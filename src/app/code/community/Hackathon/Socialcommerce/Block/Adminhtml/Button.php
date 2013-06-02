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

    protected function _beforeToHtml()
    {
        $html = $this->getAfterHtml() . $this->getLayout()->createBlock('socialcommerce/adminhtml_button')
                ->setTemplate ('socialcommerce/buttons.phtml')
                ->setData(array(
                    'user_profiles' => $this->getServicesUserProfile()
                ))
                ->toHtml();

        $this->setAfterHtml($html);

    }

    public function getPostServiceUrl ()
    {
        return $this->getUrl('socialcommerce/service/post');
    }
}

