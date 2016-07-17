<?php
/**
 * Merge IP blocks
 *
 * @author sskaje
 */

if (!isset($argv[1]) || !is_file($argv[1])) {
    die("Usage: php {$argv[0]} FILE\n");
}

require(__DIR__ . '/../autoload.php');

use sskaje\ip\Calculator;
use sskaje\ip\Merge;


$merge = new Merge();
$merge->addFile($argv[1]);

$s = $merge->getBlocks();

foreach ($s as $from=>$to) {
    $ss = Calculator::Range2Blocks($from, $to);

    foreach ($ss as $ip) {
        list($subnet, $broadcast, $netmask, ) = Calculator::ipCidr2Subnet($ip['subnet'], $ip['cidr']);
        echo $subnet . '/' . $ip['cidr'] . "\n";
    }
}

