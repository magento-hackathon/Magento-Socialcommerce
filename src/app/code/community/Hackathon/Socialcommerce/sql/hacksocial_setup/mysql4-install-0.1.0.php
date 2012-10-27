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

$installer = $this;

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer->startSetup();

$installer->run("
DROP TABLE IF EXISTS `{$installer->getTable('socialcommerce/shorturl')}`;
CREATE TABLE `{$installer->getTable('socialcommerce/shorturl')}` (
    `shorturl_id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `shorturl` VARCHAR(255) NOT NULL DEFAULT '',
    `longurl` VARCHAR(255) NOT NULL DEFAULT '',
    `service` VARCHAR(32) NOT NULL DEFAULT '0',
    `create_time` DATETIME,
    PRIMARY KEY (`shorturl_id`),
    UNIQUE (`longurl`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
");

$installer->endSetup();
