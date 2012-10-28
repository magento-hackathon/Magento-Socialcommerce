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
 * Source file for services
 *
 * @category Hackathon
 * @package Hackathon_Socialcommerce
 * @author Sylvain RayŽ <sylvain.raye@gmail.com>
 */
class Hackathon_Socialcommerce_Model_System_Config_Source_Bitly_Domain
{

    protected $_options;

    public function toOptionArray ()
    {
        if (! $this->_options) {
            $this->_options[] = array(
                    'label' => 'bit.ly',
                    'value' => 'bit.ly'
            );
            $this->_options[] = array(
                    'label' => 'j.mp',
                    'value' => 'j.mp'
            );
            $this->_options[] = array(
                    'label' => 'bit.com',
                    'value' => 'bit.com'
            );
        }
        return $this->_options;
    }
}
