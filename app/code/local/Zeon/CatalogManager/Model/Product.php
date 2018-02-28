<?php
/**
 * Created by PhpStorm.
 * User: neeraj.raoot
 * Date: 7/21/14
 * Time: 1:08 PM
 * Cron for updating
 */
class Zeon_CatalogManager_Model_Product
{
    public function setZeroPriceDisable()
    {
        try {
            $toEmail=Mage::getStoreConfig('zeon_catalogmanager/cron_zero_price_disable/to_email');
            $toName=Mage::getStoreConfig('zeon_catalogmanager/cron_zero_price_disable/to_name');
            $fromEmail=Mage::getStoreConfig('trans_email/ident_general/email');
            $fromName=Mage::getStoreConfig('trans_email/ident_general/name');

            $products=Mage::getSingleton('catalog/product')->getCollection()
                ->addAttributeToFilter('type_id', array('nin' => array('configurable','grouped','bundle')))
                ->addAttributeToSelect('price')
                ->addAttributeToSelect('name');

            $disabledProductsCount=0;
            $productsData=array();
            $productIds="";
            foreach ($products as $product) {
                $statusDefault=$product->getStatus();
                $price=$product->getPrice();
                if ($price!=0) {
                    continue;
                }
                $productIds.=$product->getId().",";
                $productsData[$disabledProductsCount]['id']=$product->getId();
                $productsData[$disabledProductsCount]['name']=$product->getName();
                $productsData[$disabledProductsCount]['sku']=$product->getSku();
                $disabledProductsCount++;
            }
            if ($disabledProductsCount>0) {

                $disableVal=Mage_Catalog_Model_Product_Status::STATUS_DISABLED;

                $productIds=rtrim($productIds, ",");
                $coreResource = Mage::getSingleton('core/resource');
                $write = $coreResource->getConnection('core_write');
                $statusModel = Mage::getSingleton('eav/entity_attribute')->loadByCode('catalog_product', 'status');
                $statusId=$statusModel->getId();
                $tableName=$coreResource->getTableName('catalog_product_entity_'.$statusModel->getBackendType());
                $query="update $tableName set value='$disableVal'
                    where attribute_id='$statusId' and entity_id in ($productIds)";

                $write->query($query);
                $this->sendAlert($productsData, $toEmail, $toName, $fromEmail, $fromName);
            }
        } catch (Mage_Core_Exception $e) {
            Mage::log('Zero Price Disable :: '.$e->getMessage(), null, 'site_errors.log');
        }
    }

    public function sendAlert($productsData, $toEmail, $toName, $fromEmail, $fromName)
    {
        $subject=Mage::helper('zeon_catalogmanager')->__('Products Disabled Due To Zero Price ').date('F d, Y');

        $emailItems='';
        for ($i=0;$i<count($productsData);$i++) {
            $emailItems.='<tr>
                <td style="border: 1px solid gray;">'.$productsData[$i]['id'].'</td>
                <td style="border: 1px solid gray;">'.$productsData[$i]['sku'].'</td>
                <td style="border: 1px solid gray;">'.$productsData[$i]['name'].'</td>
            </tr>';
        }

        $isCustomTemplateSet=Mage::getStoreConfig('zeon_catalogmanager/cron_zero_price_disable/templ_email');
        $mailTemplate = Mage::getModel('core/email_template');
        $emailTemplateVariables['items'] = $emailItems;
        if ($isCustomTemplateSet==false) {
            $mailTemplate->loadDefault('zeon_catalogmanager_cron_zero_price_disable_templ_email');
            $processedTemplate = $mailTemplate->getProcessedTemplate($emailTemplateVariables);
            $mail = Mage::getSingleton('core/email')
            ->setToName($toName)
            ->setToEmail($toEmail)
            ->setBody(
                $processedTemplate
            )
            ->setSubject($subject)
            ->setFromEmail($fromEmail)
            ->setFromName($fromName)
            ->setType('html');
            try{
                $mail->send();
            }
            catch(Exception $error)
            {
                Mage::getSingleton('core/session')->addError($error->getMessage());
                return false;
            }
        } else {
            $sender = array('name' => $fromName,
                'email' => $fromEmail);

            $translate = Mage::getSingleton('core/translate');
            $translate->setTranslateInline(false);
            $mailTemplate->setTemplateSubject($subject);
            $mailTemplate->setDesignConfig(
                array(
                    'area' => 'frontend'
                )
            )
                ->sendTransactional(
                    Mage::getStoreConfig('zeon_catalogmanager/cron_zero_price_disable/templ_email'),
                    $sender,
                    $toEmail,
                    $toName,
                    $emailTemplateVariables
                );
            $translate->setTranslateInline(true);
        }
    }
}