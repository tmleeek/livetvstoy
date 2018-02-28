<?php
class SmashingMagazine_BrandDirectory_Block_Adminhtml_Brand_Edit_Form
    extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        // instantiate a new form to display our brand for editing
        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl(
                'smashingmagazine_branddirectory_admin/brand/edit', 
                array(
                    '_current' => true,
                    'continue' => 0,
                )
            ),
            'method' => 'post',
        ));
        $form->setUseContainer(true);
        $this->setForm($form);
         $resource = Mage::getSingleton('core/resource');
        $writeConnection = $resource->getConnection('core_write');
        $query_sel = "SELECT *  FROM site_link_partners";
        $results = $writeConnection->fetchAll($query_sel);
        $result = array();
        $result['']='Choose';
        foreach( $results as $row )
        {
            $partner= $row['partnername'];
            $result["$partner"]="$partner";
        }
        // define a new fieldset, we only need one for our simple entity
        $fieldset = $form->addFieldset(
            'general',
            array(
                'legend' => $this->__('Product Details')
            )
        );
        
        $brandSingleton = Mage::getSingleton(
            'smashingmagazine_branddirectory/brand'
        );
        
        // add the fields we want to be editable
        $this->_addFieldsToFieldset($fieldset, array(
            'product_id' => array(
                'label' => $this->__('Offer Id'),
                'input' => 'text',
                'required' => true,
            ),
            'partner_name' => array(
                'label' => $this->__('Partner Name'),
                'input' => 'text',
                'readonly' => true,
                
            ),
            'cps_sku' => array(
                
                'input' => 'hidden',
               
            ),
            'part_no' => array(
                'label' => $this->__('Client SKU'),
                'input' => 'text',
                'required' => true,
            ),
             'model_no' => array(
                'label' => $this->__('Model No'),
                'input' => 'text',
                
            ),
              'product_name' => array(
                'label' => $this->__('Product Name'),
                'input' => 'text',
                'required' => true,
            ),
              'intro_date' => array(
                'label' => $this->__('Intro Date'),
                'input' => 'date',
                'required' => true,
                'image' => $this->getSkinUrl('images/grid-cal.gif'),
                'format' => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
            ),
              'cancel_date' => array(
                'label' => $this->__('Cancel Date'),
                'input' => 'date',
               
                'image' => $this->getSkinUrl('images/grid-cal.gif'),
                'format' => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
            ),
               'url_key' => array(
                'label' => $this->__('Url Key'),
                'input' => 'text',
                'required' => true,
                 ),
            'short_description' => array(
                'label' => $this->__('Short Desc'),
                'input' => 'textarea',
                'required' => true,
            ),
            'long_description' => array(
                'label' => $this->__('Long Desc'),
                'input' => 'textarea',
                'required' => true,
            ),
            'actual_price' => array(
                'label' => $this->__('Actual Price'),
                'input' => 'text',
                'required' => true,
            ),
            'retail_price' => array(
                'label' => $this->__('Retail Price'),
                'input' => 'text',
                'required' => true,
            ),
           
             'custom_option' => array(
                'label' => $this->__('Custom Option'),
                'input' => 'select',
                
                'values' => array('1' => 'No','2' => 'Yes'),
                
            ),
               'custom_text' => array(
                'label' => $this->__('Custom Text'),
                'input' => 'text',
                
            ),
                'custom_url' => array(
                'label' => $this->__('Custom Url'),
                'input' => 'text',
               
            ),
               'custom_option1' => array(
                'label' => $this->__('Bottom Custom Option'),
                'input' => 'select',
                
                'values' => array('1' => 'No','2' => 'Yes'),
                'onchange' => "enableFields1(this);",
            ),
               'custom_text1' => array(
                'label' => $this->__('Bottom Custom Text'),
                'input' => 'text',
                
            ),
                'custom_url1' => array(
                'label' => $this->__('Bottom Custom Url'),
                'input' => 'text',
                
            ),   
             
            
            /**
             * Note: we have not included created_at or updated_at,
             * we will handle those fields ourself in the Model before save.
             */
        ));

        return $this;
    }
    
    /**
     * This method makes life a little easier for us by pre-populating 
     * fields with $_POST data where applicable and wraps our post data in 
     * 'brandData' so we can easily separate all relevant information in
     * the controller. You can of course omit this method entirely and call
     * the $fieldset->addField() method directly.
     */
    protected function _addFieldsToFieldset(
        Varien_Data_Form_Element_Fieldset $fieldset, $fields)
    {
        $requestData = new Varien_Object($this->getRequest()
            ->getPost('brandData'));
        
        foreach ($fields as $name => $_data) {
            if ($requestValue = $requestData->getData($name)) {
                $_data['value'] = $requestValue;
            }
            
            // wrap all fields with brandData group
            $_data['name'] = "brandData[$name]";
            
            // generally label and title always the same
            $_data['title'] = $_data['label'];
            
            // if no new value exists, use existing brand data
            if (!array_key_exists('value', $_data)) {
                $_data['value'] = $this->_getBrand()->getData($name);
            }
            
            // finally call vanilla functionality to add field
            $fieldset->addField($name, $_data['input'], $_data);
        }
        
        return $this;
    }
    
    /**
     * Retrieve the existing brand for pre-populating the form fields.
     * For a new brand entry this will return an empty Brand object.
     */
    protected function _getBrand() 
    {
        if (!$this->hasData('brand')) {
            // this will have been set in the controller
            $brand = Mage::registry('current_brand');
            
            // just in case the controller does not register the brand
            if (!$brand instanceof 
                    SmashingMagazine_BrandDirectory_Model_Brand) {
                $brand = Mage::getModel(
                    'smashingmagazine_branddirectory/brand'
                );
            }
            
            $this->setData('brand', $brand);
        }
        
        return $this->getData('brand');
    }
}