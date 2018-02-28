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
 * Do not edit or add to this file if you wish to upgrade this extension
 * to newer versions in the future. If you wish to customize this extension for
 * your needs please refer to http://www.zeonsolutions.com for more information.
 *
 * @category    Zeon
 * @package     Zeon_Attributemapping
 * @copyright   Copyright (c) 2012 Zeon Solutions, Inc. All Rights Reserved.
 *    (http://www.zeonsolutions.com)
 * @license     http://www.zeonsolutions.com/license/
 */


class Zeon_Attributemapping_Model_Menucron extends Mage_Core_Model_Abstract
{
    protected $_menuBlocks = array();
    protected $_menuStaticBlocks = '';
    protected $_logfile = 'attribute_mapping.log';


    /**
     *
     * cron to set site menu data
     */
    public function setSiteTopMenu()
    {
        $this->_cleanUrlData();
        $this->_menuUpdateByStores();
    }

    protected function _cleanUrlData()
    {
        $resource = Mage::getSingleton('core/resource');
        $write = $resource->getConnection('core_write');
        $urlRewrite = $resource
                ->getTableName('enterprise_urlrewrite/url_rewrite');
        $write->query("DELETE FROM `$urlRewrite` WHERE request_path = '';");
        return;
    }

    /**
     *
     * set attribute url by stores
     */
    protected function _menuUpdateByStores()
    {
        $stores = Mage::getModel('core/store')->getCollection()
            ->addFieldToFilter('store_id', array('gt'=>0))->getAllIds();

        //set menu blocks
        $this->_menuBlocks = Mage::helper('zeon_attributemapping')
            ->getBlockNames();
        //set menu identifier
        $this->_menuStaticBlocks = Mage::helper('zeon_attributemapping')
            ->getConfigDetails('menublock_identifier');

        foreach ($stores as $store) {
            Mage::app()->setCurrentStore($store);
            // check for enable cron
            $enableCron = Mage::helper('zeon_attributemapping')
                ->getConfigDetails('active');
            if (!$enableCron) {
                continue;
            }
            $this->_setStoreMenu($store);
        }
        return;
    }

    /**
     *
     * set data in proper store cms block
     * @param int $store
     */
    protected function _setStoreMenu($store)
    {
        try{
            $menuCmsBlock = Mage::getModel('cms/block')
                         ->setStoreId($store)
                         ->load($this->_menuStaticBlocks);
            if ($menuCmsBlock && $menuCmsBlock->getId()) {
                $content = $this->_setStoreTopMenuData($store);
                $menuCmsBlock->setContent($content);
                $menuCmsBlock->save();
            } else {
                $menuCmsBlock = Mage::getModel('cms/block')
                    ->setTitle('Top Menu Block')
                    ->setIdentifier($this->_menuStaticBlocks)
                    ->setStores(array($store))
                    ->setIsActive(1)
                    ->setContent('Menu content');
                $menuCmsBlock->save();
            }
        } catch (Exception $e) {

            Mage::log(
                'Menu setStoreMenu : '. implode(', ', $urlData)
                .' message: '. $e->getMessage(),
                null,
                $this->_logfile
            );

        }
        return;
    }

    /**
     *
     * concatinate all category, attribute data in one menu
     * @param unknown_type $store
     */
    protected function _setStoreTopMenuData($store)
    {
        $html = '';
        try{

            $frontStatic = Mage::helper('zeon_attributemapping')
                ->getConfigDetails('static_blockinmenu');
            if ($frontStatic) {
                $html .= '<li class="first">
                    {{block type="cms/block" block_id="'.$frontStatic.'"}}
                    </li>';
            }

            $setCatOnly = Mage::helper('zeon_attributemapping')
                ->getConfigDetails('set_category_menu');
            if ($setCatOnly) {
                //set only category
                $catIds = Mage::helper('zeon_attributemapping')
                    ->getConfigDetails('get_categories');
                $categoryData = $this->_getCategoryMenuCollection($store, $catIds, 0, 'position');

                $menuNo = 1;
                foreach ($categoryData as $id => $catData) {
                    $catData['id'] = $id;
                    $catData['options'] = $catData['child'] ;
                    $catData['code'] = 'category_menu_'.$id;
                    $catData['menutype'] = 'category';
                    $catData['menuno'] = $menuNo++;
                    $staticData = $this->_getStaticBlockDynamic($catData, $store);

                    if (!empty($catData)) {
                        $html .= $this->_setMenuHtml(
                            $store,
                            $catData,
                            $staticData,
                            'list',
                            $catData['name']
                        );
                    }

                }

            } else {
                //set category for menu
                $catIds = Mage::helper('zeon_attributemapping')
                    ->getConfigDetails('get_categories');
                $categoryData = $this->_getCategoryMenuCollection($store, $catIds);

                if (!empty($categoryData)) {
                    $html .= $this->_setMenuHtml(
                        $store,
                        $categoryData,
                        $this->_getMenuAdBlockContent($store, 'category'),
                        'category',
                        'Category',
                        'first'
                    );
                }

                //set Attributes menu
                $attributeData = $this->_getAttributeData($store);
                if ($attributeData) {
                    foreach ($attributeData as $attributeList) {
                        $manuType = 'list';
                        if ($attributeList['code'] == 'character') {
                            $manuType = 'character';
                        }

                        $html .= $this->_setMenuHtml(
                            $store,
                            $attributeList,
                            $this->_getMenuAdBlockContent(
                                $store,
                                $attributeList['code']
                            ),
                            $manuType,
                            $attributeList['name']
                        );
                    }
                }

                //set Personalized gift data
                $catId = Mage::helper('zeon_attributemapping')
                    ->getConfigDetails('personalized_category');
                $categoryData = $this->_getSingleCategoryMenuCollection(
                    $store,
                    $catId
                );
                if (!empty($categoryData)) {
                    $html .= $this->_setMenuHtml(
                        $store,
                        $categoryData,
                        $this->_getMenuAdBlockContent(
                            $store,
                            $categoryData['code']
                        ),
                        'list',
                        $categoryData['name']
                    );
                }
            }

            //set clearence link
            $linkName = Mage::helper('zeon_attributemapping')
                ->getConfigDetails('clearence_text');
            $linkUrl = Mage::helper('zeon_attributemapping')
                ->getConfigDetails('clearence_url');
            if ($linkName != "" && $linkUrl != "") {
                $html .= '<li class="level0 nav-1 last level-top parent">';
                $html .= '<a class="level-top" href="{{store url=\''
                    .$linkUrl.'\'}}" title="'.$linkName.'"'
                    . ' setshow="z"><span>'
                    .$linkName.'</span></a></li>';
            }

            //set url of store
            $html = @str_replace(Mage::getBaseUrl(), '{{store url}}', $html);

            Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);


        } catch (Exception $e) {
            Mage::log(
                'Menu _setStoreTopMenuData : '. implode(', ', $urlData)
                .' message: '. $e->getMessage(),
                null,
                $this->_logfile
            );
        }

        return $html;
    }

    /**
     *
     * get advertisement block for menu
     * @param int $store
     * @param string $blockname
     */
    protected function _getMenuAdBlockContent($store, $blockname)
    {
        $blockname = Mage::helper('zeon_attributemapping')
            ->getBlockIdentifier($blockname);

        return '{{block type="cms/block" block_id="'.$blockname.'"}}';
    }

    protected function _getStaticBlockDynamic($catData, $store)
    {
        $content = '';
        try{
            $blockname = Mage::helper('zeon_attributemapping')
                ->getBlockIdentifier($catData['id']);

            $menuCmsBlock = Mage::getModel('cms/block')
                         ->setStoreId($store)
                         ->load($blockname);
            if ($menuCmsBlock && $menuCmsBlock->getId()) {
                $content = '{{block type="cms/block" block_id="'.$blockname.'"}}';
            } else {
                // block common content
                $advContent = '<div class="left-col-ad"><a href="#">
                    <img src="{{skin url=images/homepage_banner.jpg}}"
                        alt="Menu Advertisement" />
                    </a></div>';

                $menuCmsBlock = Mage::getModel('cms/block')
                    ->setTitle('Advertisement block for '.$catData['name'].' - PP')
                    ->setIdentifier($blockname)
                    ->setStores(array($store))
                    ->setIsActive(1)
                    ->setContent($advContent);
                $menuCmsBlock->save();
                $content = '{{block type="cms/block" block_id="'.$menuCmsBlock->getIdentifier().'"}}';
            }
        } catch (Exception $e) {
            Mage::log(
                'Menu _getStaticBlockDynamic : '. $blockname
                .' message: '. $e->getMessage(),
                null,
                $this->_logfile
            );

        }
        return $content;
    }

    /**
     *
     * get categories and sub-category list for menu links
     * @param int $store
     */
    protected function _getCategoryMenuCollection($store, $catIds, $setNxt = 0, $order = 'name')
    {
        $categoryData = array();
        try{
            $catIds = @explode(',', $catIds);
            //maximum depth value
            $navlevel = Mage::getStoreConfig('catalog/navigation/max_depth');
            $navlevel = empty($navlevel) ? '2' : $navlevel;
            if ($setNxt) {
                $navlevel = '3';
            }

            $catChilds = Mage::getModel('catalog/category')
                ->getCollection()
                ->addAttributeToSelect('entity_id')
                ->addAttributeToSelect('name')
                ->addAttributeToSelect('url_key')
                ->addAttributeToFilter('is_active', '1')
                ->addAttributeToFilter('include_in_menu', '1')
                ->addAttributeToFilter('is_anchor', '1')
                ->addAttributeToFilter('level', array('lteq' => $navlevel))
                ->addFieldToFilter(
                    'entity_id',
                    array('in' => $catIds)
                )
                ->setOrder($order, 'ASC')
                //->setPageSize(40)
                //->setCurPage(1)
                ->load();

                //set category
            foreach ($catChilds as $childs) {
                $getSubCat = array();
                $childSubcat = $childs->getChildrenCategories();
                if (count($childSubcat) > 0) {
                    $categoryData[$childs->getId()] = array(
                        'name' => $childs->getName(),
                        'url'  => $childs->getUrl(),
                    );

                    //set sub categories
                    foreach ($childSubcat as $subChilds) {
                        $getSubCat[] = array(
                            'option_id' => $subChilds->getId(),
                            'name' => $subChilds->getName(),
                            'url'  => $subChilds->getUrl(),
                            'url_key'  => $subChilds->getUrl(),
                        );
                    }
                    $categoryData[$childs->getId()]['child'] = $getSubCat;
                }
            }
        } catch (Exception $e) {
            Mage::log(
                'Menu _setStoreTopMenuData : '. implode(', ', $urlData)
                .' message: '. $e->getMessage(),
                null,
                $this->_logfile
            );

        }
        return $categoryData;
    }

    /**
     *
     * get single categories and sub-category list for menu links
     * @param int $store
     */
    protected function _getSingleCategoryMenuCollection($store, $catId, $code = 'personalized_gifts')
    {
        $categoryData = array();
        try{
            $category = Mage::getModel('catalog/category')->load($catId);
            $childSubcat = $category->getChildrenCategories();
            $categoryData['id'] = $catId;
            $categoryData['code'] = $code;
            $categoryData['name'] = $category->getName();
            $categoryData['menutype'] = 'category';
            $catData['menuno'] = 1;

            if (count($childSubcat) > 0) {
                //set sub categories
                foreach ($childSubcat as $subChilds) {
                    $categoryData['options'][] = array(
                        'option_id' => $subChilds->getId(),
                        'name' => $subChilds->getName(),
                        'url_key'  => $subChilds->getUrl(),
                    );
                }
            }
        } catch (Exception $e) {
            Mage::log(
                'Menu_setStoreTopMenuData1 : '. implode(', ', $urlData)
                .' message: '. $e->getMessage(),
                null,
                $this->_logfile
            );

        }
        return $categoryData;
    }

    /**
     *
     * get attribute options for menu links
     * @param int $store
     */
    protected function _getAttributeData($store)
    {
        $attributeAllData = array();
        try{
            $attributsCodes = Mage::helper('zeon_attributemapping')
                ->getConfigDetails('attribute_list');
            $attributsCodes = @explode(',', $attributsCodes);

            $resource = Mage::getSingleton('core/resource');
            $readConnection = $resource->getConnection('core_read');
            $urlRedirect = $resource
                ->getTableName('enterprise_urlrewrite/redirect');
            $urlConnect = $resource
                ->getTableName('enterprise_urlrewrite/redirect_rewrite');
            $urlRewrite = $resource
                ->getTableName('enterprise_urlrewrite/url_rewrite');
            $optionTable = $resource
                ->getTableName('eav/attribute_option');
            $optionValueTable = $resource
                ->getTableName('eav/attribute_option_value');


             // get all attribute data
            $entityTypeId = Mage::getModel('eav/entity_type')
                ->loadByCode('catalog_product')->getEntityTypeId();
            $attributes = Mage::getModel('eav/entity_attribute')
                ->getCollection()
                ->addFieldToFilter('entity_type_id', $entityTypeId)
                ->addFieldToFilter(
                    'attribute_code',
                    array('in' => $attributsCodes)
                )
                ->setOrder('attribute_id', 'ASC');

            foreach ($attributes as $attributeData) {
                $attributeId = $attributeData->getId();
                $attributeDataTable = $resource->getTableName(
                    array('zeon_attributemapping/attributemapping', $store)
                );

                // get attribute options data with all details
                $getAttributeData = 'SELECT att_table.mapping_id,'
                    . ' att_table.attribute_id, att_table.option_id,'
                    . ' att_table.url_key, att_table.option_status,'
                    . ' url_rewrite.request_path,'
                    . 'IF (opt1.value_id > 0, opt1.value, opt0.value )'
                    . ' AS `value`'
                    . ' FROM `'.$attributeDataTable.'` AS att_table'
                    . ' LEFT JOIN `'.$urlRedirect.'` AS url_redirect'
                    . ' ON url_redirect.options = att_table.mapping_id'
                    . ' AND store_id = \''.$store.'\''
                    . ' INNER JOIN `'.$urlConnect.'` AS url_connect'
                    . ' ON url_redirect.redirect_id = url_connect.redirect_id'
                    . ' INNER JOIN `'.$urlRewrite.'` AS url_rewrite'
                    . ' ON url_rewrite.url_rewrite_id ='
                    . ' url_connect.url_rewrite_id'
                    . ' LEFT JOIN `'.$optionValueTable.'` AS opt0'
                    . ' ON opt0.option_id = att_table.option_id'
                    . ' AND opt0.store_id = 0'
                    . ' INNER JOIN `'.$optionTable.'` AS opt_data'
                    . ' ON att_table.option_id = opt_data.option_id'
                    . ' LEFT JOIN `'.$optionValueTable.'` AS opt1'
                    . ' ON opt1.option_id = att_table.option_id'
                    . ' AND opt1.store_id = \''.$store.'\''
                    . ' WHERE att_table.attribute_id = \''.$attributeId.'\''
                    . ' AND att_table.option_status = \'1\''
                    . ' ORDER BY opt_data.sort_order ASC;';

                $alldata = $readConnection->fetchAll($getAttributeData);
                $attOptions = array();
                foreach ($alldata as $oprionData) {
                    if (!$oprionData['value']) {
                            continue;
                    }

                    if (isset($oprionData['request_path'])
                        && $oprionData['request_path'] != "") {
                            $urlKey = $oprionData['request_path'];
                    } else {
                        $urlKey = 'attributemapping/index/index/'
                            . 'id/'.$oprionData['option_id'].'/';
                    }

                    $strChar = substr(strtolower($oprionData['value']), 0, 1);
                    if ($attributeData->getAttributeCode() == 'character') {
                        $attOptions[$strChar][] = array(
                            'option_id' => $oprionData['option_id'],
                            'name' => $oprionData['value'],
                            'url_key' => $urlKey
                        );
                    } else {
                        $attOptions[] = array(
                            'option_id' => $oprionData['option_id'],
                            'name' => $oprionData['value'],
                            'url_key' => $urlKey
                        );
                    }

                }

                //set data for attribute oprtions
                $attributeAllData[$attributeId]['id'] =
                    $attributeId;
                $attributeAllData[$attributeId]['code'] =
                    $attributeData->getAttributeCode();
                $attributeAllData[$attributeId]['name'] =
                    $attributeData->getFrontendLabel();
                $attributeAllData[$attributeId]['options'] = $attOptions;
                //get attribute urls
                $urlAttribute = 'attributemapping/index/option/id/'
                        .$attributeId;
                $checkUrl = Mage::getModel('enterprise_urlrewrite/url_rewrite')
                    ->getCollection()
                    ->addFieldToFilter('target_path', $urlAttribute)
                    ->addFieldToFilter('store_id', $store)
                    ->getFirstItem();

                if ($checkUrl->getData()) {
                    $urlAttribute = $checkUrl->getRequestPath();
                }
                $attributeAllData[$attributeId]['url'] = $urlAttribute;
            }
        } catch (Exception $e) {
            Mage::log(
                'Menu _setStoreTopMenuData : '. implode(', ', $urlData)
                .' message: '. $e->getMessage(),
                null,
                $this->_logfile
            );

        }
        return $attributeAllData;
    }

    /**
     *
     * Build html for top menu store
     * @param int $store
     * @param array $categoryData
     * @param string $cms
     * @param string $manuType
     * @param string $title
     * @param string $extraClass
     */
    protected function _setMenuHtml($store, $categoryData = array(), $cms = '', $manuType = '',
    $title = 'Menu', $extraClass = '')
    {
        $output = '';
        $configCatPerRow = 2;
        try{
            if ($manuType == 'category') {
                $allLinkCount = Mage::helper('zeon_attributemapping')
                    ->getConfigDetails('sublink_count');
                $output .= '<li class="level0 nav-1 '.$extraClass
                    .' level-top parent">';
                $output .= '<a class="level-top" title="'.$title.'"'
                    . ' setshow="'.str_replace(' ', '-', $title).'">'
                    .'<span>'.$title.'</span></a>';
                $output .= '<div class="top-div '.str_replace(' ', '-', $title)
                    .'"><div class="topdiv-container"><div class="topdiv-seperater">';
                $divDevide = count($categoryData);
                $i = 0;
                if ($extraClass == 'personal3') {
                    $configCatPerRow = 3;
                }
                foreach ($categoryData as $cat) {
                    if (($i++ % $configCatPerRow) == 0) {
                        $output .= '<div class="sub-menu">';
                    }
                    $output .= '<span class="sub-menu-title">'
                        .$cat['name'].'</span>';

                    $output .= '<ul>';
                    $z = 0;
                    foreach ($cat['child'] as $subcat) {
                        if ($z == $allLinkCount) {
                            break;
                        }
                        $output .= '<li>';
                        $output .= '<a href="'.$subcat['url'].'"
                            title="'.$subcat['name'].'">'
                            .$subcat['name'].'</a>';
                        $output .= '</li>';
                        $z++;
                    }
                    $output .= '<li class="see-all">';
                    $output .= '<a href="'.$cat['url'].'"'
                        .' title="See All '.$cat['name'].'">'
                        . 'See All</a>';
                    $output .= '</li>';
                        $output .= '</ul>';
                    if (($i % $configCatPerRow) == 0 || $i == $divDevide) {
                        $output .= '</div>';
                    }
                }
                $output .= '<div class="addcontent"><div class="left-col-ad">'.$cms.'</div></div>';
                $output .= '</div></div></div>';
                $output .= '</li>';

            } elseif ($manuType == 'character') {
                $allLinkCount = Mage::helper('zeon_attributemapping')
                    ->getConfigDetails('sublink_count_char');
                $seeAllEnable = Mage::helper('zeon_attributemapping')
                    ->getConfigDetails('seeall_enable');
                $alpha1 = @explode(',', Mage::helper('zeon_attributemapping')->getConfigDetails('char_menu1'));
                $alpha2 = @explode(',', Mage::helper('zeon_attributemapping')->getConfigDetails('char_menu2'));
                $alpha3 = @explode(',', Mage::helper('zeon_attributemapping')->getConfigDetails('char_menu3'));

                $alfaArray = array($alpha1, $alpha2, $alpha3);

                $allUrl = Mage::helper('zeon_attributemapping')
                    ->getConfigDetails('characterpage_url', 1);
                $urlSuffix = Mage::helper('zeon_attributemapping')
                        ->getConfigDetails('url_prefix', 1);
                if ($urlSuffix) {
                    $urlSuffix = '.'.$urlSuffix;
                }
                $allUrl = $allUrl.$urlSuffix;

                $output .= '<li class="level0 nav-1 '
                    .$extraClass.' level-top parent">';
                $output .= '<a class="level-top" title="'.$title.'"'
                    . ' setshow="'.$categoryData['code'].'">'
                    .'<span>'.$title.'</span></a>';
                $output .= '<div class="top-div '.$categoryData['code'].'">'
                 .'<div class="topdiv-container"><div class="topdiv-seperater">';
                $options = $categoryData['options'];

                foreach ($alfaArray as $allAlfa) {
                    $output .= '<div class="sub-menu">';
                    $count = 0;
                    foreach ($allAlfa as $char) {
                        if (isset($options[$char]) && !empty($options[$char])) {
                            //if ($count == 0) {
                                $output .= '<span class="sub-menu-title">'
                                    .strtoupper($char).'</span>';
                            //}
                            $output .= '<ul>';
                            $z = 0;
                            foreach ($options[$char] as $optData) {
                                if ($seeAllEnable && $z == $allLinkCount) {
                                    break;
                                }
                                $output .= '<li><a '
                                . 'href="{{store url}}'
                                .$optData['url_key'].'"'
                                .' title="'.$optData['name'].'">'
                                .$optData['name'].'</a></li>';
                                $z++;
                            }
                            if ($seeAllEnable) {
                                $output .= '<li class="see-all">';
                                $output .= '<a href="{{store url}}'.$allUrl.'/?char='.$char.'"'
                                    .' title="See All '.$cat['name'].'">'
                                    . 'See All</a>';
                                $output .= '</li>';
                            }

                            $output .= '</ul>';

                            $count++;
                        }
                    }
                    $output .= '</div>';
                }

                $output .= '<div class="addcontent"><div class="left-col-ad">'.$cms.'</div></div>';
                $output .= '</div></div></div>';
                $output .= '</li>';
            } else {
                $output .= '<li class="level0 nav-1 '
                    .$extraClass.' level-top parent">';

                //display See All link for menus
                $rootMenuClickable = Mage::helper('zeon_attributemapping')
                    ->getConfigDetails('root_menu_clickable');
                if ($rootMenuClickable == 1) {
                    $output .= '<a class="level-top" title="'.$title.'"'
                        .' href="'.$categoryData['url'].'"'
                        . ' setshow="'.$categoryData['code'].'">'
                        .'<span>'.$title.'</span></a>';
                } else {
                    $output .= '<a class="level-top" title="'.$title.'"'
                        . ' setshow="'.$categoryData['code'].'">'
                        .'<span>'.$title.'</span></a>';
                }

                $menudata = str_replace(' ', '', strtolower($title));
                if (isset($categoryData['menuno'])) {
                    $menudata = $categoryData['menuno'];
                }
                $output .= '<div class="top-div '.$categoryData['code']. ' categorydrop' .$menudata.'">'
                    . '<div class="topdiv-container"><div class="topdiv-seperater">';
                $options = $categoryData['options'];

                $output .= '<div class="sub-menu">';
                $output .= '<ul>';
                $x = 0;
                foreach ($options as $optData) {
                    //if ($x++ == 12 && $categoryData['menutype'] != "category") {
                    //Changing 12 to 100 for removing See All link and showing all characters/age attribute. Assumption is that there wont 100 or more attribute values in life
                    if ($x++ == 100 && $categoryData['menutype'] != "category") {
                        $output .= '<li class="see-all">';
                        $output .= '<a href="{{store url}}'
                            .$categoryData['url'].'"'
                            .' title="See All '.$title.'">'
                            . 'See All</a>';
                        $output .= '</li>';
                        break;
                    }

                    if ($categoryData['menutype'] == "category") {
                        $output .= '<li><a '
                            . 'href="'
                            .$optData['url_key'].'"'
                            .' title="'.$optData['name'].'">'
                            .$optData['name'].'</a></li>';
                    } else {
                        $output .= '<li><a '
                            . 'href="{{store url}}'
                            .$optData['url_key'].'"'
                            .' title="'.$optData['name'].'">'
                            .$optData['name'].'</a></li>';
                    }
                }
                //display See All link for menus
                $menuSeeAllEnable = Mage::helper('zeon_attributemapping')
                    ->getConfigDetails('menu_seeall_enable');
                if ($menuSeeAllEnable == 1) {
                    $output .= '<li class="see-all"><a '
                        . 'href="'.$categoryData['url'].'"'
                        .' title="See All">See All</a></li>';
                }

                $output .= '</ul>';
                $output .= '</div>';

                $output .= '<div class="addcontent"><div class="left-col-ad">'.$cms.'</div></div>';
                $output .= '</div></div></div>';
                $output .= '</li>';

            }

        } catch (Exception $e) {
            Mage::log(
                'Menu _setStoreTopMenuData : '. implode(', ', $urlData)
                .' message: '. $e->getMessage(),
                null,
                $this->_logfile
            );

        }

        return $output;
    }
}