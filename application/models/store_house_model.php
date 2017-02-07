<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

// 会员
class store_house_model extends content_model
{
    function __construct ()
    {
        parent::__construct();

        $this->table = 'zy_store_house';
    }

    //获取当前仓库存量
    public function get_current_store_num($uId){
        $res = $this->db->query("SELECT h.num FROM zy_store_house h,zy_goods g WHERE h.goodsId=g.id AND uId=$uId")->result_array();
        return $res;
    }

    //获取当前仓库存储的 种子 数量
    public function get_seed_num(){
        $res = $this->db->query("SELECT g.id,g.goodsName,h.num FROM zy_store_house h,zy_goods g WHERE g.goodsClass = 1 AND h.goodsId=g.id AND h.num>0")->result_array();
        return $res;
    }

    //获取当前仓库存储的所有物品名称以及对应的数量
    public function get_all_num(){
        $res = $this->db->query("SELECT g.id,g.goodsName,h.num FROM zy_store_house h,zy_goods g WHERE h.goodsId=g.id AND h.num>0")->result_array();
        return $res;
    }




}
