<?php
class Zeon_Personalize_IndexController extends Mage_Core_Controller_Front_Action
{
    /**
     * Initialize product instance from request data
     *
     * @param $productId Integer
     * @return Mage_Catalog_Model_Product || false
     */
    protected function _initProduct($productId)
    {
        if ($productId) {
            $product = Mage::getModel('catalog/product')
                ->setStoreId(Mage::app()->getStore()->getId())
                ->load($productId);
            if ($product->getId()) {
                return $product;
            }
        }
        return false;
    }

    public function indexAction()
    {
        $this->loadLayout()->renderLayout();

        //Check if custumer is logged in anf if yes get the Custumer Id
        //Else Set is Guset Flag as true
        if (Mage::getSingleton('customer/session')->isLoggedIn()) {
            $customerData = Mage::getSingleton('customer/session')
            ->getCustomer();
            $customer = $customerData->getId();
            $flag="false";
        } else {
            $customer = "Guest";
            $flag="true";
        }

        // Get The Posts or Get Variable of the form and set
        //The product Id in personalized Page.
        //$posts = Mage::app()->getRequest()->getPost();
        $posts = $this->getRequest()->getParams();

        if (isset($posts['designId']) && isset($posts['sku'])) {
            /*
             * Exception Handling Try Catch Block
             * */
            try{
                // Get the session-id.
                $sessionId = Mage::getSingleton('customer/session')->getEncryptedSessionId();

                // Load the product by SKU.
                //$product = Mage::getModel('catalog/product')->loadByAttribute('sku', $posts['sku']);
                $product = $this->_initProduct($posts['product_id']);

                if (isset($posts['personalize_id']) && $posts['personalize_id'] != '' ) {
                    //Do Something
                    $id = $posts['personalize_id'];
                    $personalizedValues =  array(
                        'design_id' => $posts['designId'],
                        'design_params' => '',
                        'product_id' => $posts['product_id'],
                        'customer_id' => $posts['userId'],
                        'is_guest' => $flag,
                        'order_id' => '12',
                        'quote_id' => '0',
                        'sku' =>  $posts['sku'],
                        'productcode' =>  $posts['productCode'],
                        'session_id' => $sessionId,
                    );

                    $model = Mage::getSingleton('personalize/personalize')->load($id)->addData($personalizedValues);
                    $model->setId($id)->save();
                } else {
                    $qty = $posts['qty']; // Replace qty with your qty
                    $personalized = Mage::getSingleton('personalize/personalize'); //->load(1)->getData();
                    //Get the product details
                    //form the model by Id.
                    /*if ( isset($posts['design_id']) && isset($posts['item_id']) ) {
                        $item = Mage::getSingleton('checkout/session')->getQuote()->getItemById($posts['item_id']);
                        $item->setQty($qty)->save();
                    }*/

                    $params = array();

                    // Check for configurable product.
                    if (isset($posts['super_attribute'])) {
                        $superAttributes = json_decode($posts['super_attribute'], true);
                        foreach ($superAttributes as $key => $val) {
                            $key = str_replace(array('super_attribute', '[', ']'), '', $key);
                            $params['super_attribute'][$key] = $val;
                        }
                    }
                    // Check for product options.
                    if (isset($posts['options'])) {
                        $customOptions = json_decode($posts['options'], true);
                        foreach ($customOptions as $key => $val) {
                            $params['options'][str_replace(array('options', '[', ']'), '', $key)] = $val;
                        }
                    }

                    // Set the qty.
                    $params['qty'] = $posts['qty'];
                    $params['product'] = $params['id'] = $product->getId();
                    //set child product data for configurable products
                    if (isset($posts['child_product'])) {
                        $params['child_product'] = $posts['child_product'];

                        // If child_product is set then update the SKU value.
                        $_resource = Mage::getSingleton('catalog/product')->getResource();
                        $_storeId  = Mage::app()->getStore();
                        $posts['sku'] = $_resource->getAttributeRawValue($posts['child_product'], 'sku', $_storeId);
                    }

                    //$cart = Mage::getSingleton('checkout/cart');//Load Cart model
                    $cart = Mage::helper('checkout/cart')->getCart();
                    //$cart->init();//Initialize Cart model
                    $cart->addProduct($product, $params);//Add the product details to the cart.
                    $cart->save();//Save the required details inn the cart table.

                    $itemIds = array();
                    foreach ($cart->getQuote()->getAllItems() as $item) {
                        $itemIds[] = $item->getId();
                    }
                    sort($itemIds);

                    $quote = Mage::getSingleton('checkout/session')->setCartWasUpdated(true);
                    //Update the checkout Session of the cart

                    $personalizedValues =  array(
                        'design_id' => $posts['designId'],
                        'design_params' => $posts['attributes'],
                        'thumbnail_paths' => $posts['savedDesigns'],
                        'product_id' => $posts['product_id'],
                        'customer_id' => $posts['userId'],
                        'is_guest' => $flag,
                        'order_id' => '12',
                        'quote_id' => $cart->getQuote()->getId(),
                        'item_id'  => $itemIds[count($itemIds) - 1],
                        'sku' =>  $posts['sku'],
                        'productcode' =>  $posts['productCode'],
                        'session_id' => $sessionId,
                    );

                    $personalized->setData($personalizedValues)->save();
                }//The Else Part ends
            } catch(Exception $e){
                //Mage::log(print_r($e->getMessage(), 1), 1, 'personalized.log', 1);
                Mage::log(print_r($e->getTrace(), 1), 1, 'personalized.log', 1);
                return $e->getMessage();
            }
        }
    }
}