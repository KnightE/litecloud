<?php
$root = dirname(__FILE__) . '/../../';
include_once("$root/LiteCloud.php");

//-------------------------------------------------------
$config['lookup1_A'] = '127.0.0.1:41201';
$config['lookup1_B'] = '127.0.0.1:51201';

$config['storage1_A'] = '127.0.0.1:44201';
$config['storage1_B'] = '127.0.0.1:54201';

list($lookupNodes, $storageNodes) = LiteCloud::generateNodes($config);
LiteCloud::init($lookupNodes, $storageNodes);

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



