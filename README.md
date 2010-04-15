# LiteCloud Library for PHP
This is a library for accessing LightCloud systems through PHP.

## Background

LightCloud is a distributed key-value stored open-sourced by Plurk.
The official website which includes benchmarks, design specs, and
more can be viewed at the following URL:

http://opensource.plurk.com/LightCloud/

## Usage

You can use it with class methods:

<pre>
<code>
require 'LiteCloud.php';

$config = array(
	'classes' => array(
		'storage' 	=> 'LiteCloud_Tyrant',
	),
	'nodes' => array(	
		'lookup1_A' => array('127.0.0.1:41201', '127.0.0.1:41202'),
		'lookup1_B' => array('127.0.0.1:51201', '127.0.0.1:51202'),

		'storage1_A' => array('127.0.0.1:44201', '127.0.0.1:44202',),
		'storage1_B' => array('127.0.0.1:54201', '127.0.0.1:54202',),
	),
);

LiteCloud::init($config);

LiteCloud::set('hello', 'world');
print LiteCloud::get("hello"); # => world
LiteCloud::delete("hello");

print LiteCloud::get("hello"); # => nil
</code>
</pre>

Or you can also use it with instances:

<pre>
<code>
require 'LiteCloud.php';

$config = array(
	'nodes' => array(	
		'lookup1_A' => array('127.0.0.1:41201', '127.0.0.1:41202'),
		'lookup1_B' => array('127.0.0.1:51201', '127.0.0.1:51202'),

		'storage1_A' => array('127.0.0.1:44201', '127.0.0.1:44202',),
		'storage1_B' => array('127.0.0.1:54201', '127.0.0.1:54202',),
	),
);

$cloud = new LiteCloud($config);

$cloud->set('hello', 'world');
print $cloud->get("hello"); # => world
$cloud->delete("hello");

print $cloud->get("hello"); # => nil
</code>
</pre>

## Dependence

  sudo pecl install memcached

## Known Issues / To-Do

The python library actually caches the get/set values in a thread-local
hash table. This library won't do this now.
