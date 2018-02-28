<?php

/**
 * Class Gene_Braintree_Helper_Data
 *
 * @author Dave Macaulay <dave@gene.co.uk>
 */
class Gene_Braintree_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Store if the migration has ran in the system config
     */
    const MIGRATION_COMPLETE = 'payment/gene_braintree/migration_ran';

    /**
     * Return all of the possible statuses as an array
     *
     * @return array
     */
    public function getStatusesAsArray()
    {
        return array(
            Braintree_Transaction::AUTHORIZATION_EXPIRED => $this->__('Authorization Expired'),
            Braintree_Transaction::AUTHORIZING => $this->__('Authorizing'),
            Braintree_Transaction::AUTHORIZED => $this->__('Authorized'),
            Braintree_Transaction::GATEWAY_REJECTED => $this->__('Gateway Rejected'),
            Braintree_Transaction::FAILED => $this->__('Failed'),
            Braintree_Transaction::PROCESSOR_DECLINED => $this->__('Processor Declined'),
            Braintree_Transaction::SETTLED => $this->__('Settled'),
            Braintree_Transaction::SETTLING => $this->__('Settling'),
            Braintree_Transaction::SUBMITTED_FOR_SETTLEMENT => $this->__('Submitted For Settlement'),
            Braintree_Transaction::VOIDED => $this->__('Voided'),
            Braintree_Transaction::UNRECOGNIZED => $this->__('Unrecognized'),
            Braintree_Transaction::SETTLEMENT_DECLINED => $this->__('Settlement Declined'),
            Braintree_Transaction::SETTLEMENT_PENDING => $this->__('Settlement Pending')
        );
    }

    /**
     * Force the prices to two decimal places
     * Magento sometimes doesn't return certain totals in the correct format, yet Braintree requires them to always
     * be in two decimal places, thus the need for this function
     *
     * @param $price
     *
     * @return string
     */
    public function formatPrice($price)
    {
        // Suppress errors from formatting the price, as we may have EUR12,00 etc
        return @number_format($price, 2, '.', '');
    }

    /**
     * Convert a Braintree address into a Magento address
     *
     * @param $address
     *
     * @return \Mage_Customer_Model_Address
     */
    public function convertToMagentoAddress($address)
    {
        $addressModel = Mage::getModel('customer/address');

        $addressModel->addData(array(
            'firstname' => $address->firstName,
            'lastname' => $address->lastName,
            'street' => $address->streetAddress . (isset($address->extendedAddress) ? "\n" . $address->extendedAddress : ''),
            'city' => $address->locality,
            'postcode' => $address->postalCode,
            'country' => $address->countryCodeAlpha2
        ));

        if (isset($address->region)) {
            $addressModel->setData('region_code', $address->region);
        }

        if (isset($address->company)) {
            $addressModel->setData('company', $address->company);
        }

        return $addressModel;
    }

    /**
     * Convert a Magento address into a Braintree address
     *
     * @param $address
     *
     * @return array
     */
    public function convertToBraintreeAddress($address)
    {
        if (is_object($address)) {
            // Build up the initial array
            $return = array(
                'firstName'         => $address->getFirstname(),
                'lastName'          => $address->getLastname(),
                'streetAddress'     => $address->getStreet1(),
                'locality'          => $address->getCity(),
                'postalCode'        => $address->getPostcode(),
                'countryCodeAlpha2' => $address->getCountry()
            );

            // Any extended address?
            if ($address->getStreet2()) {
                $return['extendedAddress'] = $address->getStreet2();
            }

            // Region
            if ($address->getRegion()) {
                $return['region'] = $address->getRegionCode();
            }

            // Check to see if we have a company
            if ($address->getCompany()) {
                $return['company'] = $address->getCompany();
            }

            return $return;
        }
    }

    /**
     * Can we update information in Kount for a payment?
     *
     * kount_ens_update is set when an ENS update is received from Kount
     *
     * @return bool
     */
    public function canUpdateKount()
    {
        return !Mage::registry('kount_ens_update')
            && Mage::getStoreConfig('payment/gene_braintree_creditcard/kount_merchant_id')
            && Mage::getStoreConfig('payment/gene_braintree_creditcard/kount_api_key');
    }

    /**
     * Should the system run the migration tool?
     *
     * @return bool
     */
    public function shouldRunMigration()
    {
        return Mage::helper('core')->isModuleEnabled('Braintree_Payments')
            && !Mage::getStoreConfigFlag(self::MIGRATION_COMPLETE)
            && !Mage::getStoreConfig('payment/gene_braintree/merchant_id')
            && !Mage::getStoreConfig('payment/gene_braintree/sandbox_merchant_id');
    }
}