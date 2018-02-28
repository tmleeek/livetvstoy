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
class Zeon_CatalogManager_IndexController
    extends Mage_Core_Controller_Front_Action
{
    /**
     * Check settings set in System->Configuration and apply them
     * for featured products page
     */
    public function indexAction()
    {
        // Get the helper and config settings of featured products in vars.
        $helper         = Mage::helper('zeon_catalogmanager');
        $configSettings = $helper->getConfigDetails('featured_products');

        // If the feature is disabled then redirect user to home page.
        if (!$configSettings['active']) {
            $this->_redirect('/');
            return;
        }

        // Set the template layout.
        $template = Mage::getConfig()->getNode(
            'global/page/layouts/' . $configSettings['layout'] . '/template'
        );
        $this->loadLayout();

        // Get the meta title, description and keywords.
        $title       = $this->__($configSettings['meta_title']);
        $description = $this->__($configSettings['meta_description']);
        $keyword     = $this->__($configSettings['meta_keywords']);

        $this->getLayout()->getBlock('root')->setTemplate($template);
        $this->getLayout()->getBlock('head')->setTitle($title);
        $this->getLayout()->getBlock('head')->setDescription($description);
        $this->getLayout()->getBlock('head')->setKeywords($keyword);

        // Set the breadcrumb.
        $breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs');
        $breadcrumbsBlock->addCrumb(
            'featured_products',
            array(
            'label' => $helper->__($configSettings['heading']),
            'title' => $helper->__($configSettings['heading']),
            )
        );

        // Render the layout.
        $this->renderLayout();
    }

    /**
     * Check settings set in System->Configuration and apply them
     * for best seller products page
     */
    public function bestAction()
    {
        // Get the helper and config settings of featured products in vars.
        $helper         = Mage::helper('zeon_catalogmanager');
        $configSettings = $helper->getConfigDetails('best_products');

        // If the feature is disabled then redirect user to home page.
        if (!$configSettings['active']) {
            $this->_redirect('/');
            return;
        }

        // Set the template layout.
        $template = Mage::getConfig()->getNode(
            'global/page/layouts/' . $configSettings['layout'] . '/template'
        );
        $this->loadLayout();

        // Get the meta title, description and keywords.
        $title       = $this->__($configSettings['meta_title']);
        $description = $this->__($configSettings['meta_description']);
        $keyword     = $this->__($configSettings['meta_keywords']);

        $this->getLayout()->getBlock('root')->setTemplate($template);
        $this->getLayout()->getBlock('head')->setTitle($title);
        $this->getLayout()->getBlock('head')->setDescription($description);
        $this->getLayout()->getBlock('head')->setKeywords($keyword);

        // Set the breadcrumb.
        $breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs');
        $breadcrumbsBlock->addCrumb(
            'best_sellers',
            array(
            'label' => $helper->__($configSettings['heading']),
            'title' => $helper->__($configSettings['heading']),
            )
        );

        // Render the layout.
        $this->renderLayout();
    }

    /**
     * Check settings set in System->Configuration and apply them
     * for home option 1 products page
     */
    public function homeOption1Action()
    {
        // Get the helper and config settings of featured products in vars.
        $helper         = Mage::helper('zeon_catalogmanager');
        $configSettings = $helper->getConfigDetails('homepage_products_option1');

        // If the feature is disabled then redirect user to home page.
        if (!$configSettings['active']) {
            $this->_redirect('/');
            return;
        }

        // Set the template layout.
        $template = Mage::getConfig()->getNode(
            'global/page/layouts/' . $configSettings['layout'] . '/template'
        );
        $this->loadLayout();

        // Get the meta title, description and keywords.
        $title       = $this->__($configSettings['meta_title']);
        $description = $this->__($configSettings['meta_description']);
        $keyword     = $this->__($configSettings['meta_keywords']);

        $this->getLayout()->getBlock('root')->setTemplate($template);
        $this->getLayout()->getBlock('head')->setTitle($title);
        $this->getLayout()->getBlock('head')->setDescription($description);
        $this->getLayout()->getBlock('head')->setKeywords($keyword);

        // Set the breadcrumb.
        $breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs');
        $breadcrumbsBlock->addCrumb(
            'best_sellers',
            array(
                'label' => $helper->__($configSettings['heading']),
                'title' => $helper->__($configSettings['heading']),
            )
        );

        // Render the layout.
        $this->renderLayout();
    }

    /**
     * Check settings set in System->Configuration and apply them
     * for home option 2 products page
     */
    public function homeOption2Action()
    {
        // Get the helper and config settings of featured products in vars.
        $helper         = Mage::helper('zeon_catalogmanager');
        $configSettings = $helper->getConfigDetails('homepage_products_option2');

        // If the feature is disabled then redirect user to home page.
        if (!$configSettings['active']) {
            $this->_redirect('/');
            return;
        }

        // Set the template layout.
        $template = Mage::getConfig()->getNode(
            'global/page/layouts/' . $configSettings['layout'] . '/template'
        );
        $this->loadLayout();

        // Get the meta title, description and keywords.
        $title       = $this->__($configSettings['meta_title']);
        $description = $this->__($configSettings['meta_description']);
        $keyword     = $this->__($configSettings['meta_keywords']);

        $this->getLayout()->getBlock('root')->setTemplate($template);
        $this->getLayout()->getBlock('head')->setTitle($title);
        $this->getLayout()->getBlock('head')->setDescription($description);
        $this->getLayout()->getBlock('head')->setKeywords($keyword);

        // Set the breadcrumb.
        $breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs');
        $breadcrumbsBlock->addCrumb(
            'best_sellers',
            array(
                'label' => $helper->__($configSettings['heading']),
                'title' => $helper->__($configSettings['heading']),
            )
        );

        // Render the layout.
        $this->renderLayout();
    }

    /**
     * Check settings set in System->Configuration and apply them
     * for home option 3 products page
     */
    public function homeOption3Action()
    {
        // Get the helper and config settings of featured products in vars.
        $helper         = Mage::helper('zeon_catalogmanager');
        $configSettings = $helper->getConfigDetails('homepage_products_option3');

        // If the feature is disabled then redirect user to home page.
        if (!$configSettings['active']) {
            $this->_redirect('/');
            return;
        }

        // Set the template layout.
        $template = Mage::getConfig()->getNode(
            'global/page/layouts/' . $configSettings['layout'] . '/template'
        );
        $this->loadLayout();

        // Get the meta title, description and keywords.
        $title       = $this->__($configSettings['meta_title']);
        $description = $this->__($configSettings['meta_description']);
        $keyword     = $this->__($configSettings['meta_keywords']);

        $this->getLayout()->getBlock('root')->setTemplate($template);
        $this->getLayout()->getBlock('head')->setTitle($title);
        $this->getLayout()->getBlock('head')->setDescription($description);
        $this->getLayout()->getBlock('head')->setKeywords($keyword);

        // Set the breadcrumb.
        $breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs');
        $breadcrumbsBlock->addCrumb(
            'best_sellers',
            array(
                'label' => $helper->__($configSettings['heading']),
                'title' => $helper->__($configSettings['heading']),
            )
        );

        // Render the layout.
        $this->renderLayout();
    }
    /**
     * Check settings set in System->Configuration and apply them
     * for most popular products page
     */
    public function popularAction()
    {
        // Get the helper and config settings of featured products in vars.
        $helper         = Mage::helper('zeon_catalogmanager');
        $configSettings = $helper->getConfigDetails('popular_products');

        // If the feature is disabled then redirect user to home page.
        if (!$configSettings['active']) {
            $this->_redirect('/');
            return;
        }

        // Set the template layout.
        $template = Mage::getConfig()->getNode(
            'global/page/layouts/' . $configSettings['layout'] . '/template'
        );
        $this->loadLayout();

        // Get the meta title, description and keywords.
        $title       = $this->__($configSettings['meta_title']);
        $description = $this->__($configSettings['meta_description']);
        $keyword     = $this->__($configSettings['meta_keywords']);

        $this->getLayout()->getBlock('root')->setTemplate($template);
        $this->getLayout()->getBlock('head')->setTitle($title);
        $this->getLayout()->getBlock('head')->setDescription($description);
        $this->getLayout()->getBlock('head')->setKeywords($keyword);

        // Set the breadcrumb.
        $breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs');
        $breadcrumbsBlock->addCrumb(
            'most_popular',
            array(
            'label' => $helper->__($configSettings['heading']),
            'title' => $helper->__($configSettings['heading']),
            )
        );

        // Render the layout.
        $this->renderLayout();
    }

    /**
     * This method used to display the list of all the New Arrivals.
     *
     * The "New Arrivals" are based on product's New-From & New-To attributes.
     *
     * @return void
     */
    public function newarrivalsAction()
    {
        // Load and render the layout.
        $this->loadLayout();

        $helper = Mage::helper('zeon_catalogmanager');

        // Set the breadcrumb.
        $breadcrumbsBlock = $this->getLayout()->getBlock('breadcrumbs');
        $breadcrumbsBlock->addCrumb(
            'new_arrivals',
            array(
            'label' => $helper->__('New Arrivals'),
            'title' => $helper->__('New Arrivals'),
            )
        );

        $this->renderLayout();
    }

    /**
     * best seller cron check function
     */
    public function bestcronAction()
    {
        Mage::getModel('zeon_catalogmanager/bestseller')->setBestsellers();
    }
}