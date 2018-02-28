<?php

/**
 * The array params for config module
 *
 * @category   CRM4Ecommerce
 * @package    CRM4Ecommerce_CRMCore
 * @author     Philip Nguyen <philip@crm4ecommerce.com>
 * @link       http://crm4ecommerce.com
 */
class CRM4Ecommerce_CRMCore_Model_Option_GetListUsers extends CRM4Ecommerce_CRMCore_Model_Option_Abstract {

    public function toOptionArray() {
        $roles = Mage::getModel('admin/roles')->getCollection()->setOrder('role_name', 'asc');
        $_roles = array();
        foreach ($roles as $role) {
            $_role = Mage::getModel('admin/roles')->load($role->getId());
            if ($_role instanceof Mage_Admin_Model_Roles) {
                $users = $_role->getRoleUsers();
                $_users = array();
                foreach ($users as $user_id) {
                    $user = Mage::getModel('admin/user')->load($user_id);
                    $_users[] = array(
                        'value' => $user->getId(),
                        'label' => $user->getUsername() . ' (' . $user->getName() . ')'
                    );
                }
                if (count($_users)) {
                    $_roles[] = array(
                        'value' => $_users,
                        'label' => $role->getRoleName()
                    );
                }
            }
        }
        return $_roles;
    }

}