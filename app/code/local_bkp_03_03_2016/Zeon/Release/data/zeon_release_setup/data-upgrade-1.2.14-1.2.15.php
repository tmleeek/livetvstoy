<?php
/**
 * This installer script is used to create the is-party-planner product attribute.
 *
 * @category    Zeon
 * @package     Release
 * @author      Suhas Dhoke <suhas.dhoke@zeonsolutions.com>
 */

$installer = $this;
/* @var $installer Mage_Eav_Model_Entity_Setup */

$installer->startSetup();

// Attribute Group Name.
$attributeGroupName = 'CPS Attributes';

// Apply to which product types?
$applyTo = 'simple,configurable,virtual,bundle,downloadable';

// Add the Is-Party-Planner Atrribute.
$installer->addAttribute(
    Mage_Catalog_Model_Product::ENTITY,
    'is_party_planner',
    array(
        'group'                   => $attributeGroupName,
        'type'                    => 'int',
        'input'                   => 'select',
        'backend'                 => '',
        'frontend'                => '',
        'label'                   => 'Is Party Planner Product?',
        'class'                   => '',
        'source_model'            => 'eav/entity_attribute_source_boolean',
        'is_global'               => 0,
        'visible'                 => true,
        'required'                => false,
        'user_defined'            => false,
        'default'                 => 0,
        'searchable'              => false,
        'filterable'              => false,
        'comparable'              => false,
        'visible_on_front'        => true,
        'unique'                  => false,
        'apply_to'                => $applyTo,
        'is_configurable'         => false,
        'used_in_product_listing' => '0',
    )
);

// Save the source-model against the attribute.
Mage::getSingleton('eav/config')
    ->getAttribute(Mage_Catalog_Model_Product::ENTITY, 'is_party_planner')
    ->setData('source_model', 'eav/entity_attribute_source_boolean')
    ->save();

// End of installer script.
$installer->endSetup();


// Create the CMS page for Party Planner Landing Page
$landingPage = array(
                'title'         => 'Party Planner',
                'root_template' => 'one_column',
                'identifier'    => 'party-planner',
                'content'       => '<div class="party-planner-landing-page">
{{block type="zeon_bundle/partyplanner" name="partyplanner" as="partyplanner"
template="bundle/catalog/product/partyplanner_list.phtml"}}
</div>',
                'is_active'     => 1,
                'stores'        => array(0),
                'sort_order'    => 0
);

// Saves the Party Planner Landing CMS Page.
Mage::getModel('cms/page')->setData($landingPage)->save();