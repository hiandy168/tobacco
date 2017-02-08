<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

// 会员
class working_record_model extends content_model
{
    function __construct ()
    {
        parent::__construct();
        $this->table = 'zy_working_record';
    }

    public function get_current_all_working($uId){
        $result = $this->db->query("SELECT a.id AS working_record_id,a.goodsId,a.peifangId,a.startWorkingTime,a.endWorkingTime,a.status,b.goodsName FROM zy_working_record a JOIN zy_goods b ON a.uId=$uId AND b.id=a.peifangId AND a.status!=0 AND a.status !=3 ORDER BY a.id ASC ;")->result_array();
        return $result;
    }



}
