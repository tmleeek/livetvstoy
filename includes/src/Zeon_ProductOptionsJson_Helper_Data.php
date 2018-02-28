<?php

/**
 * ProductOptions JSON helper
 *
 * @category    Zeon
 * @package     Zeon_ProductOptionsJson
 * @author      Blake Bauman <blake.bauman@zeonsolutions.com>
 */
class Zeon_ProductOptionsJson_Helper_Data extends Mage_Core_Helper_Abstract
{

    /**
     * Holds attribute/options labels
     *
     * @var array
     */
    protected $_labels = array();

    /**
     * Holds attribute/options values
     *
     * @var array
     */
    protected $_values = array();

    /**
     * Holds label and value keys
     *
     * @var array
     */
    protected $_keys = array(
        'label',
        'value'
    );

    /**
     * Holds options and attribute_info keys
     *
     * @var array
     */
    protected $_arrays = array(
        'options'
        //'attributes_info'
    );

    /**
     * Process the product options and return only the
     * options and attribute_info arrays
     *
     * @param   array $options
     * @return  array Combined array
     */
    public function process(array $options)
    {
        // Unset the previous labels and values
        unset($this->_labels, $this->_values);
        
        foreach ($options as $key => $value) {
            if (in_array($key, $this->_arrays)) {
                $this->_parseArray($value);
            }
        }
        return array_combine($this->_labels, $this->_values);
    }

    /**
     * Parse the array
     *
     * @param  array $array
     */
    private function _parseArray($array)
    {
        $iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($array));

        foreach ($iterator as $key => $value) {
            if (in_array($key, $this->_keys)) {
                if ($key == 'label') {
                    $this->_labels[] = $value;
                } else {
                    $this->_values[] = $value;
                }
            }
        }
    }
}