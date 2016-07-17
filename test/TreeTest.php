<?php
require(__DIR__ . '/../autoload.php');

use sskaje\ip\Block;
use sskaje\ip\Tree;

$tree = new Tree();
/*
$tree->addBlock(Block::CreateFromIPCIDR('8.8.0.0', 16));
$tree->addBlock(Block::CreateFromIPCIDR('8.8.4.0', 24));
$tree->addBlock(Block::CreateFromIPCIDR('8.8.8.0', 24));
*/

#$tree->addBlock(Block::CreateFromIPCIDR('74.125.0.0/16'));
$tree->addBlock(Block::CreateFromIPCIDR('74.125.0.0/24'));
$tree->addBlock(Block::CreateFromIPCIDR('74.125.1.0/24'));
$tree->addBlock(Block::CreateFromIPCIDR('74.125.2.0/24'));
$tree->addBlock(Block::CreateFromIPCIDR('74.125.3.0/24'));
$tree->addBlock(Block::CreateFromIPCIDR('74.125.4.0/24'));

$tree->addBlock(Block::CreateFromIPCIDR('74.125.32.0/20'));
$tree->addBlock(Block::CreateFromIPCIDR('74.125.48.0/21'));
$tree->addBlock(Block::CreateFromIPCIDR('74.125.56.0/24'));
$tree->addBlock(Block::CreateFromIPCIDR('74.125.57.0/25'));
$tree->addBlock(Block::CreateFromIPCIDR('74.125.57.128/26'));
$tree->addBlock(Block::CreateFromIPCIDR('74.125.57.192/27'));
$tree->addBlock(Block::CreateFromIPCIDR('74.125.57.224/28'));
$tree->addBlock(Block::CreateFromIPCIDR('74.125.57.240/29'));

/*
$file = file(__DIR__ . '/ips.txt');
foreach ($file as $l) {
    $l = trim($l);
    if ($l) {
        $tree->addBlock(Block::CreateFromIPCIDR($l));
    }
}
*/

$data = $tree->getBlocks();
foreach ($data as $k=>$v) {
    echo long2ip($k), "\t", long2ip($v), "\n";
}