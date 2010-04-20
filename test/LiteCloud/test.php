<?php
$root = dirname(__FILE__) . '/../../';
include_once("$root/LiteCloud.php");
include_once("$root/LiteCloud/Tyrant.php");

//-------------------------------------------------------
$config = array(
    'nodes' => array(   
        'lookup1_A' => array('127.0.0.1:41201'),
        'lookup1_B' => array('127.0.0.1:51201'),

        'storage1_A' => array('127.0.0.1:44201'),
        'storage1_B' => array('127.0.0.1:54201'),
    ),
);


LiteCloud::init($config);

$key = 'hello';
$value = 'world';

$const = 10000;

$stime = microtime(true);
for ($i = 0; $i< $const; $i++){
	LiteCloud::set($key , $value);
}
$etime = microtime(true);
$interval = $etime - $stime;
$qps = sprintf('%0.2f', 10000 / $interval);
print "Using $interval time to set $const values. QPS:$qps\n";

$stime = microtime(true);
for ($i = 0; $i< $const; $i++){
	LiteCloud::get($key);
}
$etime = microtime(true);
$interval = $etime - $stime;
$qps = sprintf('%0.2f', 10000 / $interval);
print "Using $interval time to get $const values. QPS:$qps\n";

$stime = microtime(true);
for ($i = 0; $i< $const; $i++){
	LiteCloud::delete($key);
}

$etime = microtime(true);
$interval = $etime - $stime;
$qps = sprintf('%0.2f', 10000 / $interval);
print "Using $interval time to delete $const values. QPS:$qps\n";



