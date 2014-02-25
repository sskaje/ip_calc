<?php
require(__DIR__ . '/../ipcalc.class.php');
if (!isset($argv[2])) {
	echo <<<USAGE
Usage: php {$argv[0]} BEGIN_IP END_IP

USAGE;
	exit;
}
$begin = $argv[1];
$end = $argv[2];

$s = IPCalculator::getSubnetsFromRange($begin, $end);
foreach ($s as $ip) {
    list($subnet, $broadcast, $netmask) = IPCalculator::ipCidr2Subnet($ip['subnet'], $ip['cidr']);
    echo long2ip($ip['subnet']) . '/' . $ip['cidr'] . "\t" . $netmask . "\t" . $broadcast . "\n";
}

