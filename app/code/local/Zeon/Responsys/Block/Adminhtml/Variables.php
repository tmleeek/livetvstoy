<?php
/**
 * Magento Enterprise Edition
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Magento Enterprise Edition License
 * that is bundled with this package in the file LICENSE_EE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.magentocommerce.com/license/enterprise-edition
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
 * @category    Zeon
 * @package     Zeon_Responsys
 * @copyright
 * @license
 */
class Zeon_Responsys_Block_Adminhtml_Variables extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected $_addRowButtonHtml = array();
    protected $_removeRowButtonHtml = array();

    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        $this->setElement($element);

        $html = '<table id="responsys_variable_template" border="1" style="display:none">';
        $html .= $this->_getRowTemplateHtml();
        $html .= '</table>';

        $html .= '<table id="responsys_variable_container">';
        $html .= '<tr><th>'.$this->__('Set Variables').'</th><th>'.$this->__('Campaign Variables')
            .'</th><th>&nbsp;</th></tr><br/>';

        if ($this->_getValue('email_variables')) {
            foreach ($this->_getValue('email_variables') as $i => $f) {
                if ($i) {
                    $html .= $this->_getRowTemplateHtml($i);
                }
            }
        }
        $html .= '</table>';
        $html .= $this->_getAddRowButtonHtml(
            'responsys_variable_container',
            'responsys_variable_template',
            $this->__('Add Row')
        );

        return $html;
    }

    protected function _getRowTemplateHtml($rowIndex = 0)
    {
        $options =  array(
            array('label' => '---Please Select---'),
            array('label' => 'Email_Address'),
            array('label' => 'First_Name'),
            array('label' => 'Last_Name'),
            array('label' => 'Customer_Id'),
            array('label' => 'Address'),
            array('label' => 'City'),
            array('label' => 'State'),
            array('label' => 'Country'),
            array('label' => 'Zip_Code'),
            array('label' => 'Telephone_no'),
            array('label' => 'Is_Subscribed'),
        );

        $html = '<tr>';
        $selectedEmailVariables = $this->_getSelected('email_variables/' . $rowIndex);
        $html .= '<td class="v-top" style="padding-right:10px;">';
        //$html .= '<input type="text" name="' . $this->getElement()->getName() . '[email_variables][]" value="'
        //. (!empty($selectedEmailVariables) ? $selectedEmailVariables : '') . '">';
        $html .= '<select name="' . $this->getElement()->getName() . '[email_variables][]">';
        foreach ($options as $option) {
            $html .= '<option value="'.$option['label'].'" '
                . ((!empty($selectedEmailVariables) && $option['label'] == $selectedEmailVariables) ? 'selected' : '')
                . '>' . $option['label'] . '</option>';
        }
        $html .= '</select>';
        $html .= '</td>';

        $selectedCampaignVariables = $this->_getSelected('campaign_variables/' . $rowIndex);
        $html .= '<td class="v-top" style="padding-right:10px;">';
        $html .= '<input type="text" name="' . $this->getElement()->getName() . '[campaign_variables][]" value="'
            . (!empty($selectedCampaignVariables) ? $selectedCampaignVariables : '') . '">';
        $html .= '<br><br></td><td class="v-top" style="padding-right:10px;">';
        $html .= $this->_getRemoveRowButtonHtml('tr');
        $html .= '</td></tr>';

        return $html;
    }

    protected function _getDisabled()
    {
        return $this->getElement()->getDisabled() ? ' disabled' : '';
    }

    protected function _getValue($key)
    {
        return $this->getElement()->getData('value/' . $key);
    }

    protected function _getSelected($key)
    {
        return $this->getElement()->getData('value/' . $key);
    }

    protected function _getAddRowButtonHtml($container, $template, $title='Add')
    {
        if (!isset($this->_addRowButtonHtml[$container])) {
            $this->_addRowButtonHtml[$container] = $this->getLayout()->createBlock('adminhtml/widget_button')
                    ->setType('button')
                    ->setClass('add ' . $this->_getDisabled())
                    ->setLabel($this->__($title))
                    ->setOnClick("Element.insert($('" . $container . "'), {bottom: $('" . $template . "').innerHTML})")
                    ->setDisabled($this->_getDisabled())
                    ->toHtml();
        }
        return $this->_addRowButtonHtml[$container];
    }

    protected function _getRemoveRowButtonHtml($selector = 'li', $title = 'Remove')
    {
        if (!$this->_removeRowButtonHtml) {
            $this->_removeRowButtonHtml = $this->getLayout()->createBlock('adminhtml/widget_button')
                    ->setType('button')
                    ->setClass('delete v-middle ' . $this->_getDisabled())
                    ->setLabel($this->__($title))
                    ->setOnClick("Element.remove($(this).up('" . $selector . "'))")
                    ->setDisabled($this->_getDisabled())
                    ->toHtml();
        }
        return $this->_removeRowButtonHtml;
    }
}