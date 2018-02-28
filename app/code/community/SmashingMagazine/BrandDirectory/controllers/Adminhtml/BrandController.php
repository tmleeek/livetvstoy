<?php
class SmashingMagazine_BrandDirectory_Adminhtml_BrandController
    extends Mage_Adminhtml_Controller_Action
{
    /**
     * Instantiate our grid container block and add to the page content.
     * When accessing this admin index page we will see a grid of all
     * brands currently available in our Magento instance, along with
     * a button to add a new one if we wish.
     */
    public function indexAction()
    {
       
        // instantiate the grid container
        $brandBlock = $this->getLayout()
            ->createBlock('smashingmagazine_branddirectory_adminhtml/brand');
        
        // add the grid container as the only item on this page
        $this->loadLayout()
            ->_addContent($brandBlock)
            ->renderLayout();
    }
    
    /**
     * This action handles both viewing and editing of existing brands.
     */
    public function editAction()
    {
        /**
         * retrieving existing brand data if an ID was specified,
         * if not we will have an empty Brand entity ready to be populated.
         */
        $brand = Mage::getModel('smashingmagazine_branddirectory/brand');
        if ($brandId = $this->getRequest()->getParam('id', false)) {
            $brand->load($brandId);

            if ($brand->getId() < 1) {
                $this->_getSession()->addError(
                    $this->__('This brand no longer exists.')
                );
                return $this->_redirect(
                    'smashingmagazine_branddirectory_admin/brand/index'
                );
            }
        }
        
        // process $_POST data if the form was submitted
        if ($postData = $this->getRequest()->getPost('brandData')) {
            try {
                
               // print_r($postData);exit;
               if($postData['url_key']=='')
               $postData['url_key']=str_replace(' ', '', $postData['part_no']);
               
                $brand->addData($postData);
                
                $brand->save();
               
                $product_id = Mage::getModel("catalog/product")->getIdBySku($postData['cps_sku']);
              
                $partner_name = $postData['partner_name'];
                $resource = Mage::getSingleton('core/resource');
                $writeConnection = $resource->getConnection('core_write');
                $url_key = $postData['url_key'];
         
             $query_sel = "SELECT website_id  FROM site_link_partners WHERE partnername = '$partner_name'";
             $results = $writeConnection->fetchAll($query_sel);
     
             //print_r($results);
                $websiteIds = array($results[0]['website_id']);
                $productIds=array($product_id);
            
                $product = Mage::getModel('catalog/product')->load($product_id);
              
                $product->setData('site_link',1);
                $product->save();
                 $product->setStoreId($results[0]['website_id'])->setUrlKey($url_key)->save();
                Mage::getModel('catalog/product_website')->addProducts($websiteIds, $productIds);
                //
                 
                
                
                //$product=Mage::getModel('catalog/product')->load($product_id);
                
                if($product->getTypeId() == "configurable")
                {
                    $simple_product_array=array();
                    $conf = Mage::getModel('catalog/product_type_configurable')->setProduct($product);
                    $simple_collection = $conf->getUsedProductCollection()->addAttributeToSelect('*')->addFilterByRequiredOptions();
                    foreach($simple_collection as $simple_product){
                        $simple_product_array[]=$simple_product->getId();
                       /* $simple_product_id=$postData['product_id'];
                        $part_no=$postData['part_no'];
                        $product_name=$postData['product_name'];
                        $partner_name=$postData['partner_name'];
                        $short_description=$postData['short_description'];
                        $long_description=$postData['long_description'];
                        $intro_date=$postData['intro_date'];
                        $product_name=$postData['product_name'];
                         $query = "INSERT INTO `site_link_configure_products` (`product_id`,`part_no`,`product_name`,`partner_name`,`short_description`,`long_description`,`intro_date`,`actual_price`,`retail_price`) VALUES('$simple_product_id',$websiteId)";
                                $writeConnection->query($query);
                        echo $simple_product->getSku() . " - " . $simple_product->getName() . " - " . Mage::helper('core')->currency($simple_product->getPrice()) . "<br>";*/
                    }
                    //print_r($simple_product_array);exit;
                    Mage::getModel('catalog/product_website')->addProducts($websiteIds, $simple_product_array);
                }
                
                
                
                
                $this->_getSession()->addSuccess(
                    $this->__('The Product has been saved.')
                );
                
                // redirect to remove $_POST data from the request
                return $this->_redirect(
                    'smashingmagazine_branddirectory_admin/brand/index', 
                    array('sku' => $postData['cps_sku'])
                );
            } catch (Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError($e->getMessage());
            }
            
            /**
             * if we get to here then something went wrong. Continue to
             * render the page as before, the difference being this time 
             * the submitted $_POST data is available.
             */
        }
        
        // make the current brand object available to blocks
        Mage::register('current_brand', $brand);
        
        // instantiate the form container
        $brandEditBlock = $this->getLayout()->createBlock(
            'smashingmagazine_branddirectory_adminhtml/brand_edit'
        );
        
        // add the form container as the only item on this page
        $this->loadLayout()
            ->_addContent($brandEditBlock)
            ->renderLayout();
    }
    
    public function deleteAction()
    {
        $brand = Mage::getModel('smashingmagazine_branddirectory/brand');

        if ($brandId = $this->getRequest()->getParam('id', false)) {
            $brand->load($brandId);
        }
        $resource = Mage::getSingleton('core/resource');
	$writeConnection = $resource->getConnection('core_write');
          
            
	
	
	  
	    $query_sel = "SELECT cps_sku FROM `site_link_configure_products` where entity_id=$brandId";
            $results = $writeConnection->fetchCol($query_sel);
            
        if ($brand->getId() < 1) {
            $this->_getSession()->addError(
                $this->__('This brand no longer exists.')
            );
            return $this->_redirect(
                'smashingmagazine_branddirectory_admin/brand/index'
            );
        }
        
        try {
            $brand->delete();
            
            $this->_getSession()->addSuccess(
                $this->__('The item has been deleted.')
            );
        } catch (Exception $e) {
            Mage::logException($e);
            $this->_getSession()->addError($e->getMessage());
        }

        return $this->_redirect(
            'smashingmagazine_branddirectory_admin/brand/index/sku/'.$results[0]
        );
    }
    
    /**
     * Thanks to Ben for pointing out this method was missing. Without 
     * this method the ACL rules configured in adminhtml.xml are ignored.
     */
    protected function _isAllowed()
    {
        /**
         * we include this switch to demonstrate that you can add action 
         * level restrictions in your ACL rules. The isAllowed() method will
         * use the ACL rule we have configured in our adminhtml.xml file:
         * - acl 
         * - - resources
         * - - - admin
         * - - - - children
         * - - - - - smashingmagazine_branddirectory
         * - - - - - - children
         * - - - - - - - brand
         * 
         * eg. you could add more rules inside brand for edit and delete.
         */
        $actionName = $this->getRequest()->getActionName();
        switch ($actionName) {
            case 'index':
            case 'edit':
            case 'delete':
                // intentionally no break
            default:
                $adminSession = Mage::getSingleton('admin/session');
                $isAllowed = $adminSession
                    ->isAllowed('smashingmagazine_branddirectory/brand');
                break;
        }
        
        return $isAllowed;
    }
    /**
     * This action handles both viewing and editing of existing brands.
     */
    public function addAction()
    {
       $sku=$this->getRequest()->getParam('sku');
        
        
        
        // instantiate the form container
        $brandAddBlock = $this->getLayout()->createBlock(
            'smashingmagazine_branddirectory_adminhtml/brand_add'
        );
        
        // add the form container as the only item on this page
        $this->loadLayout()
            ->_addContent($brandAddBlock)
            ->renderLayout();
    }
    
    
    
     public function newpAction()
    {
       
        $brand = Mage::getModel('smashingmagazine_branddirectory/brand');
      
        
       
        if ($postData = $this->getRequest()->getPost('brandData')) {
            try {
                
                
                 $partner_name = strtoupper($postData['partner_name']);
                $resource = Mage::getSingleton('core/resource');
          $writeConnection = $resource->getConnection('core_write');
          $wname=$postData['partner_name'];
          $wcode=str_replace(" ","",strtolower($postData['partner_name']));
         
             $query_sel = "SELECT *  FROM site_link_partners WHERE partnername = '$partner_name'";
             $results = $writeConnection->fetchAll($query_sel);

                if(count($results)==0)
                {
                                                       
                            
                            $website = Mage::getModel('core/website');
                            $website->setCode($wcode)
                                ->setName($wname)
                                ->save();
                        
                        
                           
                            $storeGroup = Mage::getModel('core/store_group');
                            $storeGroup->setWebsiteId($website->getId())
                                ->setName($wname)
                                ->setRootCategoryId('2')
                                ->save();
                        
                       
                           
                            $store = Mage::getModel('core/store');
                            $store->setCode($wcode)
                                ->setWebsiteId($storeGroup->getWebsiteId())
                                ->setGroupId($storeGroup->getId())
                                ->setName($wname)
                                ->setIsActive(1)
                                ->save();
                                
                                $website = Mage::getModel('core/website')->load($wcode);
                                $websiteId = $website->getId();
                                $query = "INSERT INTO `site_link_partners` (`partnername`,`website_id`) VALUES('$partner_name',$websiteId)";
                                $writeConnection->query($query);
                                
                               /* echo $base_dir = $_SERVER["DOCUMENT_ROOT"]; //document root

                                echo $template_ref = $base_dir."app/design/frontend/enterprise/limoges";
                                echo $template_new = $base_dir."app/design/frontend/enterprise/".$wcode;
                                echo $skin_ref = $base_dir."skin/frontend/enterprise/limoges";
                                echo $skin_new = $base_dir."skin/frontend/enterprise/".$wcode;
                               
                                
                               
                                exec("sh ".$base_dir."store_copy.sh ".$template_ref." ".$template_new);
                                exec("sh ".$base_dir."store_copy.sh ".$skin_ref." ".$skin_new);*/
                            
                }
                
              
                
                // redirect to remove $_POST data from the request
               
            $this->_getSession()->addSuccess(
                $this->__('New partner added.')
            );
             return $this->_redirect(
                    'smashingmagazine_branddirectory_admin/brand/newp'
                );
            } catch (Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError($e->getMessage());
            }
            
            /**
             * if we get to here then something went wrong. Continue to
             * render the page as before, the difference being this time 
             * the submitted $_POST data is available.
             */
        }
        
        // make the current brand object available to blocks
        Mage::register('current_brand', $brand);
        
        // instantiate the form container
        $brandNewBlock = $this->getLayout()->createBlock(
            'smashingmagazine_branddirectory_adminhtml/brand_newp'
        );
        
        // add the form container as the only item on this page
        $this->loadLayout()
            ->_addContent($brandNewBlock)
            ->renderLayout();
    }
    public function bulkuploadAction()
    {
        
        
        
        
        $brandUploadBlock = $this->getLayout()->createBlock(
            'smashingmagazine_branddirectory_adminhtml/brand_upload'
        );
        
        // add the form container as the only item on this page
        $this->loadLayout()
            ->_addContent($brandUploadBlock)
            ->renderLayout();
        
        
        
    }
     public function saveAction()
    {
       
     if(isset($_FILES['upload_file']['name']) and (file_exists($_FILES['upload_file']['tmp_name']))) {
  try {
    $uploader = new Varien_File_Uploader('upload_file');
    
    $uploader->setAllowedExtensions(array('csv')); // or pdf or anything
 
 
    $uploader->setAllowRenameFiles(false);
 
    // setAllowRenameFiles(true) -> move your file in a folder the magento way
    // setAllowRenameFiles(true) -> move your file directly in the $path folder
    $uploader->setFilesDispersion(false);
   
      $path = Mage::getBaseDir('media') . DS ;
      $ext = strtotime("now");
      $file_to_write = $path."/sitelink/sitelink-product-export-report-".$ext.".csv";
      $remote_file_name="sitelink-product-export-report-".$ext.".csv";
      @unlink($file_to_write);

        $out = fopen($file_to_write,'w');
        if($out){
                chmod($file_to_write,0777);
        }
        $header = array('cps_sku',
        'external_productid',
        'partnername',
        'status',
        'link'
        );
              $file_path=$path.$_FILES['upload_file']['name']; 
    $uploader->save($path, $_FILES['upload_file']['name']);
 
    $fh = fopen($file_path, "r") or die("can't open file");
    
    if (($handle_file = fopen($file_path, "r")) !== FALSE)
        {
            $row = 1;
            $postData=array();
            
            
           $this->importProducts($header, $out);
          
            $flag = true;
            while (($product_data = fgetcsv($handle_file, 1000, ",")) !== FALSE)
            {
                $writeData=array();
                $writeData[]=$product_data[1];
                $writeData[]=$product_data[0];
                $writeData[]=$product_data[3];
                
                if($flag)
                {
                    $flag = false;
                    continue;
                }
            $product=Mage::getModel('catalog/product')->loadByAttribute('sku',$product_data[1]);
            if (!$product){
                $writeData[]="fail";
                $writeData[]="No such cps sku exists";
                $this->importProducts($writeData, $out);
                continue;
            }
            if($product->getStatus()==2)
            {
                $writeData[]="fail";
                $writeData[]="Disabled";
                $this->importProducts($writeData, $out);
                continue;
            }
            
             $resource = Mage::getSingleton('core/resource');
            $writeConnection = $resource->getConnection('core_write');
            $query_sel_dup = "select * from site_link_configure_products where cps_sku='$product_data[1]' and partner_name='$product_data[3]'";
            $results_dup = $writeConnection->fetchAll($query_sel_dup);

            if(count($results_dup)>0) {
                $writeData[]="fail";
                $writeData[]="Already Sitelinked";
                $this->importProducts($writeData, $out);
                 continue;
            }
             $query_sel_dup_prod = "select * from site_link_configure_products where part_no='$product_data[0]' and partner_name='$product_data[3]'";
            $results_dup_prod = $writeConnection->fetchAll($query_sel_dup_prod);

            if(count($results_dup_prod)>0) {
                $writeData[]="fail";
                $writeData[]="External product id exists";
                $this->importProducts($writeData, $out);
                 continue;
            }
            
            $postData['product_id']=$product_data[2];
            $postData['part_no']=$product_data[0];
            if($product_data[5]=='')
            $postData['product_name']=$product->getName();
            else
            $postData['product_name']=$product_data[5];
            $postData['partner_name']=strtoupper($product_data[3]);
            $postData['short_description']=$product_data[8];
            if($product_data[9]=='')
            $postData['long_description']=$product->getDescription();
            else
            $postData['long_description']=$product_data[9];
            if($product_data[6]!='')
            $postData['intro_date']=date("Y-m-d H:i:s",strtotime($product_data[6]));
            else
            $postData['intro_date']=$product_data[6];
             if($product_data[7]!='')
            $postData['cancel_date']=date("Y-m-d H:i:s",strtotime($product_data[7]));
            else
            $postData['cancel_date']=$product_data[7];
            $postData['actual_price']=$product_data[11];
            $postData['retail_price']=$product_data[10];
            $postData['cps_sku']=$product_data[1];
            $postData['url_key']=$product_data[18];
            $postData['custom_option']=$product_data[12];
            $postData['custom_text']=$product_data[13];
            $postData['custom_url']=$product_data[14];
             $postData['custom_option1']=$product_data[15];
            $postData['custom_text1']=$product_data[16];
            $postData['custom_url1']=$product_data[17];
           
                $brand = Mage::getModel('smashingmagazine_branddirectory/brand');
        
       
            try {
                
               
               if($postData['url_key']=='')
               $postData['url_key']=str_replace(' ', '', $postData['part_no']);
               
                $brand->addData($postData);
                
                $brand->save();
               
                $product_id = Mage::getModel("catalog/product")->getIdBySku($postData['cps_sku']);
              
                $partner_name = $postData['partner_name'];
                
                $url_key = $postData['url_key'];
         
             $query_sel = "SELECT website_id  FROM site_link_partners WHERE partnername = '$partner_name'";
             $results = $writeConnection->fetchAll($query_sel);
     
             
                $websiteIds = array($results[0]['website_id']);
                $productIds=array($product_id);
            
                $product = Mage::getModel('catalog/product')->load($product_id);
                 $product->setData('site_link',1);
                $product->save();
                $product->setStoreId($results[0]['website_id'])->setUrlKey($url_key)->save();
                
                Mage::getModel('catalog/product_website')->addProducts($websiteIds, $productIds);
                
                
                
                if($product->getTypeId() == "configurable")
                {
                    $simple_product_array=array();
                    $conf = Mage::getModel('catalog/product_type_configurable')->setProduct($product);
                    $simple_collection = $conf->getUsedProductCollection()->addAttributeToSelect('*')->addFilterByRequiredOptions();
                    foreach($simple_collection as $simple_product){
                        $simple_product_array[]=$simple_product->getId();
                      
                    }
                    
                    Mage::getModel('catalog/product_website')->addProducts($websiteIds, $simple_product_array);
                }
                $writeData[]="Success";
                if($product_data[3]=='WALMART SITELINK')
                $url='http://personalizeditems-cps.walmart.com/'.$url_key;
                else if($product_data[3]=='HSN SITELINK')
                $url='http://ep10p.hsn.com/'.$url_key;
                $writeData[]=$url;
                $this->importProducts($writeData, $out);
              
            } catch (Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError($e->getMessage());
                $writeData[]="fail";
                $writeData[]=$e->getMessage();
                $this->importProducts($writeData, $out);
            }
            
       
        
        $row++;
            }
          fclose($out);
            fclose($handle_raku);
            //sent email.
                            $file_link='https://admin.tystoybox.com/media/sitelink/'.$remote_file_name;
                               $to = "sitelinkalerts@cpscompany.com";
                               $from = "noreply@cpscompany.com";
                               $subject = "Sitelink Bulk Upload Report";
                           
                               //begin of HTML message
                               $message = "
                           <html>
                             <body >
                              <table>
                              Hello,<br><br>
                             
                              Please <a href=".$file_link." target=_blank>click here</a> to download the latest sitelink bulk upload report.<br><br>
                              
                              Thanks,<br>
                              CPS Team
                              </table>
                                </body>
                           </html>";
                           
                              //end of message
                               $headers  = "From: $from\r\n";
                               $headers .= "Content-type: text/html\r\n";
                           
                               //options to send to cc+bcc
                               //$headers .= "Cc: [email]maa@p-i-s.cXom[/email]";
                               //$headers .= "Bcc: [email]email@maaking.cXom[/email]";
                               
                               // now lets send the email.
                              mail($to, $subject, $message, $headers);
             $this->_getSession()->addSuccess(
                $this->__('Success.')
            );
            return $this->_redirect(
                    'smashingmagazine_branddirectory_admin/brand/bulkupload'
                );
           
        }
    
    
  }catch(Exception $e) {
 Mage::logException($e);
                $this->_getSession()->addError($e->getMessage());
                return $this->_redirect(
                    'smashingmagazine_branddirectory_admin/brand/bulkupload'
                );
  }
}
    }
    public function exportAction()
    {
        
        
        
        
        $brandExportBlock = $this->getLayout()->createBlock(
            'smashingmagazine_branddirectory_adminhtml/brand_export'
        );
        
        // add the form container as the only item on this page
        $this->loadLayout()
            ->_addContent($brandExportBlock)
            ->renderLayout();
        
        
        
    }
    public function exportfileAction()
    {
       
        $brand = Mage::getModel('smashingmagazine_branddirectory/brand');
      
        
       
        if ($postData = $this->getRequest()->getPost('brandData')) {
            $partner_name = $postData['partner_name'];
            $num = 0;
            $resource = Mage::getSingleton('core/resource');
            $writeConnection = $resource->getConnection('core_write');
               
         
             $query_sel = "SELECT *  FROM site_link_configure_products WHERE partner_name = '$partner_name'";
             $results = $writeConnection->fetchAll($query_sel);
             if($partner_name=='WALMART SITELINK')
             $url='http://personalizeditems-cps.walmart.com/';
             else if($partner_name=='HSN SITELINK')
             $url='http://ep10p.hsn.com/';
             else if($partner_name=='SEARS SITELINK')
             $url='http://sears.personalizedplace.com/';
     foreach($results as $simple_product){
        //echo "1_";
        $prod[$num]['external_productid']        = $simple_product['part_no'];
        $prod[$num]['cps_sku']        = $simple_product['cps_sku'];
        $prod[$num]['offer_id']        = $simple_product['product_id'];
        $prod[$num]['partnername']        = $simple_product['partner_name'];
        $prod[$num]['productname']        = $simple_product['product_name'];
        $prod[$num]['introdate']        = $simple_product['intro_date'];
        $prod[$num]['canceldate']        = $simple_product['cancel_date'];
        $prod[$num]['shortdescription']        = $simple_product['short_description'];
        
         $prod[$num]['longdescription']        = $simple_product['long_description'];
         $prod[$num]['retailprice']        = $simple_product['retail_price'];
         $prod[$num]['saleprice']        = $simple_product['actual_price'];
         $prod[$num]['url_key']        = $simple_product['url_key'];
        // echo "2_";
         /*if($simple_product['cps_sku']!='')
         {
         $_Pdetails = Mage::getModel('catalog/product')->loadByAttribute('sku',(string)$simple_product['cps_sku']);
         $prod[$num]['cps_product_name']        = $_Pdetails->getName();
         }
         else*/
         //$prod[$num]['cps_product_name']        = '';
         $prod[$num]['full_url']        = $url.''.$simple_product['url_key'];
       // echo $prod[$num]['external_productid']."<br>";
         $num++;
         
    }
    //exit;
$file_name=strtolower(str_replace(' ', '', $simple_product['partner_name']))."_sku_report.csv";
$output = fopen("php://output",'w') or die("Can't open php://output");
header("Content-Type:application/csv"); 
header("Content-Disposition:attachment;filename=$file_name"); 
fputcsv($output, array('external_productid','cps_sku','offer_id','partnername','productname','introdate','canceldate','shortdescription','longdescription','retailprice','saleprice','url_key','cps_product_name','full_url'));
foreach($prod as $product) {
    fputcsv($output, $product);
}
fclose($output) or die("Can't close php://output");
        }
        
            
    }
    
     function importProducts($data, $fh)
    {
            if($fh){
                
                    fputcsv($fh, $data);
            }
    
    }
    
}