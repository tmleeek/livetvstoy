<?php
/**
 * Override Default Varien Profiler to log in console
 *
 * @category    Zeon
 * @package     Zeon_Module
 * @copyright   Zeon Solutions. (http://www.zeonsolutions.com)
 * @author      Mukesh Patel <mukesh.patel@zeonsolutions.com>
 * @since       Tuesday 18 Jan, 2014
 */
class Varien_Profiler
{

    /**
     * Timers for code profiling
     *
     * @var array
     */
    static private $_timers           = array();
    static private $_enabled          = true;
    static private $_memory_get_usage = true;

    public static function enable()
    {
        self::$_enabled = true;
        self::$_memory_get_usage = function_exists('memory_get_usage');
    }

    public static function disable()
    {
        self::$_enabled = false;
    }

    public static function reset($timerName)
    {
        self::$_timers[$timerName] = array(
            'start'   => false,
            'count'   => 0,
            'sum'     => 0,
            'realmem' => 0,
            'emalloc' => 0,
        );
    }

    public static function resume($timerName)
    {

        if (!self::$_enabled) {
            return;
        }

        if (empty(self::$_timers[$timerName])) {
            self::reset($timerName);
        }
        if (self::$_memory_get_usage) {
            self::$_timers[$timerName]['realmem_start'] = memory_get_usage(true);
            self::$_timers[$timerName]['emalloc_start'] = memory_get_usage();
        }
        self::$_timers[$timerName]['start'] = microtime(true);
        self::$_timers[$timerName]['count'] ++;
    }

    public static function start($timerName)
    {

        self::resume($timerName);
    }

    public static function pause($timerName)
    {
        if (!self::$_enabled) {
            return;
        }

        $time = microtime(true); // Get current time as quick as possible to make more accurate calculations

        if (empty(self::$_timers[$timerName])) {
            self::reset($timerName);
        }
        if (false !== self::$_timers[$timerName]['start']) {
            self::$_timers[$timerName]['sum'] += $time - self::$_timers[$timerName]['start'];
            self::$_timers[$timerName]['start'] = false;
            if (self::$_memory_get_usage) {
                self::$_timers[$timerName]['realmem'] += memory_get_usage(true) -
                    self::$_timers[$timerName]['realmem_start'];
                self::$_timers[$timerName]['emalloc'] += memory_get_usage() -
                    self::$_timers[$timerName]['emalloc_start'];
            }
        }
    }

    public static function stop($timerName)
    {
        self::pause($timerName);
    }

    public static function fetch($timerName, $key = 'sum')
    {
        if (empty(self::$_timers[$timerName])) {
            return false;
        } elseif (empty($key)) {
            return self::$_timers[$timerName];
        }
        switch ($key) {
            case 'sum':
                $sum = self::$_timers[$timerName]['sum'];
                if (self::$_timers[$timerName]['start'] !== false) {
                    $sum += microtime(true) - self::$_timers[$timerName]['start'];
                }
                return $sum;

            case 'count':
                $count = self::$_timers[$timerName]['count'];
                return $count;

            case 'realmem':
                if (!isset(self::$_timers[$timerName]['realmem'])) {
                    self::$_timers[$timerName]['realmem'] = -1;
                }
                return self::$_timers[$timerName]['realmem'];

            case 'emalloc':
                if (!isset(self::$_timers[$timerName]['emalloc'])) {
                    self::$_timers[$timerName]['emalloc'] = -1;
                }
                return self::$_timers[$timerName]['emalloc'];

            default:
                if (!empty(self::$_timers[$timerName][$key])) {
                    return self::$_timers[$timerName][$key];
                }
        }
        return false;
    }

    public static function getTimers()
    {
        return self::$_timers;
    }

