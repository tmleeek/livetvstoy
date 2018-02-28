<?php
$installer = $this;

$installer->startSetup();

//alter table newsletter subescribers
$installer->run(
    "ALTER TABLE ".$this->getTable('newsletter/subscriber')."
    ADD responsys_rrid varchar(225);"
);

$setup = Mage::getModel('customer/entity_setup', 'core_setup');
$setup->addAttribute(
    'customer',
    'responsys_rrid',
    array(
        'type' => 'int',
        'input' => 'text',
        'label' => 'Responsys RRID',
        'global' => 1,
        'visible' => 1,
        'required' => 0,
        'user_defined' => 1,
        'default' => '0',
        'visible_on_front' => 0,
    )
);

$customer = Mage::getModel('customer/customer');
$attrSetId = $customer->getResource()->getEntityType()->getDefaultAttributeSetId();
$setup->addAttributeToSet('customer', $attrSetId, 'General', 'responsys_rrid');

$installer->endSetup();