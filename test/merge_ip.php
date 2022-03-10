<?php

use sskaje\ip\Block;
use sskaje\ip\Calculator;
use sskaje\ip\Merge;

require(__DIR__ . '/../autoload.php');

$merge = new Merge();

# facebook ip
$fb_ip = '173.252.64.0/16
173.252.64.0/18
173.252.64.0/19
173.252.70.0/24
173.252.96.0/19
';

$fb_ip = '
192.168.1.0/24
192.168.4.0/24
192.168.2.0/30
10.0.0.0/16
172.16.3.0/24
';

$fb_ip = trim($fb_ip);
$fb_ip = explode("\n", $fb_ip);

foreach ($fb_ip as $ip) {
    $merge->addBlock(Block::CreateFromIPCIDR($ip));
}

echo "--- blocks ---\n";
$s = $merge->getBlocks();
foreach ($s as $from=>$to) {
    echo "# ", $from, "\t", $to, "\n";
    $subnets = Calculator::Range2Subnets($from, $to);
    foreach ($subnets as $subnet) {
        echo $subnet, "\n";
    }
}

echo "--- inversed blocks ---\n";
$s = $merge->getInverseBlocks();

foreach ($s as $from=>$to) {
    echo "# ", $from, "\t", $to, "\n";
    $subnets = Calculator::Range2Subnets($from, $to);
    foreach ($subnets as $subnet) {
        echo $subnet, "\n";
    }
}

