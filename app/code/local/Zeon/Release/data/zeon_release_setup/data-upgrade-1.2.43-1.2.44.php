<?php
//upgrade script for creating static pages for limoges store


$staticBlock = array(
    'title' => 'Login Page info Limoges',
    'identifier' => 'login_page_info_limoges',
    'content' => '<p>"We\'ve recently updated our website.Please choose a new password to continue to your account or consider logging in with your Gmail or Facebook credentials." </p>',
    'is_active' => 1,
    'stores' => array(5)
);

Mage::getModel('cms/block')->setData($staticBlock)->save();