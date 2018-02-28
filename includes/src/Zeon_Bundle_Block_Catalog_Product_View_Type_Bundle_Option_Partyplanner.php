<?php
/**
 * Bundle option dropdown type renderer
 *
 * @category   Zeon
 * @package    Zeon_Bundle
 * @author     Suhas Dhoke <suhas.dhoke@zeonsolutions.com>
 */
class Zeon_Bundle_Block_Catalog_Product_View_Type_Bundle_Option_Partyplanner
    extends Mage_Bundle_Block_Catalog_Product_View_Type_Bundle_Option
{
    /**
     * Set template
     *
     * @return void
     */
    protected function _construct()
    {
        $this->setTemplate('bundle/catalog/product/view/type/bundle/option/partyplanner.phtml');
    }


    public function getSelectionTitle($_selection, $includeContainer = true)
    {
        $price = $this->getProduct()->getPriceModel()->getSelectionPreFinalPrice($this->getProduct(), $_selection);
        $this->setFormatProduct($_selection);
        $priceTitle = $_selection->getSelectionQty() * 1 . ' x ' . $this->escapeHtml($_selection->getName());


        return $priceTitle;
    }

    public function getSelectionPrice($_selection, $includeContainer = true)
    {
        $price = $this->getProduct()->getPriceModel()->getSelectionPreFinalPrice($this->getProduct(), $_selection);
        $priceTitle = $this->escapeHtml($_selection->getName());
        $priceTitle .= ' &nbsp; ' . ($includeContainer ? '<span class="price-notice">' : '')
            .  $this->formatPriceString($price, $includeContainer)
            . ($includeContainer ? '</span>' : '');

        return $priceTitle;
    }
}
