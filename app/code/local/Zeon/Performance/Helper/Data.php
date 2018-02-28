<?php
/**
 * Performance Helper
 *
 * @category    Zeon
 * @package     Zeon_Paymtric
 * @copyright   Zeon Solutions. (http://www.zeonsolutions.com)
 * @author      Mukesh Patel <mukesh.patel@zeonsolutions.com>
 * @since       Tuesday 18 Jan, 2014
 */
class Zeon_Performance_Helper_Data extends Mage_Core_Helper_Data
{

    /**
     * Require Cusomter to enter CVV number, yes or no
     *
     * @return boolean
     */
    public function getProfiler()
    {

        return Mage::getStoreConfig('dev/debug/profiler');
    }

    public function getDebug()
    {

        return Mage::getStoreConfig('zeon_performance/global/debug');
    }

    public function getPerformance()
    {

        return Mage::getStoreConfig('zeon_performance/global/enabled');
    }

    public function getJsMinify()
    {

        return Mage::getStoreConfig('zeon_performance/global/minijs');
    }

    public function getCssMinify()
    {

        return Mage::getStoreConfig('zeon_performance/global/minicss');
    }

    public function mergeFiles(array $srcFiles, $targetFile = false,
                               $mustMerge = false, $beforeMergeCallback = null,
                               $extensionsFilter = array())
    {
        try {
            // check whether merger is required
            $shouldMerge = $mustMerge || !$targetFile;
            if (!$shouldMerge) {
                if (!file_exists($targetFile)) {
                    $shouldMerge = true;
                } else {
                    $targetMtime = filemtime($targetFile);
                    foreach ($srcFiles as $file) {
                        if (!file_exists($file) || @filemtime($file) > $targetMtime) {
                            $shouldMerge = true;
                            break;
                        }
                    }
                }
            }

            // merge contents into the file
            if ($shouldMerge) {
                if ($targetFile && !is_writeable(dirname($targetFile))) {
                    // no translation intentionally
                    throw new Exception(sprintf('Path %s is not writeable.', dirname($targetFile)));
                }

                // filter by extensions
                if ($extensionsFilter) {
                    if (!is_array($extensionsFilter)) {
                        $extensionsFilter = array($extensionsFilter);
                    }
                    if (!empty($srcFiles)) {
                        foreach ($srcFiles as $key => $file) {
                            $fileExt = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                            if (!in_array($fileExt, $extensionsFilter)) {
                                unset($srcFiles[$key]);
                            }
                        }
                    }
                }
                if (empty($srcFiles)) {
                    // no translation intentionally
                    throw new Exception('No files to compile.');
                }

                $data = '';
                foreach ($srcFiles as $file) {
                    if (!file_exists($file)) {
                        continue;
                    }
                    $contents = file_get_contents($file) . "\n";
                    if ($beforeMergeCallback && is_callable($beforeMergeCallback)) {
                        $contents = call_user_func($beforeMergeCallback, $file, $contents);
                    }
                    $data .= $contents;
                }
                if (!$data) {
                    // no translation intentionally
                    throw new Exception(sprintf("No content found in files:\n%s", implode("\n", $srcFiles)));
                }

                if ($this->getPerformance()) {
                    $ext = '';
                    if($targetFile){
                        $files = explode('.', @basename($targetFile));
                    }
                    $ext = end($files);
                    if ($ext == 'js') {
                        if ($this->getJsMinify()) {
                            $textIn = str_replace("\r\n", "\n", $data);
                            $args   = array($textIn);
                            $func   = array('Varien_Jsmin', 'minify');
                            $data   = call_user_func_array($func, $args);
                        }
                    } elseif ($ext == 'css') {
                        if ($this->getCssMinify()) {
                            $textIn = str_replace("\r\n", "\n", $data);
                            $args   = array($textIn);
                            $func   = array('Varien_Cssmin', 'minify');
                            $data   = call_user_func_array($func, $args);
                        }
                    }
                }

                //$data = preg_replace('@\n\s*(\n)@', "$1", $data);

                if ($targetFile) {
                    file_put_contents($targetFile, $data, LOCK_EX);
                } else {
                    return $data; // no need to write to file, just return data
                }
            }

            return true; // no need in merger or merged into file successfully
        } catch (Exception $e) {
            Mage::logException($e);
        }
        return false;
    }

}
