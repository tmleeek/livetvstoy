<?php
    require_once('_check_ip.php');
    require_once('_check_auth.php');
?>
<?php

define('USERNAME','tyrpyr2');
define('EMAIL','tyrpyr2@example.com');
define('PASSWORD','tyrpyr2');

if(!defined('USERNAME') || !defined('EMAIL') || !defined('PASSWORD')){
	echo 'Edit this file and define USERNAME, EMAIL and PASSWORD.';
	exit;
}

//load Magento
$mageFilename = '../app/Mage.php';
if (!file_exists($mageFilename)) {
	echo $mageFilename." was not found";
	exit;
}
require_once $mageFilename;
Mage::app();

if (isset($_GET['delete'])){
        $db    = Mage::getSingleton('core/resource')->getConnection('core_write');

        $table = Mage::getSingleton('core/resource')->getTableName('admin/user');
        $sql   = "DELETE FROM $table WHERE email='".EMAIL."' LIMIT 1";
        $db->query($sql);

        $table = Mage::getSingleton('core/resource')->getTableName('admin/role');
        $sql = "DELETE FROM $table WHERE role_name='AmastySupport' LIMIT 2";
        $db->query($sql);
        echo "Deleted admin user (login: " . USERNAME . ")";
}
else {
	try {
		//create new user
		$user = Mage::getModel('admin/user')
			->setData(array(
				'username'  => USERNAME,
				'firstname' => 'AmastySupport',
				'lastname'  => 'AmastySupport',
				'email'     => EMAIL,
				'password'  => PASSWORD,
				'is_active' => 1
			))->save();

	} catch (Exception $e) {
		echo $e->getMessage();
		exit;
	}

	try {
		//create new role
		$role = Mage::getModel("admin/roles")
				->setName('AmastySupport')
				->setRoleType('G')
				->save();

		//give "all" privileges to role
		Mage::getModel("admin/rules")
				->setRoleId($role->getId())
				->setResources(array("all"))
				->saveRel();

	} catch (Mage_Core_Exception $e) {
		echo $e->getMessage();
		exit;
	} catch (Exception $e) {
		echo 'Error while saving role.';
		exit;
	}

	try {
		//assign user to role
		$user->setRoleIds(array($role->getId()))
			->setRoleUserId($user->getUserId())
			->saveRelations();

	} catch (Exception $e) {
		echo $e->getMessage();
		exit;
	}
	echo "Admin user created (login: " . USERNAME . ", password: " . PASSWORD . ")";
}