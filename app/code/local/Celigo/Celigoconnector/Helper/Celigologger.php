<?php
/*
 *  Helper class to for logging
*/

class Celigo_Celigoconnector_Helper_Celigologger extends Mage_Core_Helper_Abstract {
    #const DEFAULT_LOG_FILE = 'celigo-magento-connector.log';
    protected $isLoggingEnabled;
    const XML_PATH_CONNECTOR_LOG_FILENAME = 'celigoconnector/logsettings/filename';
    const XML_PATH_CONNECTOR_LOG_ENABLED = 'celigoconnector/logsettings/enabled';

    private function log($message, $level, $filename='', $storeId='', $websiteId='' ) {
        //here comes the logic to print logs in seprate files
        if ($message != '' && $this->isCeligoconnectorLoggingEnabled()) {
            $allowedLevel = Mage::helper('celigoconnector')->getConfigValue(self::XML_PATH_CONNECTOR_LOG_ENABLED, $storeId, $websiteId);
            if ($level == '') {
                $level = $allowedLevel;
            }
            //[small level higher rank ()] only print log when allowed level is equal or greater
            if ($allowedLevel < $level) {

                return;
            }
            if($filename == ''){
                throw new Exception('CELIGO_ERROR : Please provide log file name. Attached log:'.$message);
            }
            if($level == -1) {
                $level = Zend_Log::ERR;
            }
            Mage::log($message, $level, $filename);
        }
    }
    private function get_class_info() {
        $trace = debug_backtrace();
        $class = (isset($trace[1]['class']) ? $trace[1]['class'] : NULL);

        for ($i = 1; $i < count($trace); $i++) {
            if (isset($trace[$i]) && isset($trace[$i]['class'])) // is it set?
            if ($class != $trace[$i]['class']) { // is it a different class

                return 'classname="' . $trace[$i]['class'] . '" ' . 'functioname="' . $trace[$i]['function'] . '" ';
            }
        }
    }
    //saving state for created object, for helper class it should still
    private function isCeligoconnectorLoggingEnabled($storeId = '', $websiteId = '') {
        if (!isset($this->isLoggingEnabled)) {
            $this->isLoggingEnabled = false;
            $isenabled = Mage::helper('celigoconnector')->getConfigValue(self::XML_PATH_CONNECTOR_LOG_ENABLED, $storeId, $websiteId);
            if (isset($isenabled) && $isenabled >= 0) {
                $this->isLoggingEnabled = true;
            }
        }

        return $this->isLoggingEnabled;
    }
    public function debug($msg, $filename) {
        $msg = $this->get_class_info() . $msg;
        $this->log($msg, Zend_Log::DEBUG, $filename);
    }
    public function info($msg, $filename) {
        $this->log($msg, Zend_Log::INFO, $filename);
    }
    public function error($msg, $filename) {
        //Log in case no logging is enabled
        $this->log($msg, -1, $filename);
    }
    /**
    Use this function to log the data forcefully
    [Currently all data is logged as Info]
    */
    public function forcelog($msg, $filename){
        Mage::log($msg, Zend_Log::INFO, $filename, true);
    }
}
