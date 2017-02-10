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
        $res = $this->db->query("SELECT h.num FROM zy_store_house h,zy_goods g WHERE h.goodsId=g.id AND h.uId=$uId")->result_array();
        return $res;
    }

    //获取当前仓库存储的 种子 数量 图片
    public function get_seed_num($uId){
        $res = $this->db->query("SELECT g.id,g.goodsName,g.thumb,h.num FROM zy_store_house h,zy_goods g WHERE h.uId=$uId AND g.goodsClass = 1 AND h.goodsId=g.id AND h.num>0")->result_array();
        return $res;
    }

    //获取当前仓库存储的所有物品名称以及对应的数量
    public function get_all_num($uId){
        $res = $this->db->query("SELECT g.id,g.goodsName,h.num FROM zy_store_house h,zy_goods g WHERE h.uId=$uId AND h.goodsId=g.id AND h.num>0")->result_array();
        return $res;
    }

    //获取当前仓库存储的所有物品名称以及对应的数量、售卖的单价
    public function get_sale_num($uId){
        $res = $this->db->query("SELECT g.id,g.goodsName,g.salePriceLD,g.salePriceJB,h.num FROM zy_store_house h,zy_goods g WHERE h.uId=$uId AND h.goodsId=g.id AND h.num>0")->result_array();
        return $res;
    }


}
