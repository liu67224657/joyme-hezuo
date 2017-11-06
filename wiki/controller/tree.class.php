<?php

/**
 * Description:
 * Author: gradydong
 * Date: 2017/3/24
 * Time: 15:35
 * Copyright: Joyme.com
 */
class tree
{
    /**
     * +------------------------------------------------
     * 生成树型结构所需要的2维数组
     * +------------------------------------------------
     * @author abc
     * +------------------------------------------------
     * @var Array
     */
    var $arr = array();

    var $exsitdata = array();
    var $exsittempdata = array();
    var $sortdata = array();
    var $sorttempdata = array();


    /**
     * @access private
     */
    var $ret = '';

    /**
     * 构造函数，初始化类
     * @param array 2维数组
     */
    function init($arr = array())
    {
        $this->arr = $arr;
        $this->ret = '';
        return is_array($arr);
    }

    /**
     * 得到子级数组
     * @param int
     * @return array
     */
    function get_child($myname)
    {
        $newarr = array();
        if (is_array($this->arr)) {
            foreach ($this->arr as $k => $a) {
                if ($a['pname'] == $myname) {
                    $newarr[$k] = $a;
                }
            }
        }
        return $newarr ? $newarr : false;
    }

    /**
     * +------------------------------------------------
     * 格式化数组
     * +------------------------------------------------
     * @author abc
     * +------------------------------------------------
     */
    function getArray($pname = '')
    {
        $child = $this->get_child($pname);
        if (is_array($child)) {
            foreach ($child as $id => $a) {
                $this->ret[$a['name']] = $a;
                $this->getArray($a['name']);
            }
        }
        return $this->ret;
    }

    function gettree($tree, $pname = '')
    {
        $return = array();
        foreach ($tree as $leaf) {
            if ($leaf['pname'] == $pname) {
                foreach ($tree as $subleaf) {
                    if ($subleaf['pname'] == $leaf['name']) {
                        $leaf['children'] = $this->gettree($tree, $leaf['name']);
                        break;
                    }
                }
                if ($pname == '') {
                    $return[$leaf['name']] = $leaf;
                } else {
                    $return[] = $leaf;
                }
            }
        }
        return $return;
    }

    function getselectedtree($tree, $pname = '')
    {
        $return = array();
        foreach ($tree as $leaf) {
            if ($leaf['pname'] == $pname) {
                if (in_array($leaf['name'], $this->exsitdata)) {
                    $leaf['selected'] = 'selected';
                    $leaf['sort'] = $this->sortdata[$leaf['name']];
                }elseif (in_array($leaf['name'], $this->exsittempdata)){
                    $leaf['selected'] = 'selected';
                    $leaf['sort'] = $this->sorttempdata[$leaf['name']];
                }

                foreach ($tree as $subleaf) {
                    if ($subleaf['pname'] == $leaf['name']) {
                        $leaf['children'] = $this->getselectedtree($tree, $leaf['name']);
                        break;
                    }
                }
                if ($pname == '') {
                    $return[$leaf['name']] = $leaf;
                } else {
                    $return[] = $leaf;
                }
            }
        }
        return $return;
    }

    function gettreelists($tree, $pname = 'root')
    {
        $return = array();
        foreach ($tree as $leaf) {
            if ($leaf['pname'] == $pname) {
                foreach ($tree as $subleaf) {
                    if ($subleaf['pname'] == $leaf['wcat_name']) {
                        $leaf['children'] = $this->gettreelists($tree, $leaf['wcat_name']);
                        break;
                    }
                }
                if ($pname == '') {
                    $return[$leaf['wcat_name']] = $leaf;
                } else {
                    $return[] = $leaf;
                }
            }
        }
        return $return;
    }
}