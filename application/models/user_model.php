<?php
if (! defined('BASEPATH'))
    exit('No direct script access allowed');

// 会员
class user_model extends content_model
{
    function __construct ()
    {
        parent::__construct();
        $this->table = 'zy_user';
    }

    //根据md5Uid获取Uid
     function get_uid($md5_uid){
        $this->db->select('userId');
        $user_id = $this->db->get_where('zy_user', array('md5Uid'=>$md5_uid))->row_array();
        if($user_id){
            return $user_id['userId'];
        }else{
            return 0;
        }
    }




}
