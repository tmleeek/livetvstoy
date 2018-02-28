<?php
/**
 * Catalog product api
 */
require_once(Mage::getBaseDir('app') . "/code/core/Mage/Catalog/Model/Product/Api.php");
class Celigo_Connector_Model_Product_Api extends Mage_Catalog_Model_Product_Api
{
    /**
     * Retrieve product info
     *
     * @param int|string $productId
     * @param string|int $store
     * @param array $attributes
     * @return array
     */
    public function info($productId, $store = null, $attributes = null, $identifierType = null)
    {
        $product = $this->_getProduct($productId, $store, $identifierType);

        if (!$product->getId()) {
            $this->_fault('not_exists');
        }

        $result = array( // Basic product data
            'product_id' => $product->getId(),
            'sku'        => $product->getSku(),
            'set'        => $product->getAttributeSetId(),
            'type'       => $product->getTypeId(),
            'categories' => $product->getCategoryIds(),
            'websites'   => $product->getWebsiteIds()
        );

        foreach ($product->getTypeInstance(true)->getEditableAttributes($product) as $attribute) {
            if ($this->_isAllowedAttribute($attribute, $attributes)) {
                $result[$attribute->getAttributeCode()] = $product->getData($attribute->getAttributeCode());
            }
        }

		##################### START :: Below Code for Bundle items info of the bundle product Code by Celigo #####################
		/*
		 * Check for if the product is Bundle product passed through API Call. If yes return the bundle items ID and SKU as response. If not do nothing.
		 */
		$type = $product->getTypeId();
		if ($type == 'bundle') {
			$bundle_items = array();
			$childItems = array();
			try {
				$childItems = $product->getTypeInstance(true)->getChildrenIds($product->getId(), false);
				if (is_array($childItems) && count($childItems) > 0) {
					foreach ($childItems as $childItem) {
						if (is_array($childItem) && count($childItem) > 0) {
							foreach ($childItem as $key => $value) {
								$bundle_item = Mage::getModel('catalog/product')->load($key);
								$bundle_items[] = array("id" => $bundle_item->getId(), "sku" => $bundle_item->getSku());
							}
						}
					}
				}
				$result['bundle_items'] = $bundle_items;
			} catch (Exception $e) {
				Mage::log($e->getMessage());
			}
		}
		##################### END :: Above Code for Bundle items info of the bundle product Code by Celigo #####################

        return $result;
    }


