# LiteCloud Library for PHP
This is a library for accessing LightCloud systems through PHP.

## Background

LightCloud is a distributed key-value stored open-sourced by Plurk.
The official website which includes benchmarks, design specs, and
more can be viewed at the following URL:

http://opensource.plurk.com/LightCloud/

## Usage

You can use it with class methods:

require 'LiteCloud.php';

$config = array(
	'lookup1_A' => '127.0.0.1:41201',
	'lookup1_B' => '127.0.0.1:51201',

	'storage1_A' => '127.0.0.1:44201',
	'storage1_B' => '127.0.0.,:54201',
);

list($lookupNodes, $storageNodes) = LiteCloud::generateNodes($config);
LiteCloud::init($lookupNodes, $storageNodes);

LiteCloud::set('hello', 'world');
print LiteCloud::get("hello"); # => world
LiteCloud::delete("hello");

print LiteCloud::get("hello"); # => nil

Or you can also use it with instances:

require 'LiteCloud.php';

$config = array(
	'lookup1_A' => '127.0.0.1:41201',
	'lookup1_B' => '127.0.0.1:51201',

	'storage1_A' => '127.0.0.1:44201',
	'storage1_B' => '127.0.0.,:54201',
);

$cloud = new LiteCloud($config);

$cloud->set('hello', 'world');
print $cloud->get("hello"); # => world
$cloud->delete("hello");

print $cloud->get("hello"); # => nil

## Dependence

  sudo pecl install php-memcached

## Known Issues / To-Do

The python library actually caches the get/set values in a thread-local
hash table. This library won't do this now.
