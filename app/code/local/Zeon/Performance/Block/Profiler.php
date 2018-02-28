<?php
/**
 * ClassName
 *
 * @category    Zeon
 * @package     Zeon_Performance
 * @copyright   Zeon Solutions. (http://www.zeonsolutions.com)
 * @author      Mukesh Patel <mukesh.patel@zeonsolutions.com>
 * @since       Tuesday 18 Jan, 2014
 */
class Zeon_Performance_Block_Profiler extends Mage_Core_Block_Profiler
{

    protected function _toHtml()
    {


        if (!$this->_beforeToHtml()
            || !Mage::getStoreConfig('dev/debug/profiler')
            || !Mage::helper('core')->isDevAllowed()) {
            return '';
        }
        if (Mage::getStoreConfig('zeon_performance/global/debug')) {
            if (!Mage::getStoreConfig('zeon_performance/global/console')) {
                return $this->footerHtml();
            }

            $timers = Varien_Profiler::getTimers();

            $new                 = 'Memory usage: real: ' .
                memory_get_usage(true) . ', emalloc: ' . memory_get_usage() . "\t";
            $out['CodeProfiler'] = '';
            $out['Time']         = '';
            $out['count']        = '';
            $out['Emalloc']      = '';
            $out['RealMemt']     = '';
            foreach ($timers as $name => $timer) {
                $sum     = Varien_Profiler::fetch($name, 'sum');
                $count   = Varien_Profiler::fetch($name, 'count');
                $realmem = Varien_Profiler::fetch($name, 'realmem');
                $emalloc = Varien_Profiler::fetch($name, 'emalloc');
                if ($sum < .0010 && $count < 10 && $emalloc < 10000) {
                    continue;
                }

                $out['CodeProfiler'] = $name;
                $out['Time']         = number_format($sum, 4);
                $out['count']        = $count;
                $out['Emalloc']      = number_format($emalloc);
                $out['RealMemt']     = number_format($realmem);
                $data[]              = $out;
            }
            $queryData = Varien_Profiler::getSqlProfiler(
                Mage::getSingleton('core/resource')->getConnection('core_write')
            );

            $console    = $this->consoleLog($data, $new, $queryData);
            $coreWrite = Mage::getSingleton('core/resource')->getConnection('core_write');
            if (Mage::getStoreConfig('zeon_performance/global/sqldebug')) {
                $htmlOutput = print_r(Varien_Profiler::getSqlProfilerHtml($coreWrite), 1);
                $console .= $htmlOutput;
            }
            return $console;
        }
    }

    protected function footerHtml()
    {
        if (!$this->_beforeToHtml()
            || !Mage::getStoreConfig('dev/debug/profiler')
            || !Mage::helper('core')->isDevAllowed()) {
            return '';
        }

        $timers = Varien_Profiler::getTimers();
       
        $out = "<a href=\"javascript:void(0)\" onclick=\"$('profiler_section')"
            . ".style.display=$('profiler_section').style.display==''?'none':''\">"
            . "[profiler]</a>";
        $out .= '<div id="profiler_section" style="background:white; display:block">';
        $out .= '<pre>Memory usage: real: ' . memory_get_usage(true) . ', emalloc: ' . memory_get_usage() . '</pre>';
        $out .= '<table border="1" cellspacing="0" cellpadding="2" style="width:auto">';
        $out .= '<tr><th>Code Profiler</th><th>Time</th><th>Cnt</th><th>Emalloc</th><th>RealMem</th></tr>';
        foreach ($timers as $name => $timer) {
            $sum     = Varien_Profiler::fetch($name, 'sum');
            $count   = Varien_Profiler::fetch($name, 'count');
            $realmem = Varien_Profiler::fetch($name, 'realmem');
            $emalloc = Varien_Profiler::fetch($name, 'emalloc');
            if ($sum < .0010 && $count < 10 && $emalloc < 10000) {
                continue;
            }
            $out .= '<tr>'
                . '<td align="left">' . $name . '</td>'
                . '<td>' . number_format($sum, 4) . '</td>'
                . '<td align="right">' . $count . '</td>'
                . '<td align="right">' . number_format($emalloc) . '</td>'
                . '<td align="right">' . number_format($realmem) . '</td>'
                . '</tr>'
            ;
        }
        $out .= '</table>';
        $out .= '<pre>';
        $coreWrite = Mage::getSingleton('core/resource')->getConnection('core_write');
        $out .= print_r(Varien_Profiler::getSqlProfiler($coreWrite), 1);
        $out .= '</pre>';
        $out .= '</div>';

        return $out;
    }

    function consoleLog($data, $new, $queryData)
    {
        $out       = "<script>\r\n//<![CDATA[\r\nif(!console){var console={log:function(){}}}";
        $data      = json_encode($data);
        $longQuery = json_encode($queryData[0]);
        $allQuery  = json_encode($queryData[1]);
        $out .="console.log(\"" . $new . "\");";
        $out .="console.table(eval(" . $data . "));";
        $out .="console.table(eval([" . $longQuery . "]));";
        $out .="console.table(eval(" . $allQuery . "));";
        $out .= "\r\n//]]>\r\n</script>";
        return $out;
    }

}