    public function create($type, $set, $sku, $productData, $store = null)
    {
        if (!$type || !$set || !$sku) {
            $this->_fault('data_invalid');
        }

		if (!in_array($type, array_keys(Mage::getModel('catalog/product_type')->getOptionArray()))) {
            return 'Product type '.$type.' does not exists.';
        }

        $attributeSet = Mage::getModel('eav/entity_attribute_set')->load($set);
			if (is_null($attributeSet->getId())) {
				return 'Attribute Set Id '. $set.' not exists';
			}
			if (Mage::getModel('catalog/product')->getResource()->getTypeId() != $attributeSet->getEntityTypeId()) {
				return 'Attribute Set Id '. $set.' is not valid';
        }

        /** @var $product Mage_Catalog_Model_Product */
        $product = Mage::getModel('catalog/product');
        $product->setStoreId($this->_getStoreId($store))
            ->setAttributeSetId($set)
            ->setTypeId($type)
            ->setSku($sku);

        if (isset($productData['website_ids']) && is_array($productData['website_ids'])) {
            $product->setWebsiteIds($productData['website_ids']);
        }

        foreach ($product->getTypeInstance(true)->getEditableAttributes($product) as $attribute) {
            if ($this->_isAllowedAttribute($attribute)
                && isset($productData[$attribute->getAttributeCode()])) {
                $product->setData(
                    $attribute->getAttributeCode(),
                    $productData[$attribute->getAttributeCode()]
                );
            }
        }

        $this->_prepareDataForSave($product, $productData);

		##################### START :: Below Code for Configurable Products Code by Celigo #####################
		/*
		 * Check for configurable products array passed through API Call
		 */
		if ($type == 'configurable') {
			if (isset($productData['configurable_products_data']) && is_array($productData['configurable_products_data'])) {

				foreach ($productData['configurable_products_data'] as $key=>$prodinfo) {
					if (!is_array($prodinfo)) { unset($productData['configurable_products_data'][$key]); $productData['configurable_products_data'][$prodinfo] = array(); }
				}
				$product->setConfigurableProductsData($productData['configurable_products_data']);
			}

			if (isset($productData['configurable_attributes_data']) && is_array($productData['configurable_attributes_data'])) {
				foreach ($productData['configurable_attributes_data'] as $key => $data) {
					//Check to see if these values exist, otherwise try and populate from existing values
					$data['label'] 			=	(!empty($data['label'])) 			? $data['label'] 			: $product->getResource()->getAttribute($data['attribute_code'])->getStoreLabel();
					$data['frontend_label'] =	(!empty($data['frontend_label'])) 	? $data['frontend_label'] 	: $product->getResource()->getAttribute($data['attribute_code'])->getFrontendLabel();
					$productData['configurable_attributes_data'][$key] = $data;
				}
				$product->setConfigurableAttributesData($productData['configurable_attributes_data']);
				$product->setCanSaveConfigurableAttributes(1);
			}
		}
		##################### End :: Above Code for Configurable Products Code by Celigo #####################

		##################### START :: Below Code for Bundle Products Code by Celigo #####################
		/*
		 * Check for configurable products array passed through API Call
		 */
		if ($type == 'bundle') {

			if (isset($productData['bundle_options']) && is_array($productData['bundle_options'])) {

				$bundle_selections = array();
				foreach ($productData['bundle_options'] as $optionId => $otpionvalues) {
					if (!isset($otpionvalues['type']))
						$productData['bundle_options'][$optionId]['type'] = 'select';
					if (!isset($otpionvalues['required']))
						$productData['bundle_options'][$optionId]['required'] = 1;
					if (isset($otpionvalues['products'])) {
						if( is_array($otpionvalues['products'])) {
							$bundle_selections[$optionId] = $otpionvalues['products'];
						}
						unset($otpionvalues['products']);
					}
				}
				$product->setBundleOptionsData($productData['bundle_options']);

				if (is_array($bundle_selections) && count($bundle_selections) > 0 
						&& !isset($productData['bundle_selections']) && !is_array($productData['bundle_selections'])) {
					$productData['bundle_selections'] = $bundle_selections;
				}
			}

			if (isset($productData['bundle_selections']) && is_array($productData['bundle_selections'])) {

				$allowedTypes = Mage::helper('bundle')->getAllowedSelectionTypes();
				foreach ($productData['bundle_selections'] as $optionId => $products) {
					$productsCount = 0;
					foreach ($products as $indexId => $_product) {
						if (isset($_product['product_id']) && $_product['product_id'] != '') {
							$_item = Mage::getModel('catalog/product')->load($_product['product_id']);
							if (!in_array($_item->getTypeId(), $allowedTypes)) {
								$errorMessage = "Product Id: " . $_product['product_id'] . " product type (" . $_item->getTypeId() . " is not allowed";
								$this->_fault('data_invalid', $errorMessage);
							} elseif ($_item->getStatus() != 1) {
								$errorMessage = "Product Id: ". $_product['product_id']. " is Disabled";
								$this->_fault('data_invalid', $errorMessage);
							} elseif ($_item->getHasOptions()) {
								$errorMessage = "Product Id: " . $_product['product_id'] . " can not be added as Bundle item as it has custom options";
								$this->_fault('data_invalid', $errorMessage);
							}

							if (!isset($productData['bundle_selections'][$optionId][$indexId]['selection_qty']))
								$productData['bundle_selections'][$optionId][$indexId]['selection_qty'] = 1;
							if ($productsCount == 0 && !isset($productData['bundle_selections'][$optionId][$indexId]['is_default']))
								$productData['bundle_selections'][$optionId][$indexId]['is_default'] = 1;
							$productsCount++;
						}
					}
				}

				Mage::register('product', $product);
				$product->setBundleSelectionsData($productData['bundle_selections']);
				$product->setCanSaveBundleSelections(true);
			}
		}
		##################### End :: Above Code for Bundle Products Code by Celigo #####################

        try {
            /**
             * @todo implement full validation process with errors returning which are ignoring now
             * @todo see Mage_Catalog_Model_Product::validate()
             */
            if (is_array($errors = $product->validate())) {
                $strErrors = array();
                foreach ($errors as $code=>$error) {
                    $strErrors[] = ($error === true)? Mage::helper('catalog')->__('Attribute "%s" is invalid.', $code) : $error;
                }
                $this->_fault('data_invalid', implode("\n", $strErrors));
            }

            $product->save();
        } catch (Mage_Core_Exception $e) {
            $this->_fault('data_invalid', $e->getMessage());
        }

        return $product->getId();
    }



