<?php
class Zeon_Bannerslider_Block_Adminhtml_Banner_Edit_Tab_Form
    extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $this->setForm($form);

        $dataObj = new Varien_Object(
            array(
                'store_id' => '',
                'name_in_store' => '',
                'status_in_store' => '',
                'click_url_in_store' => '',
            )
        );
        if (Mage::getSingleton('adminhtml/session')->getBannerData()) {
            $data = Mage::getSingleton('adminhtml/session')->getBannerData();
            Mage::getSingleton('adminhtml/session')->setBannerData(null);
        } else if (Mage::registry('banner_data')) {
            $data = Mage::registry('banner_data')->getData();
        }

        if (isset($data)) {
            $dataObj->addData($data);
        }
        $data = $dataObj->getData();

        $fieldset = $form->addFieldset(
            'banner_form',
            array(
                'legend' => Mage::helper('bannerslider')
                    ->__('Banner information')
            )
        );

        $imageCalendar = Mage::getBaseUrl('skin') .
            'adminhtml/default/default/images/grid-cal.gif';

        $fieldset->addField(
            'name',
            'text',
            array(
                'label' => Mage::helper('bannerslider')->__('Name'),
                'class' => 'required-entry',
                'required' => true,
                'name' => 'name',
                //'disabled' => ($inStore && !$data['name_in_store']),
            )
        );

        $fieldset->addField(
            'status',
            'select',
            array(
                'label' => Mage::helper('bannerslider')->__('Status'),
                'name' => 'status',
                'values' => array(
                    array(
                        'value' => 0,
                        'label' => Mage::helper('bannerslider')->__('Disabled'),
                    ),
                    array(
                        'value' => 1,
                        'label' => Mage::helper('bannerslider')->__('Enabled'),
                    ),
                ),
            )
        );

        $fieldset->addField(
            'image_alt',
            'text',
            array(
                'label' => Mage::helper('bannerslider')->__('Alt Text'),
                'name' => 'image_alt',
                'note' => 'Used for SEO',
            )
        );

        $fieldset->addField(
            'click_url',
            'text',
            array(
                'label' => Mage::helper('bannerslider')->__('URL'),
                'required' => false,
                'class' => 'validate-url',
                'name' => 'click_url',
                'note' => 'Redirect to this URL after clicking this banner.
                    ex. http://www.example.com',
            )
        );

        if (isset($data['image']) && $data['image']) {
            $imageName = Mage::helper('bannerslider')
                ->reImageName($data['image']);
            $data['image'] = 'bannerslider' . '/' . $imageName;
        }
        $fieldset->addField(
            'image',
            'image',
            array(
                'label' => Mage::helper('bannerslider')->__('Banner Image'),
                'name'  => 'image',
                'note'  => 'Please upload image files in gif/jpg/png
                    format only. Banner image resolution must be
                    1000px x 320px.',
            )
        );


        if (!Mage::app()->isSingleStoreMode()) {
            $field = $fieldset->addField(
                'store_id',
                'multiselect',
                array(
                    'name'      => 'stores[]',
                    'label'     => Mage::helper('cms')->__('Store View'),
                    'title'     => Mage::helper('cms')->__('Store View'),
                    'required'  => true,
                    'values'    => Mage::getSingleton('adminhtml/system_store')
                        ->getStoreValuesForForm(false, true),
                    'disabled'  => $isElementDisabled,
                )
            );
//            $renderer = $this->getLayout()
//              ->createBlock('adminhtml/store_switcher_form
//              _renderer_fieldset_element');
//            $field->setRenderer($renderer);
        } else {
            $fieldset->addField(
                'store_id',
                'hidden',
                array(
                    'name'      => 'stores[]',
                    'value'     => Mage::app()->getStore(true)->getId()
                )
            );
            $model->setStoreId(Mage::app()->getStore(true)->getId());
        }

        $fieldset->addField(
            'start_time',
            'date',
            array(
                'label' => Mage::helper('bannerslider')->__('Start date'),
                'format' => 'yyyy-MM-dd HH:mm',
                'required' => true,
                'image' => $imageCalendar,
                'name' => 'start_time',
                'time' => true
            )
        );

        $fieldset->addField(
            'end_time',
            'date',
            array(
                'label' => Mage::helper('bannerslider')->__('End date'),
                'format' =>'yyyy-MM-dd HH:mm',
                'required' => true,
                'image' => $imageCalendar,
                'name' => 'end_time',
                'time' => true
            )
        );

        $fieldset->addField(
            'sort_order',
            'text',
            array(
                'label' => Mage::helper('bannerslider')->__('Sort Order'),
                'name' => 'sort_order',
                'required' => true,
            )
        );

        $form->setValues($data);

        return parent::_prepareForm();
    }

    protected function _prepareLayout()
    {
        $return = parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        return $return;
    }

}