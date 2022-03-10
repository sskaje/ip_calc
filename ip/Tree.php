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
    /**
     * 网段 开始IP => 结束IP 的map
     *
     * @var array
     */
    protected $data = [];
    /**
     * Key 的数量
     *
     * @var int
     */
    protected $count = 0;
    /**
     * 所有网段的开始IP
     *
     * @var array
     */
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
        # return sorted
        $ret = [];
        foreach ($this->data_keys as $v) {
            $ret[$v] = $this->data[$v];
        }
        return $ret;
    }


    /**
     * 获取网段列表
     *
     * @return array
     */
    public function getInverseBlocks()
    {
        # return sorted
        $ret = [];
        if ($this->data_keys[0] !== 0) {
            // 右边界 - 1
            $ret[0] = $this->data_keys[0] - 1;
        }

        for ($i = 0; $i < $this->count - 1; $i++) {
            // 左边界 +1，右边界 - 1
            $ret[$this->data[$this->data_keys[$i]] + 1] = $this->data_keys[$i+1] - 1;
        }

        if ($this->data[$this->data_keys[$this->count-1]] !== 0xFFFFFFFF) {
            // 左边界 +1
            $ret[$this->data[$this->data_keys[$this->count-1]] + 1] = 0xFFFFFFFF;
        }

        return $ret;
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

                if ($bn->begin < $this->data_keys[$insert_pos]) {
                    array_splice($this->data_keys, $insert_pos, 0, [$bn->begin]);
                } else {
                    array_splice($this->data_keys, $insert_pos+1, 0, [$bn->begin]);
                }
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

    /**
     * Delete block from current tree
     * 
     * @param \sskaje\ip\BlockNode $bn
     */
    public function deleteBlock(BlockNode $bn)
    {
        if (empty($this->data)) {
            return;
        }

        $insert_pos = $this->getInsertPosition($bn->begin);

        $i = $insert_pos;
        do {
            if (!isset($this->data_keys[$i])) {
                break;
            }

            $current_begin = $this->data_keys[$i];
            $current_end   = $this->data[$current_begin];

            if ($current_begin > $bn->end) {
                break;
            } else if ($current_end < $bn->begin) {
                ++ $i;
                continue;
            }

            if ($current_begin < $bn->begin) {
                $new_end = $bn->begin - 1;
                $this->data[$current_begin] = $new_end;

                if ($bn->end < $current_end) {
                    # cut into two parts
                    $new_begin = $bn->end + 1;
                    $this->data[$new_begin] = $current_end;

                    array_splice($this->data_keys, $i+1, 0, [$new_begin]);
                    ++ $this->count;
                }

            } else { // $current_begin >= $bn->begin
                if ($current_end <= $bn->end) {
                    unset($this->data[$current_begin]);
                    array_splice($this->data_keys, $i, 1);
                    -- $this->count;
                    -- $i;
                } else {
                    // new begin:
                    $new_begin = $bn->end + 1;
                    $this->data_keys[$i] = $new_begin;
                    $this->data[$new_begin] = $current_end;
                    unset($this->data[$current_begin]);
                }
            }
            ++ $i;
        } while (1);
    }

    /**
     * Dump keys
     */
    public function dump_keys()
    {
        echo "--- Dump Keys ---\n";
        foreach ($this->data_keys as $k=>$v) {
            echo $k, "\t", Utils::long2ip($v), "\n";
        }
        echo "\n";
    }

    /**
     * Dump blocks
     */
    public function dump_data()
    {
        echo "--- Dump Data ---\n";
        foreach ($this->data_keys as $v) {
            echo Utils::long2ip($v), "\t", Utils::long2ip($this->data[$v]), "\n";
        }
        echo "\n";
    }
}

# EOF
