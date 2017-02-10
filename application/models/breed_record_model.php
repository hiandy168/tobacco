<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

// 会员
class breed_record_model extends content_model
{
    function __construct ()
    {
        parent::__construct();

        $this->table = 'zy_breed_record';
    }

    public function get_current_all_breed($uId){
        $result = $this->db->query("SELECT a.id AS breed_record_id,a.goodsId,a.startBreedTime,a.endBreedTime,a.status,b.goodsName FROM zy_breed_record a , zy_goods b WHERE a.uId=$uId AND a.status!=0 AND a.status !=3 AND b.id=a.goodsId ORDER BY a.id ASC ;")->result_array();
        return $result;
    }



}