    /**
     * Update product data
     *
     * @param int|string $productId
     * @param array $productData
     * @param string|int $store
     * @return boolean
     */
    public function update($productId, $productData, $store = null, $identifierType = null)
    {
        $product = $this->_getProduct($productId, $store, $identifierType);

        if (!$product->getId()) {
            $this->_fault('not_exists');
        }

        if (isset($productData['website_ids']) && is_array($productData['website_ids'])) {
            $product->setWebsiteIds($productData['website_ids']);
        }

        foreach ($product->getTypeInstance(true)->getEditableAttributes($product) as $attribute) {
            if ($this->_isAllowedAttribute($attribute)
                && isset($productData[$attribute->getAttributeCode()])) {
                $product->setData(
                    $attribute->getAttributeCode(),
                    $productData[$attribute->getAttributeCode()]
                );
            }
        }

        $this->_prepareDataForSave($product, $productData);

        //$product = $this->_getProduct($productId, $store, $identifierType);

        //$this->_prepareDataForSave($product, $productData);
		##################### START :: Below Code for Configurable Products Code by Celigo #####################
		/*
		 * Check for configurable products array passed through API Call
		 */
		if ($product->getTypeId() == 'configurable') {
			if (isset($productData['configurable_products_data']) && is_array($productData['configurable_products_data'])) {
			
				$allChildItems = $this->getAllAvailableChildProducts($product);
				$allChildItemIds = array();
				foreach ($allChildItems as $childItem) {
					$allChildItemIds[] = $childItem->getId();
				}

				foreach ($productData['configurable_products_data'] as $key=>$prodinfo) {
				
					if (!is_array($prodinfo)) { 
						if (!in_array($prodinfo, $allChildItemIds)) {
							$this->_fault('data_invalid', "Invalid associated product id: " . $prodinfo);
						}
						unset($productData['configurable_products_data'][$key]); 
						$productData['configurable_products_data'][$prodinfo] = array(); 
					} else {
						if(!in_array($key, $allChildItemIds)) {
							$this->_fault('data_invalid', "Invalid associated product id: " . $key);
						}
					}
				}
				
				$childrenIds = $product->getTypeInstance(true)->getChildrenIds($product->getId());
				foreach ($childrenIds[0] as $prodinfo) {
					if (!is_array($prodinfo)) { 
						$productData['configurable_products_data'][$prodinfo] = array();
					}
				}
				
				$product->setConfigurableProductsData($productData['configurable_products_data']);
			}

			if (isset($productData['configurable_attributes_data']) && is_array($productData['configurable_attributes_data'])) {
			
				$allAttributesFromSet = Mage::getModel('catalog/product_attribute_api')->items($product->getAttributeSetId());
				$validConfigurableAttributes = array();
				foreach ($allAttributesFromSet as $attribue) {
					if ($attribue['scope'] == 'global') {
						$validConfigurableAttributes[] = $attribue['attribute_id'];
					}
				}

				$configurable_attributes = $product->getTypeInstance(true)->getUsedProductAttributeIds($product);
				$configurable_attributes_array = $product->getTypeInstance(true)->getConfigurableAttributesAsArray($product);
				foreach ($productData['configurable_attributes_data'] as $key => $data) {
					if (!in_array($data['attribute_id'], $configurable_attributes)) {			
						$attribute = $product->getResource()->getAttribute($data['attribute_code']);
						if ($product->getTypeInstance(true)->canUseAttribute($attribute) && in_array($data['attribute_id'], $validConfigurableAttributes)) { 
							//Check to see if these values exist, otherwise try and populate from existing values
							$data['label'] 			=	(!empty($data['label'])) 			? $data['label'] 			: $product->getResource()->getAttribute($data['attribute_code'])->getStoreLabel();
							$data['frontend_label'] =	(!empty($data['frontend_label'])) 	? $data['frontend_label'] 	: $product->getResource()->getAttribute($data['attribute_code'])->getFrontendLabel();
							$productData['configurable_attributes_data'][$key] = $data;
						} else { 
							$this->_fault('data_invalid', $data['attribute_code'] . " is invalid attribute");
						}
					} else {
						unset($productData['configurable_attributes_data'][$key]);
					}
				}
				
				$productData['configurable_attributes_data'] = array_merge($configurable_attributes_array, $productData['configurable_attributes_data']);

				$product->setConfigurableAttributesData($productData['configurable_attributes_data']);
				$product->setCanSaveConfigurableAttributes(1);
			}
		}
		##################### End :: Above Code for Configurable Products Code by Celigo #####################
		##################### START :: Below Code for Bundle Products Code by Celigo #####################
		/*
		 * Check for configurable products array passed through API Call
		 */
		if ($product->getTypeId() == 'bundle') {
			if (isset($productData['bundle_options']) && is_array($productData['bundle_options'])) {

				$optionCollection = $product->getTypeInstance(true)->getOptionsCollection($product);
				//$selectionCollection = $product->getTypeInstance(true)->getSelectionsCollection($product->getTypeInstance(true)->getOptionsIds($product),$product);
				foreach ($optionCollection as $option){
					$option->delete();
				}

				$bundle_selections = array();
				foreach ($productData['bundle_options'] as $optionId => $otpionvalues) {
					if (!isset($otpionvalues['type']))
						$productData['bundle_options'][$optionId]['type'] = 'select';
					if (!isset($otpionvalues['required']))
						$productData['bundle_options'][$optionId]['required'] = 1;
					if (isset($otpionvalues['products'])) {
						if (is_array($otpionvalues['products'])) {
							$bundle_selections[$optionId] = $otpionvalues['products'];
						}
						unset($otpionvalues['products']);
					}
				}
				$product->setBundleOptionsData($productData['bundle_options']);

				if (is_array($bundle_selections) && count($bundle_selections) > 0 && !isset($productData['bundle_selections']) && !is_array($productData['bundle_selections'])) {
					$productData['bundle_selections'] = $bundle_selections;
				}
			}

			if (isset($productData['bundle_selections']) && is_array($productData['bundle_selections'])) {

				$allowedTypes = Mage::helper('bundle')->getAllowedSelectionTypes();
				foreach ($productData['bundle_selections'] as $optionId => $products) {
					$productsCount = 0;
					foreach ($products as $indexId => $_product) {
						if (isset($_product['product_id']) && $_product['product_id'] != '') {
							$_item = Mage::getModel('catalog/product')->load($_product['product_id']);
							if (!in_array($_item->getTypeId(), $allowedTypes)) {
								$errorMessage = "Product Id: " . $_product['product_id'] . " product type (" . $_item->getTypeId() . " is not allowed";
								$this->_fault('data_invalid', $errorMessage);
							} elseif ($_item->getStatus() != 1) {
								$errorMessage = "Product Id: " . $_product['product_id'] . " is Disabled";
								$this->_fault('data_invalid', $errorMessage);
							} elseif ($_item->getHasOptions()) {
								$errorMessage = "Product Id: " . $_product['product_id'] . " can not be added as Bundle item as it has custom options";
								$this->_fault('data_invalid', $errorMessage);
							}


							if (!isset($productData['bundle_selections'][$optionId][$indexId]['selection_qty']))
								$productData['bundle_selections'][$optionId][$indexId]['selection_qty'] = 1;
							if ($productsCount == 0 && !isset($productData['bundle_selections'][$optionId][$indexId]['is_default']))
								$productData['bundle_selections'][$optionId][$indexId]['is_default'] = 1;
							$productsCount++;
						}
					}
				}

				Mage::register('product', $product);
				$product->setBundleSelectionsData($productData['bundle_selections']);

				if ($product->getPriceType() == '0' && !$product->getOptionsReadonly()) {
					$product->setCanSaveCustomOptions(true);
					if ($customOptions = $product->getProductOptions()) {
						foreach (array_keys($customOptions) as $key) {
							$customOptions[$key]['is_delete'] = 1;
						}
						$product->setProductOptions($customOptions);
					}
				}


				$product->setCanSaveBundleSelections(true);
			}
		}
		##################### End :: Above Code for Bundle Products Code by Celigo #####################
        try {
            /**
             * @todo implement full validation process with errors returning which are ignoring now
             * @todo see Mage_Catalog_Model_Product::validate()
             */
            if (is_array($errors = $product->validate())) {
                $strErrors = array();
                foreach ($errors as $code=>$error) {
                    $strErrors[] = ($error === true)? Mage::helper('catalog')->__('Value for "%s" is invalid.', $code) : Mage::helper('catalog')->__('Value for "%s" is invalid: %s', $code, $error);
                }
                $this->_fault('data_invalid', implode("\n", $strErrors));
            }

            $product->save();
        } catch (Mage_Core_Exception $e) {
            $this->_fault('data_invalid', $e->getMessage());
        }

        return true;
    }

