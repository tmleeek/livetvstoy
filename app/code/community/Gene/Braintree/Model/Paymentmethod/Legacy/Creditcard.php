<?php

/**
 * Class Gene_Braintree_Model_Paymentmethod_Legacy_Creditcard
 *
 * @author Dave Macaulay <dave@gene.co.uk>
 */
class Gene_Braintree_Model_Paymentmethod_Legacy_Creditcard extends Gene_Braintree_Model_Paymentmethod_Creditcard
{
    /**
     * Set the code
     *
     * @var string
     */
    protected $_code = 'braintree';

    /**
     * This method is never available and only used by the RocketWeb orders
     *
     * @param null $quote
     *
     * @return bool
     */
    public function isAvailable($quote = null)
    {
        return false;
    }
}