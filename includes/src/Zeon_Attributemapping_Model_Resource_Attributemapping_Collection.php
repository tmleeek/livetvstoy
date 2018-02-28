<?php
class Zeon_Attributemapping_Model_Resource_Attributemapping_Collection
    extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    public function _construct()
    {
        $this->_init('zeon_attributemapping/attributemapping');
    }

    /**
     *
     * delete attribute option url data
     * @param int $storeId
     * @param int $optionId
     */
    public function attributeDeleteData($storeId, $optionId)
    {
        $tablename = $this->getTable(
            array('zeon_attributemapping/attributemapping', $storeId)
        );
        $connectionWrite = $this->getConnection('core_write');
        $condition = array(
            $connectionWrite->quoteInto('option_id=?', $optionId)
        );
        if ($connectionWrite->delete($tablename, $condition)) {
            return true;
        }
        return false;

    }

   /**
     *
     * query for enable characters listing
     */
    public function getAttributeData($attributeId, $store,
        $condition='', $debug=0)
    {
        $attributeDataTable = $this->getTable(
            array('zeon_attributemapping/attributemapping', $store)
        );
        $urlRedirect = $this->getTable('enterprise_urlrewrite/redirect');
        $urlConnect = $this->getTable('enterprise_urlrewrite/redirect_rewrite');
        $urlRewrite = $this->getTable('enterprise_urlrewrite/url_rewrite');
        $optionTable = $this->getTable('eav/attribute_option_value');
        // get attribute options data with all details
        $getAttributeData = 'SELECT att_table.*,'
            . ' url_rewrite.request_path,'
            . 'IF (opt1.value_id > 0, opt1.value, opt0.value )'
            . ' AS `value`'
            . ' FROM `'.$attributeDataTable.'` AS att_table'
            . ' LEFT JOIN `'.$urlRedirect.'` AS url_redirect'
            . ' ON url_redirect.options = att_table.mapping_id'
            . ' AND store_id = \''.$store.'\''
            . ' INNER JOIN `'.$urlConnect.'` AS url_connect'
            . ' ON url_redirect.redirect_id = url_connect.redirect_id'
            . ' INNER JOIN `'.$urlRewrite.'` AS url_rewrite'
            . ' ON url_rewrite.url_rewrite_id ='
            . ' url_connect.url_rewrite_id'
            . ' LEFT JOIN `'.$optionTable.'` AS opt0'
            . ' ON opt0.option_id = att_table.option_id'
            . ' AND opt0.store_id = 0'
            . ' LEFT JOIN `'.$optionTable.'` AS opt1'
            . ' ON opt1.option_id = att_table.option_id'
            . ' AND opt1.store_id = \''.$store.'\''
            . ' WHERE att_table.attribute_id = \''.$attributeId.'\''
            . ' AND att_table.option_status = \'1\' '
            . $condition. ';';

            if ($debug) {
                echo $getAttributeData; exit;
            }


        return $getAttributeData;
    }
}