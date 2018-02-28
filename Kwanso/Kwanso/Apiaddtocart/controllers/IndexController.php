<?php
class Kwanso_Apiaddtocart_IndexController extends Mage_Core_Controller_Front_Action{
    public function IndexAction() {
      echo md5(date("Y-m-d H:i:s"));
       throw new Exception("Please stop hitting me");
      
	  // $this->loadLayout();   
	  // $this->getLayout()->getBlock("head")->setTitle($this->__("Titlename"));
	  //       $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
   //    $breadcrumbs->addCrumb("home", array(
   //              "label" => $this->__("Home Page"),
   //              "title" => $this->__("Home Page"),
   //              "link"  => Mage::getBaseUrl()
		 //   ));

   //    $breadcrumbs->addCrumb("titlename", array(
   //              "label" => $this->__("Titlename"),
   //              "title" => $this->__("Titlename")
		 //   ));

   //    $this->renderLayout(); 
	  
    }



  public function gettokenAction(){
    
    $data = array('token'=>md5(date("Y-m-d H:i:s")),'expire_date'=>date("Y-m-d H:i:s", strtotime('+24 hours')),'created_at'=>date("Y-m-d H:i:s"), 'updated_at'=> "");
    $model = Mage::getModel('apiaddtocart/apiaddtocart')->setData($data);
    try {
        $access_token = $model->save()->getData();
        echo json_encode(array('code' => '200','text' => 'Ok','description' => "Success!", "access_token" => $access_token["token"]));
        return;
    } catch (Exception $e){
     echo json_encode(array('code' => '503','text' => 'Bad Request! Something went wrong please try again.','description' => $e->getMessage()));
     return;
    }
  }

  public function addtocartAction(){
    ini_set('memory_limit', '2048M');

    // $access_token = Mage::getModel('apiaddtocart/apiaddtocart')->getCollection()->addFieldToFilter('token', $_POST["access_token"])->getFirstItem();
    
    $read = Mage::getModel('core/resource')->getConnection('core_read');
    $session_id = $_POST["session_id"];
    $query = $read->select()->from('core_session')->where("session_id = "."'".$session_id."'");
    $result = $read->fetchAll($query);
    if (count($result) == 0){
      echo json_encode(array('code' => '401','text' => 'Not Acceptable','description' => "Authentication credentials were missing or incorrect."));
      return;
    }
    // instantiate session model first
    $session = Mage::getSingleton('core/session', array('name' => 'frontend'));
    // Mage::log($session->getSessionId(), null, 'cartLog.log');
    // // close session. the session model does not provide a method for this
    // session_write_close();
    // unset($_SESSION);
    // // open new session
    // $session->setId($session_id);
    // $session->init('checkout', 'frontend');
    // //Now you can retrieve the quote as usual:
    // Mage::log(print_r($session->getId()), null, 'logfile.log');
    // $quote = $session->getQuote();
    // $quote->save();
    // //If you want to restore your own session afterwards, you have to close the session again and reinitialize the session model with the original session id:

    // //if($quote->getId()) {
    //   $product = Mage::getModel('catalog/product')->load($_POST["product"]);
    //         // exit;
    //   // load quote by customer and store...
    //   // $sale_quote = Mage::getModel('sales/quote')->load($quote->getId());

    //   $options = Mage::getModel('catalog/product_option')->getProductOptionCollection($product);
    //   $options_array = array();

    //   foreach ($options as $option) {
    //     $options_array = $options_array + array($option->getId() => "tes" );
    //   }

    //     // $newOptionArray = array();
    //     // foreach ($options as $optionKey => $optionsValue) {
    //     //   if (strtolower($optionsValue) == 'optional') {
    //     //     $newOptionArray[$optionKey] = '';
    //     //   } else {
    //     //     $newOptionArray[$optionKey] = $optionsValue;
    //     //   }
    //     // }

    //   Mage::log(print_r($options_array), null, 'logfile.log');

    //   $params = array(
    //     'qty' => $_POST["qty"],
    //     'price' => $product->getPrice(),
    //     'options' => $options_array
    //   );

    //   $cart = Mage::getSingleton('checkout/cart');

    //   // ,
    //   //   'options' => $options_array
    //   // $request = new Varien_Object();
    //   // $request->setData($params);
    //   // $return = $sale_quote->addProduct($product, new Varien_Object($params));

    //   // Mage::log(print_r($return), null, 'logfile.log');
    //   $cart->addProduct($product, $params);
    //   $cart->save();
    //   // $sale_quote->collectTotals()->save();

    //   Mage::getSingleton('checkout/session')->setCartWasUpdated(true);

      echo json_encode(array('code' => '200','text' => " id : ",'description' => "Product added to cart"));
      return;

    // } else {
    //    echo json_encode(array('code' => '400','text' => "Session Id : ".$session_id," Quote Id : " => print_r($quote)));
    //   return;
    // }


  }