    public static function getSqlProfilerHtml($res)
    {
        if (!$res) {
            return '';
        }
        $out      = '';
        $profiler = $res->getProfiler();
        if ($profiler->getEnabled()) {
            $totalTime    = $profiler->getTotalElapsedSecs();
            $queryCount   = $profiler->getTotalNumQueries();
            $longestTime  = 0;
            $longestQuery = null;
            $extra        = '';
            $extra .= '<style type="text/css">
                    .queryInfoDetails {
                        border-collapse:collapse;
                        border-top:solid 3px #E1E6FA;
                        font-family:"Lucida Sans Unicode","Lucida Grande",Sans-Serif;
                        font-size:12px;
                        text-align:left;
                        width:680px!important;
                        text-align:left;
                        margin:30px auto;
                    }
                    .queryInfoDetails th {
                        color:#003399;
                        font-size:14px;
                        font-weight:normal;
                        padding:5px;
                        border-top:1px solid #E8EDFF;
                    }
                    .queryInfoDetails tr:hover, .queryInfoDetails tr:hover td {
                        background:#EFF2FF none repeat scroll 0 0;
                        color:#333399;
                    }
                    .queryInfoDetails td {
                        border-top:1px solid #E8EDFF;
                        color:#666699;
                        padding:5px;
                    }
                </style>';
            $counter      = 0;
            foreach ($profiler->getQueryProfiles() as $query) {
                /** @var $query Zend_Db_Profiler_Query */
                $queryParams = $query->getQueryParams();
                $params      = 'none';
                if (!empty($queryParams)) {
                    $params = print_r($queryParams, 1);
                }
                $queryType = (int) $query->getQueryType();
                switch ($queryType) {
                    case 1:
                        $queryType = 'CONNECT';
                        break;
                    case 2:
                        $queryType = 'QUERY';
                        break;
                    case 4:
                        $queryType = 'INSERT';
                        break;
                    case 8:
                        $queryType = 'UPDATE';
                        break;
                    case 16:
                        $queryType = 'DELETE';
                        break;
                    case 32:
                        $queryType = 'SELECT';
                        break;
                    case 64:
                        $queryType = 'TRANSACTION';
                        break;
                }
                $extra .= '<table class="queryInfoDetails" cellpadding="0" cellspacing="0">
                        <tr><th>Query no.</th><td>' . ++$counter . '</td></tr>
                        <tr><th>Query type</th><td>' . $queryType . '</td></tr>
                        <tr><th>Query params</th><td>' . $params . '</td></tr>
                        <tr><th>Elapsed seconds</th><td>' . $query->getElapsedSecs() . '</td></tr>
                        <tr><th>Raw query</th><td>' . wordwrap($query->getQuery()) . '</td></tr>
                    </table>';
                if ($query->getElapsedSecs() > $longestTime) {
                    $longestTime  = $query->getElapsedSecs();
                    $longestQuery = $query->getQuery();
                }
            }
            $out .= 'Executed ' . $queryCount . ' queries in ' . $totalTime . ' seconds' . "<br>";
            $out .= 'Average query length: ' . $totalTime / $queryCount . ' seconds' . "<br>";
            $out .= 'Queries per second: ' . $queryCount / $totalTime . "<br>";
            $out .= 'Longest query length: ' . $longestTime . "<br>";
            $out .= 'Longest query: <br>' . $longestQuery . "<hr>";
        }
        $out .= $extra;
        return $out;
    }

    public static function getSqlProfiler($res)
    {
        if (!$res) {
            return '';
        }
        $out      = '';
        $profiler = $res->getProfiler();
        if ($profiler->getEnabled()) {
            $totalTime             = $profiler->getTotalElapsedSecs();
            $queryCount            = $profiler->getTotalNumQueries();
            $longestTime           = 0;
            $longestQuery          = null;
            $out['QueryNo']        = '';
            $out['QueryType']      = '';
            $out['QueryParams']    = '';
            $out['ElapsedSeconds'] = '';
            $out['RawQuery']       = '';
            $queryData[0]          = '';
            $counter               = 0;
            foreach ($profiler->getQueryProfiles() as $query) {
                /** @var $query Zend_Db_Profiler_Query */
                $queryParams = $query->getQueryParams();
                $params      = 'none';
                if (!empty($queryParams)) {
                    $params = print_r($queryParams, 1);
                }
                $queryType = (int) $query->getQueryType();
                switch ($queryType) {
                    case 1:
                        $queryType = 'CONNECT';
                        break;
                    case 2:
                        $queryType = 'QUERY';
                        break;
                    case 4:
                        $queryType = 'INSERT';
                        break;
                    case 8:
                        $queryType = 'UPDATE';
                        break;
                    case 16:
                        $queryType = 'DELETE';
                        break;
                    case 32:
                        $queryType = 'SELECT';
                        break;
                    case 64:
                        $queryType = 'TRANSACTION';
                        break;
                }
                $out['QueryNo']        = ++$counter;
                $out['QueryType']      = $queryType;
                $out['QueryParams']    = $params;
                $out['ElapsedSeconds'] = $query->getElapsedSecs();
                $out['RawQuery']       = substr($query->getQuery(), 0, 50);

                if ($query->getElapsedSecs() > $longestTime) {
                    $longestTime  = $query->getElapsedSecs();
                    $longestQuery = $query->getQuery();
                    $queryNo      = ++$counter;
                }
                $data[] = $out;
            }
            $top['ExecutedInTime']   = $queryCount . ':' . $totalTime . ' seconds';
            $top['AverageQuery']     = $totalTime / $queryCount . ' seconds';
            $top['QueriesPerSecond'] = $queryCount / $totalTime;
            $top['QueryTimeNo']      = $queryNo . ':' . $longestTime . ' seconds';
            $top['LongestQuery']     = $longestQuery;



            $queryData[0] = $top;
        }
        $queryData[1] = $data;
        return $queryData;
    }

}
