<?php
require(__DIR__ . '/../ip_merger.class.php');
$ipmerger = new IPMerger();

# facebook ip
$fb_ip = '173.252.64.0/16
173.252.64.0/18
173.252.64.0/19
173.252.70.0/24
173.252.96.0/19
';
$fb_ip = trim($fb_ip);
$fb_ip = explode("\n", $fb_ip);
foreach ($fb_ip as $ip) {
    $ipmerger->addBySubnet($ip);
}

$s = $ipmerger->getSubnets();
foreach ($s as $r) {
    echo $r[IPMerger::BEGIN], "\t", $r[IPMerger::END] , "\n";
}

