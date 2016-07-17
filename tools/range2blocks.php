<?php
/**
 * IP range to ip blocks
 *
 * @author sskaje
 */

if (!isset($argv[2])) {
    echo <<<USAGE
Usage: php {$argv[0]} BEGIN_IP END_IP
USAGE;
    exit;
}

require(__DIR__ . '/../autoload.php');
use sskaje\ip\Calculator;

$begin = $argv[1];
$end = $argv[2];
$s = Calculator::Range2Blocks($begin, $end);
foreach ($s as $ip) {
    list($subnet, $broadcast, $netmask) = Calculator::ipCidr2Subnet($ip['subnet'], $ip['cidr']);
    echo $ip['subnet'] . '/' . $ip['cidr'] . "\t" . $netmask . "\t" . $broadcast . "\n";
}

