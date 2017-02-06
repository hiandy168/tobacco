<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

// 会员
class land_model extends content_model
{
    function __construct ()
    {
        parent::__construct();

        $this->table = 'zy_land';
    }

    //
    public function get_current_all_plant($uId){
        $result = $this->db->query("SELECT a.id,a.landStatus,b.id AS plant_record_id ,b.goodsId,b.startPlantTime FROM zy_land a LEFT JOIN zy_plant_record b ON a.uId=$uId AND b.landId=a.id AND b.status!=3;")->result_array();
        return $result;
    }

}
