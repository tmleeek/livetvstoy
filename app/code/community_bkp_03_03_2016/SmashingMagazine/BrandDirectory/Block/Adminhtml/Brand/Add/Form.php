<script>
    function enableFields(v)
    {
        if(v.value==2)
        {
            document.getElementById('custom_text').disabled='';
            document.getElementById('custom_url').disabled='';
        }
        else
        {
            document.getElementById('custom_text').disabled='disabled';
            document.getElementById('custom_url').disabled='disabled';
        }
        
    }
     function enableFields1(v)
    {
        if(v.value==2)
        {
            document.getElementById('custom_text1').disabled='';
            document.getElementById('custom_url1').disabled='';
        }
        else
        {
            document.getElementById('custom_text1').disabled='disabled';
            document.getElementById('custom_url1').disabled='disabled';
        }
        
    }
</script>
<?php
class SmashingMagazine_BrandDirectory_Block_Adminhtml_Brand_Add_Form
    extends Mage_Adminhtml_Block_Widget_Form
{
    protected function _prepareForm()
    {
        $sku=$this->getRequest()->getParam('sku');
        $_Pdetails = Mage::getModel('catalog/product')->loadByAttribute('sku',$sku);
       
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
         //print_r($result);
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
                'input' => 'select',
                'values' => $result,
                'required' => true,
            ),
            'cps_sku' => array(
                
                'input' => 'hidden',
                'value' => $sku,
                
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
                'value' => $_Pdetails->getName(),
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
                'after_element_html' => '<i>Default value Client SKU</i>',
            ),
            'short_description' => array(
                'label' => $this->__('Short Desc'),
                'input' => 'textarea',
                'required' => true,
                'value' => $_Pdetails->getShortDescription(),
            ),
            'long_description' => array(
                'label' => $this->__('Long Desc'),
                'input' => 'textarea',
                'required' => true,
                'value' => $_Pdetails->getDescription(),
            ),
            'actual_price' => array(
                'label' => $this->__('Actual Price'),
                'input' => 'text',
                'required' => true,
                'value' => $_Pdetails->getPrice(),
            ),
            'retail_price' => array(
                'label' => $this->__('Retail Price'),
                'input' => 'text',
                'required' => true,
                'value' => $_Pdetails->getPrice(),
            ),
            
           
              'custom_option' => array(
                'label' => $this->__('Top Custom Option'),
                'input' => 'select',
                'value'  => '1',
                'values' => array('1' => 'No','2' => 'Yes'),
                'onchange' => "enableFields(this);",
            ),
               'custom_text' => array(
                'label' => $this->__('Custom Text'),
                'input' => 'text',
                'disabled' => true,
            ),
                'custom_url' => array(
                'label' => $this->__('Custom Url'),
                'input' => 'text',
                'disabled' => true,
            ),
             'custom_option1' => array(
                'label' => $this->__('Bottom Custom Option'),
                'input' => 'select',
                'value'  => '1',
                'values' => array('1' => 'No','2' => 'Yes'),
                'onchange' => "enableFields1(this);",
            ),
               'custom_text1' => array(
                'label' => $this->__('Bottom Custom Text'),
                'input' => 'text',
                'disabled' => true,
            ),
                'custom_url1' => array(
                'label' => $this->__('Bottom Custom Url'),
                'input' => 'text',
                'disabled' => true,
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
