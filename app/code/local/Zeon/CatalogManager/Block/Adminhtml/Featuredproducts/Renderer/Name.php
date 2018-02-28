<?php
/**
 * Zeon Solutions, Inc.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Zeon Solutions License
 * that is bundled with this package in the file LICENSE_ZE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.zeonsolutions.com/license/
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zeonsolutions.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to
 * newer versions in the future. If you wish to customize this extension for
 * your needs please refer to http://www.zeonsolutions.com for more information.
 *
 * @category    Zeon
 * @package     Zeon_CatalogManager
 * @author      Suhas Dhoke <suhas.dhoke@zeonsolutions.com>
 * @copyright   Copyright (c) 2014 Zeon Solutions, Inc. All Rights Reserved.
 *              (http://www.zeonsolutions.com)
 * @license     http://www.zeonsolutions.com/license/
 */
class Zeon_CatalogManager_Block_Adminhtml_Featuredproducts_Renderer_Name
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

    protected $_values;

    /**
     * Renders grid column
     *
     * @param   Varien_Object $row
     * @return  string
     */
    public function render(Varien_Object $row)
    {
        $actionName = $this->getRequest()->getActionName();

        if ($actionName == 'exportCsv' || $actionName == 'exportXml') {
            return $row->getName();
        }

        $href = $this->getUrl(
            '*/catalog_product/edit',
            array(
                'store' => $this->getRequest()->getParam('store'),
                'id' => $row->getId()
            )
        );

        $html = '<a href="' . $href . '">' . $row->getName() . '</a>';

        return $html;
    }
}