    private function getAllAvailableChildProducts($product)
    {
        $allowProductTypes = array();
        //foreach (Mage::helper('catalog/product_configuration')->getConfigurableAllowedTypes() as $type) { // This is working for 1.6.1.0 and higher versions only
		foreach (Mage::getConfig()->getNode('global/catalog/product/type/configurable/allow_product_types')->children() as $type) {
            $allowProductTypes[] = $type->getName();
        }

        $collection = $product->getCollection()
            ->addAttributeToSelect('name')
            ->addAttributeToSelect('sku')
            ->addAttributeToSelect('attribute_set_id')
            ->addAttributeToSelect('type_id')
            ->addAttributeToSelect('price')
            ->addFieldToFilter('attribute_set_id',$product->getAttributeSetId())
            ->addFieldToFilter('type_id', $allowProductTypes)
            ->addFilterByRequiredOptions()
            ->joinAttribute('name', 'catalog_product/name', 'entity_id', null, 'inner');

        if (Mage::helper('catalog')->isModuleEnabled('Mage_CatalogInventory')) {
            Mage::getModel('cataloginventory/stock_item')->addCatalogInventoryToProductCollection($collection);
        }

        foreach ($product->getTypeInstance(true)->getUsedProductAttributes($product) as $attribute) {
            $collection->addAttributeToSelect($attribute->getAttributeCode());
            $collection->addAttributeToFilter($attribute->getAttributeCode(), array('notnull'=>1));
        }

		return $collection;
    }
}