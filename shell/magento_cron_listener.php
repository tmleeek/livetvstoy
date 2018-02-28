<?php
// shell/listAllCron.php
require_once 'abstract.php';

class Mage_Shell_CronLister extends Mage_Shell_Abstract
{
    public function run()
    {
        $cronJobs = Mage::app()->getConfig()->getNode('crontab/jobs');

        $outputFormat = "%-60s %-20s %-50s";

        printf($outputFormat . "\n", "Job name", "m h dom mon dow", "Object::Method to execute");
        $lines = "Job name, m h dom mon dow, Object::Method to execute";

        foreach($cronJobs->children() as $key => $job) {
            $expr = trim((string) $job->schedule->cron_expr);
            $datas[$key] = sprintf($outputFormat, trim($job->getName()), $expr, trim((string) $job->run->model));
            $datas_csv[$key] = array(trim($job->getName()), $expr, trim((string) $job->run->model));
        }

        uksort($datas, array($this, 'compareTimes'));

        foreach($datas as $job) {
            echo $job . "\n";
        }
    }

    public function compareTimes($time1, $time2)
    {
        $times1 = explode(' ', $time1);
        $times2 = explode(' ', $time2);

        if(( ! isset($times1[1])) || ($times1[1] == '*')) return -1;

        if(( ! isset($times2[1])) || ($times2[1] == '*')) return  1;

        $times1[1] = (int) trim($times1[1]);
        $times2[1] = (int) trim($times2[1]);
        $times1[0] = (int) trim($times1[0]);
        $times2[0] = (int) trim($times2[0]);

        if($times1[1] != $times2[1]) {
            $res = ($times1[1] - $times2[1]) * 1000;
            return $res;
        }

        return $times1[0] - $times2[0];
    }
}

$cronLister = new mage_Shell_CronLister();
$cronLister->run();
