<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class base extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    //判断仓库是否已经存满物品
    function is_store_full($md5_uid){
        //先获取仓库总量
        $storeTotalCap = $this->get_store_tocap($md5_uid);
        //获取当前仓库存量
        $total_num = $this->current_store_num($md5_uid);
        if($storeTotalCap > $total_num){
            $remain_num = $storeTotalCap-$total_num;
            return $remain_num;   //未存满
        }else{
            return 0;   //已经存满
        }
    }

    //获取仓库总容量
    function get_store_tocap($md5_uid){
        $user_id = $this->user_model->get_column_row('storeTotalCap', array('md5Uid'=>$md5_uid));
        return $user_id['storeTotalCap'];
    }

    //获取当前仓库存量
    function current_store_num($md5_uid){
        $uId = $this->user_model->get_uid($md5_uid);
        if($uId){
            $res = $this->store_house_model->get_current_store_num($uId);
            $total_num = 0;
            if($res){
                //获取当前仓库存储量
                foreach($res as $value){
                    $total_num += $value['num'];
                }
            }
            return $total_num;
        }
    }
















}
