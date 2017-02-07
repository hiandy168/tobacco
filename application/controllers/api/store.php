<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include 'base.php';
class store extends base {

    public function __construct()
    {
        parent::__construct();
    }

    /**
     *接口名称：获取仓库里面的种子
     *接口地址：http://192.168.1.217/tobacco/index.php?d=api&c=store&m=get_seed
     *接收方式：post
     *接收参数：
     *      md5_uid：'66e16d4c71fe0616c864c5d591ab0be7' 用户加密id(暂时写死)
     *返回参数：
     * 	    code：返回码 1正确, 0错误
     * 	    message：描述信息
     *      id: 物品id
     *      goodsName :物品名称
     *      num：物品数量
     **/
    public function get_seed(){
        //根据md5Uid获取uId
        $md5_uid = $this->input->post("md5_uid");
        $uId = $this->user_model->get_uid($md5_uid);
        if($uId){
            $res = $this->store_house_model->get_seed_num();
            if($res){
                $result = array('code'=>1,'msg'=>'成功','time'=>time(),'data'=>$res);
            }else{
                $result = array('code'=>0,'msg'=>'物品不存在','time'=>time());
            }
        }else{
            $result = array('code'=>0,'msg'=>'没有此用户','time'=>time());
        }
        echo json_encode($result);
    }

    /**
     *接口名称：获取仓库里面的所有物品
     *接口地址：http://192.168.1.217/tobacco/index.php?d=api&c=store&m=get_store_all
     *接收方式：post
     *接收参数：
     *      md5_uid：'66e16d4c71fe0616c864c5d591ab0be7' 用户加密id(暂时写死)
     *返回参数：
     * 	    code：返回码 1正确, 0错误
     * 	    message：描述信息
     *      id: 物品id
     *      goodsName :物品名称
     *      num：物品数量
     *      total_num：仓库当前存储量
     *      storeTotalCap：仓库总容量
     **/
    public function get_store_all(){
        //根据md5Uid获取uId
        $md5_uid = $this->input->post("md5_uid");
        $uId = $this->user_model->get_uid($md5_uid);
        if($uId){
            $res = $this->store_house_model->get_all_num();//获取仓库所有物品名称和对应数量
            $temp = array();
            foreach($res as $key=>$value){
                $temp[$value['id']] = $value;
            }
            $data['goods'] = $temp;
            if($res){
                //获取当前仓库存储量
                $data['total_num'] = $this->current_store_num($md5_uid);
                //获取仓库总容量
                $data['storeTotalCap'] = $this->get_store_tocap($md5_uid);

                $result = array('code'=>1,'msg'=>'成功','time'=>time(),'data'=>$data);
            }else{
                $result = array('code'=>0,'msg'=>'物品不存在','time'=>time());
            }
        }else{
            $result = array('code'=>0,'msg'=>'没有此用户','time'=>time());
        }
        echo json_encode($result);
    }
















}
