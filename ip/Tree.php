<?php

namespace sskaje\ip;

/**
 * IPv4 树, 用于合并网段
 * 内部数据以 uint32 存储和计算
 *
 * @package sskaje\ip
 */
class Tree
{
    protected $data = [];
    protected $count = 0;
    protected $data_keys = [];

    public function __construct()
    {
    }

    /**
     * 获取网段列表
     *
     * @return array
     */
    public function getBlocks()
    {
        return $this->data;
    }

    /**
     * 添加网段
     *
     * @param \sskaje\ip\BlockNode $bn
     */
    public function addBlock(BlockNode $bn)
    {
        if (empty($this->data)) {
            $this->data[$bn->begin] = $bn->end;
            $this->count = 1;
            $this->data_keys[0] = $bn->begin;
        } else {
            $insert_pos = $this->getInsertPosition($bn->begin);
            if ($bn->begin == $this->data_keys[$insert_pos]) {
                if ($bn->end <= $this->data[$this->data_keys[$insert_pos]]) {
                    # nothing to do
                    return;
                } else {
                    $this->data[$bn->begin] = $bn->end;
                }
            } else {
                $this->data[$bn->begin] = $bn->end;
                $this->count ++;

                array_splice($this->data_keys, $insert_pos+1, 0, [$bn->begin]);
            }

            do {
                $ret = $this->compareNeighborBlock($insert_pos);
                if ($ret === false) {
                    break;
                }
            } while (true);

        }
    }

    /**
     * 查找开始IP的插入位置
     *
     * @param int $test
     * @return int
     */
    protected function getInsertPosition($test)
    {
        $left = 0;
        $right = $this->count;

        do {
            $mid = floor(($left + $right) / 2);

            if ($test < $this->data_keys[$mid]) {
                $right = $mid;
            } else if ($test > $this->data_keys[$mid]) {
                $left = $mid;
            } else { // $test == $this->data_keys[$mid]
                return $mid;
            }

            // 退出条件
            if ($right - $left <= 1) {
                break;
            }
        } while (1);

        return $left;
    }

    /**
     * 以data_keys的索引位置判断相邻的网段是否需要合并
     *
     * @param int $compare_index
     * @return bool
     */
    protected function compareNeighborBlock($compare_index)
    {
        if (!isset($this->data_keys[$compare_index]) || !isset($this->data_keys[$compare_index+1])) {
            return false;
        }

        $cur_begin = $this->data_keys[$compare_index];
        $cur_end   = $this->data[$cur_begin];

        $next_begin = $this->data_keys[$compare_index+1];
        $next_end   = $this->data[$next_begin];

        if ($next_begin > $cur_end + 1) {
            # 相邻两个网段是不连续的, 直接退出
            return false;
        } else {
            # 如果相邻两个网段连续或有交叠, 则处理数据, 并继续从当前位置判断下一个相邻网段
            $this->data[$cur_begin] = max($next_end, $cur_end);
            $this->count --;
            array_splice($this->data_keys, $compare_index+1, 1);
            unset($this->data[$next_begin]);
            return true;
        }
    }

    public function deleteBlock(BlockNode $bn)
    {
        if (empty($this->data)) {
            return;
        }

        

    }

    /**
     * Dump keys
     */
    protected function dump_keys()
    {
        foreach ($this->data_keys as $k=>$v) {
            echo $k, "\t", Utils::long2ip($v), "\n";
        }
        echo "\n";
    }

    /**
     * Dump blocks
     */
    protected function dump_data()
    {
        foreach ($this->data as $k=>$v) {
            echo Utils::long2ip($k), "\t", Utils::long2ip($v), "\n";
        }
        echo "\n";
    }
}

# EOF
