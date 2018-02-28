<?php

/**
 * 
 * @category   CRM4Ecommerce
 * @package    CRM4Ecommerce_AdminStartupPage
 * @author     Philip Nguyen <philip@crm4ecommerce.com>
 * @link       http://crm4ecommerce.com
 */
class CRM4Ecommerce_AdminStartupPage_Model_Rewrite_Adminhtml_System_Config_Source_Admin_Page extends Mage_Adminhtml_Model_System_Config_Source_Admin_Page {

    private $user = null;

    public function toOptionArray($user = null) {
        $this->user = $user;
        return parent::toOptionArray();
    }

    protected function _buildMenuArray(Varien_Simplexml_Element $parent = null, $path = '', $level = 0) {
        if (is_null($parent)) {
            $parent = Mage::getSingleton('admin/config')->getAdminhtmlConfig()->getNode('menu');
        }

        $parentArr = array();
        $sortOrder = 0;
        foreach ($parent->children() as $childName => $child) {
            if ((1 == $child->disabled)
                    || ($child->depends && !$this->_checkDepends($child->depends))
            ) {
                continue;
            }
            
            if (is_null($this->user) || !$this->user) {
                if (!Mage::getSingleton('admin/session')->isAllowed($path . $childName)) {
                    continue;
                }
            } else if ($this->user->getId() > 0) {
                $acl = Mage::getResourceModel('admin/acl')->loadAcl();
                $resource = $path . $childName;
                if (!preg_match('/^admin/', $resource)) {
                    $resource = 'admin/' . $resource;
                }
                try {
                    if (!$acl->isAllowed($this->user->getAclRole(), $resource)) {
                        continue;
                    }
                } catch (Exception $e) {
                    
                }
            }

            $menuArr = array();
            $menuArr['label'] = $this->_getHelperValue($child);

            $menuArr['sort_order'] = $child->sort_order ? (int) $child->sort_order : $sortOrder;

            if ($child->action) {
                $menuArr['url'] = (string) $child->action;
            } else {
                $menuArr['url'] = '';
            }

            $menuArr['level'] = $level;
            $menuArr['path'] = $path . $childName;

            if ($child->children) {
                $menuArr['children'] = $this->_buildMenuArray($child->children, $path . $childName . '/', $level + 1);
            }
            $parentArr[$childName] = $menuArr;

            $sortOrder++;
        }

        uasort($parentArr, array($this, '_sortMenu'));

        while (list($key, $value) = each($parentArr)) {
            $last = $key;
        }
        if (isset($last)) {
            $parentArr[$last]['last'] = true;
        }

        return $parentArr;
    }

}