  public function addAction(){
    $_POST['products'] = (array)json_decode($_POST['products']);
    $product_response = array();
    foreach ($_POST['products'] as $product) {
      $response = $this->add_product_to_cart($product);
      array_push($product_response, $response);
    }
    echo json_encode($product_response);
  }

  private function add_product_to_cart($product){
    ini_set('memory_limit', '2048M');

    try{
      
      $free_product_sku = explode(",", Mage::getStoreConfig('cat_csv_sec/cat_csv_grp/free_charms'));

      Mage::getSingleton('core/session', array('name' => 'frontend'));  
      // Get customer session
      $session = Mage::getSingleton('customer/session'); 
      // Get cart instance
      $cart = Mage::getSingleton('checkout/cart'); 
      $cart->init();
      Mage::log($session->getSessionId(), null, 'cartLog.log');
      // Add a product with custom options
      if ($product == "" or $product == null){
        return array('code' => '404','text' => "Not Acceptable",'description' => "Product sku missing.");
      }
      
      $productInstance = Mage::getModel('catalog/product')->loadByAttribute('sku', $product->sku);

      
      if (!$productInstance){
        return array('code' => '404','text' => "Not Acceptable",'description' => "Product not found with this product sku => ".$product->sku);return;
      }

      // $stock = Mage::getModel('cataloginventory/stock_item')->loadByProduct($productInstance);
      // Mage::log($session->getSessionId(), null, 'cartLog.log');

      $options = Mage::getModel('catalog/product_option')->getProductOptionCollection($productInstance);      
      $optsSize = $options->getSize();
      $empty_option = false;
      $recived_labels = array();
      $hasOptions = $productInstance->hasCustomOptions();
      $options_array = array();
      
      if ($optsSize > 0){
        $options_recived = (array)$product->options;

        foreach ($options_recived as $key => $value) {
          array_push($recived_labels,$key);
          Mage::log($value, null, 'cartLog.log');
          if ($value == "" or $value == null)
            $empty_option = true; 
        }


        $options_array_labels = array();
        $options_array_key_value = array();
        foreach ($options as $option) {
          $options_array_key_value = $options_array_key_value + array($option->getId() =>  $option->getTitle());
          array_push($options_array_labels,$option->getTitle());
        }

        $result = array_diff($recived_labels, $options_array_labels);
        Mage::log("before final", null, 'cartLog.log');
        if (count($result) > 0 or $empty_option == true){
          return array('code' => '406','text' => "Not Acceptable",'description' => "Invalid format is specified in the request.(Only Simple Products)");
        }
        
        foreach ($options_recived as $key => $value) {
          $key = array_search($key, $options_array_key_value);
          Mage::log($key, null, 'cartLog.log');
          $options_array = $options_array + array($key =>  $value);
        }

      }
      else{
        $options_array = null;
      }

      $param = array(
        'product' => $productInstance->getId(),
        'qty' => $product->qty,
        'options' => $options_array
      );

      $request = new Varien_Object();
      $request->setData($param);
      
      $cart->addProduct($productInstance->getId(), $request);
      // Set shipping method
      $quote = $cart->getQuote();
      $shippingAddress = $quote->getShippingAddress();               
      // update session
      $session->setCartWasUpdated(true);
      // save the cart
      // foreach ($quote->getAllItems() as $key => $item) {
      //   if (in_array($item->getSku(), $free_product_sku)) {
      //     $item->setCustomPrice(0);
      //     $item->setOriginalCustomPrice(0);
      //   }
      // }
      $shippingAddress->setShippingMethod('flatrate_flatrate')->save();
      // $quote->collectTotals()->save();
      $cart->save(); 
      return array('code' => '200','text' => "OK sku => ".$product->sku,'description' => "Product added to cart");
    }
    catch (Mage_Core_Exception $e) {
      return array('code' => '404','text' => "Exception sku => ".$product->sku,'description' => $e->getMessage());
    } catch (Exception $e) {
      return array('code' => '404','text' => "Exception sku => ".$product->sku,'description' => $e->getMessage());
      // $error = sprintf('Exception message: %s%sTrace: %s', $e->getMessage(), "\n", $e->getTraceAsString());
    }
  }










































}