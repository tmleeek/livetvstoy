<?php
/**
 * @category    Zeon
 * @package     Zeon_Dropshipping
 * @author      Zeon Magento Team <sushil.zore@zeonsolutions.com>
 */

// Mage_Eav_Model_Entity_Setup
$installer = new Mage_Eav_Model_Entity_Setup();

$installer->startSetup();

// the attribute added will be displayed under the group/tab Netsuite Attributes in product edit page
$installer->addAttribute('catalog_product', 'bulky', array(
    	'group' => 'Netsuite Attributes',
    	'type'              => 'varchar',
        'backend'           => '',
        'frontend'          => '',
        'label'             => 'Bulky',
        'input'             => 'text',
        'class'             => '',
        'source'            => '',
        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
        'visible'           => true,
        'required'          => false,
        'user_defined'      => true,
        'default'           => '',
        'searchable'        => false,
        'filterable'        => false,
        'comparable'        => false,
        'visible_on_front'  => false,
        'unique'            => false,
        'is_configurable'   => false,
        'used_in_product_listing' => true
    )
);

$installer->endSetup();