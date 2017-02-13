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
        $res = $this->db->query("SELECT g.id,g.goodsName,g.thumb,h.num FROM zy_store_house h,zy_goods g WHERE h.uId=$uId AND g.goodsClass = 1 AND h.goodsId=g.id")->result_array();
        return $res;
    }

    //获取当前仓库存储的所有物品名称以及对应的数量
    public function get_all_num($uId){
        $res = $this->db->query("SELECT g.id,g.goodsName,g.thumb,h.num FROM zy_store_house h,zy_goods g WHERE h.uId=$uId AND h.goodsId=g.id AND h.num>0")->result_array();
        return $res;
    }

    //获取当前仓库存储的所有物品名称以及对应的数量、售卖的单价
    public function get_sale_num($uId){
        $res = $this->db->query("SELECT g.id,g.goodsName,g.thumb,g.salePriceLD,g.salePriceJB,h.num FROM zy_store_house h,zy_goods g WHERE h.uId=$uId AND h.goodsId=g.id AND h.num>0")->result_array();
        return $res;
    }

    //获取当前仓库存储的配方 数量 图片
    public function get_peifang_num($uId){
        $res = $this->db->query("SELECT g.id as peifang_id,g.goodsName,g.thumb,h.num FROM zy_store_house h,zy_goods g WHERE h.uId=$uId AND g.goodsClass = 4 AND h.goodsId=g.id")->result_array();
        return $res;
    }

    public function get_yanye_form($peifang_id){
        $res = $this->db->query("SELECT a.yanyeId ,b.goodsName,b.thumb,a.yanyeNum,c.num as hasNum FROM zy_goods a ,zy_goods b, zy_store_house c WHERE a.id=$peifang_id AND b.id=(SELECT yanyeId FROM zy_goods WHERE id=$peifang_id) AND c.goodsId=(SELECT yanyeId FROM zy_goods WHERE id=$peifang_id)")->row_array();
        return $res;
    }

    public function get_spice_form($peifang_id){
        $res = $this->db->query("SELECT a.spiceId ,b.goodsName,b.thumb,a.spiceNum,c.num as hasNum FROM zy_goods a ,zy_goods b,zy_store_house c WHERE a.id=$peifang_id AND b.id=(SELECT spiceId FROM zy_goods WHERE id=$peifang_id) AND c.goodsId=(SELECT spiceId FROM zy_goods WHERE id=$peifang_id)")->row_array();
        return $res;
    }

    public function get_filter_form($peifang_id){
        $res = $this->db->query("SELECT a.filterId ,b.goodsName,b.thumb,a.filterNum,c.num as hasNum FROM zy_goods a ,zy_goods b,zy_store_house c WHERE a.id=$peifang_id AND b.id=(SELECT filterId FROM zy_goods WHERE id=$peifang_id) AND c.goodsId=(SELECT filterId FROM zy_goods WHERE id=$peifang_id)")->row_array();
        return $res;
    }

    public function get_packing_num($uId){
        $res = $this->db->query("SELECT g.id as packingg_id,g.goodsName,g.thumb,h.num FROM zy_store_house h,zy_goods g WHERE h.uId=$uId AND g.goodsClass = 8 AND h.goodsId=g.id")->result_array();
        return $res;
    }

    public function get_cigarette_form($packing_id){
        $res = $this->db->query("SELECT a.cigaretteId ,b.goodsName,b.thumb,a.cigaretteNum,c.num as hasNum FROM zy_goods a ,zy_goods b, zy_store_house c WHERE a.id=$packing_id AND b.id=(SELECT cigaretteId FROM zy_goods WHERE id=$packing_id) AND c.goodsId=(SELECT cigaretteId FROM zy_goods WHERE id=$packing_id)")->row_array();
        return $res;
    }



}
