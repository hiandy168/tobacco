<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

// 会员
class goods_model extends content_model
{
    function __construct ()
    {
        parent::__construct();
        $this->table = 'zy_goods';
    }

    //从物品表zy_goods获取土地属性信息
    public function get_land_msg(){
        $res = $this->get_one(4,'id');
        return $res;
    }





}
