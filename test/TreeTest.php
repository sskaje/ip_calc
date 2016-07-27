<?php
require(__DIR__ . '/../autoload.php');

use sskaje\ip\Block;
use sskaje\ip\Tree;

echo "======== Tree 1 ========\n";
$tree1 = new Tree();
$tree1->addBlock(Block::CreateFromIPCIDR('8.8.0.0', 16));
$tree1->addBlock(Block::CreateFromIPCIDR('8.8.4.0', 24));
$tree1->addBlock(Block::CreateFromIPCIDR('8.8.8.0', 24));
$tree1->dump_keys();
$tree1->dump_data();


echo "======== Tree 2 ========\n";
$tree2 = new Tree();
$tree2->addBlock(Block::CreateFromIPCIDR('74.125.0.0/16'));
$tree2->addBlock(Block::CreateFromIPCIDR('74.125.0.0/24'));
$tree2->addBlock(Block::CreateFromIPCIDR('74.125.1.0/24'));
$tree2->addBlock(Block::CreateFromIPCIDR('74.125.2.0/24'));
$tree2->addBlock(Block::CreateFromIPCIDR('74.125.3.0/24'));
$tree2->addBlock(Block::CreateFromIPCIDR('74.125.4.0/24'));
$tree2->dump_keys();
$tree2->dump_data();


echo "======== Tree 3 ========\n";
$tree3 = new Tree();
$tree3->addBlock(Block::CreateFromIPCIDR('74.125.32.0/20'));
$tree3->addBlock(Block::CreateFromIPCIDR('74.125.48.0/21'));
$tree3->addBlock(Block::CreateFromIPCIDR('74.125.56.0/24'));
$tree3->addBlock(Block::CreateFromIPCIDR('74.125.57.0/25'));
$tree3->addBlock(Block::CreateFromIPCIDR('74.125.57.128/26'));
$tree3->addBlock(Block::CreateFromIPCIDR('74.125.57.192/27'));
$tree3->addBlock(Block::CreateFromIPCIDR('74.125.57.224/28'));
$tree3->addBlock(Block::CreateFromIPCIDR('74.125.57.240/29'));
$tree3->dump_keys();
$tree3->dump_data();


echo "======== Tree 4 ========\n";
$tree4 = new Tree();
$tree4->addBlock(Block::CreateFromIPCIDR('8.8.5.0', 24));
$tree4->addBlock(Block::CreateFromIPCIDR('8.8.4.0', 24));
$tree4->addBlock(Block::CreateFromIPCIDR('8.8.3.0', 24));
$tree4->dump_keys();
$tree4->dump_data();

echo "======== Tree 5 ========\n";
$tree5 = new Tree();
$tree5->addBlock(Block::CreateFromIPCIDR('8.8.1.0', 24));
$tree5->addBlock(Block::CreateFromIPCIDR('8.8.5.0', 24));
$tree5->addBlock(Block::CreateFromIPCIDR('8.8.2.0', 24));
$tree5->addBlock(Block::CreateFromIPCIDR('8.8.3.0', 24));
$tree5->addBlock(Block::CreateFromIPCIDR('8.8.4.0', 24));
$tree5->dump_keys();
$tree5->dump_data();



echo "======== Tree 6 ========\n";
$tree6 = new Tree();
$tree6->addBlock(Block::CreateFromIPRange('10.0.6.3', '10.0.6.3'));
$tree6->addBlock(Block::CreateFromIPCIDR('10.0.1.0', 24));
$tree6->addBlock(Block::CreateFromIPRange('10.0.4.128', '10.0.6.0'));
$tree6->addBlock(Block::CreateFromIPRange('10.0.6.0', '10.0.6.1'));
$tree6->dump_keys();
$tree6->dump_data();





echo "======== Tree 7 ========\n";
$tree7 = new Tree();
$tree7->addBlock(Block::CreateFromIPRange('10.0.0.0', '10.0.7.255'));
$tree7->dump_keys();
$tree7->dump_data();

$tree7->deleteBlock(Block::CreateFromIPCIDR('10.0.2.0/24'));
$tree7->dump_data();

$tree7->deleteBlock(Block::CreateFromIPCIDR('10.0.6.0/24'));
$tree7->dump_data();

$tree7->deleteBlock(Block::CreateFromIPRange('10.0.5.255', '10.0.5.255'));
$tree7->dump_data();


$tree7->deleteBlock(Block::CreateFromIPRange('10.0.5.128', '10.0.7.127'));
$tree7->dump_data();

$tree7->deleteBlock(Block::CreateFromIPRange('9.255.255.128', '10.0.0.127'));
$tree7->dump_data();

$tree7->deleteBlock(Block::CreateFromIPRange('10.0.1.2', '10.0.7.192'));
$tree7->dump_data();



exit;
/*
$file = file(__DIR__ . '/ips.txt');
foreach ($file as $l) {
    $l = trim($l);
    if ($l) {
        $tree->addBlock(Block::CreateFromIPCIDR($l));
    }
}
*/
