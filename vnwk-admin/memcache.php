<?php
define("MEMCACHED_PORT", 11211);
$memcache = new Memcache;
$memcache->connect("127.0.0.1", MEMCACHED_PORT);
$memcache->flush();
class Mem {
    public static $memcache;
}
Mem::$memcache = new Memcache;
Mem::$memcache->connect("127.0.0.1", MEMCACHED_PORT);
 ?>