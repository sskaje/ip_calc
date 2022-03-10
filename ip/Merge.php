<?php

namespace sskaje\ip;

/**
 * 网段 Merge 工具
 *
 * @author sskaje (https://sskaje.me/ sskaje@gmail.com)
 */
class Merge
{
    /**
     * @var Tree
     */
    protected $tree;

    public function __construct()
    {
        $this->tree = new Tree();
    }

    /**
     * 从文件添加
     *
     * @param string $filename
     */
    public function addFile($filename)
    {
        $lines = file($filename);
        foreach ($lines as $ip) {
            $ip = trim($ip);
            if (!$ip) continue;
            $this->addBlock(Block::CreateFromIPCIDR($ip));
        }
    }

    /**
     * 添加网段数组
     * 每行都是 IP/CIDR 格式
     *
     * @param array $array [IP1/CIDR1, IP2/CIDR2, ...]
     */
    public function addArray(array $array)
    {
        foreach ($array as $item) {
            $this->addBlock(Block::CreateFromIPCIDR($item));
        }
    }

    /**
     * 添加 BlockNode 的网段定义
     *
     * @param \sskaje\ip\BlockNode $bn
     */
    public function addBlock(BlockNode $bn)
    {
        $this->tree->addBlock($bn);
    }

    /**
     * 添加 IP/CIDR 格式的网段
     *
     * @param string $ip_cidr
     */
    public function addIPCIDR($ip_cidr)
    {
        $this->addBlock(Block::CreateFromIPCIDR($ip_cidr));
    }

    /**
     * 获取IP段
     *
     * @return array [begin_ipv4=>end_ipv4]
     */
    public function getBlocks()
    {
        $blocks = $this->tree->getBlocks();
        $new_blocks = [];
        foreach ($blocks as $k=>$v) {
            $new_blocks[Utils::long2ip($k)] = Utils::long2ip($v);
        }
        return $new_blocks;
    }
    /**
     * 获取反向IP段
     *
     * @return array [begin_ipv4=>end_ipv4]
     */
    public function getInverseBlocks()
    {
        $blocks = $this->tree->getInverseBlocks();

        $new_blocks = [];
        foreach ($blocks as $k=>$v) {
            $new_blocks[Utils::long2ip($k)] = Utils::long2ip($v);
        }
        return $new_blocks;
    }
}



# EOF
