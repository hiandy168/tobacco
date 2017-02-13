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
     *      thumb: 物品图片地址
     **/
    public function get_seed(){
        //根据md5Uid获取uId
        $md5_uid = $this->input->post("md5_uid");
        $uId = $this->user_model->get_uid($md5_uid);
        if($uId){
            $res = $this->store_house_model->get_seed_num($uId);
            if($res){
                $result = array('code'=>1,'msg'=>'成功','time'=>time(),'data'=>$res);
            }else{
                $result = array('code'=>0,'msg'=>'物品不存在','time'=>time());
            }
        }else{
            $result = array('code'=>0,'msg'=>'没有此用户','time'=>time());
        }
        echo json_encode($result,JSON_UNESCAPED_UNICODE);
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
            $res = $this->store_house_model->get_all_num($uId);//获取仓库所有物品名称和对应数量
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
        echo json_encode($result,JSON_UNESCAPED_UNICODE);
    }

    /**
     *接口名称：获取所有配方（点击加工厂，弹出所有加工配方）
     *接口地址：http://192.168.1.217/tobacco/index.php?d=api&c=store&m=peifang_lists
     *接收方式：post
     *接收参数：
     *      md5_uid：'66e16d4c71fe0616c864c5d591ab0be7' 用户加密id(暂时写死)
     *返回参数：
     * 	    code：返回码 0错误，1正确
     * 	    message：描述信息
     *      peifang_id：配方id
     *      thumb：配方图片
     **/
    public function peifang_lists(){
        //根据md5Uid获取uId
        $md5_uid = $this->input->post("md5_uid");
        $uId = $this->user_model->get_uid($md5_uid);
        if($uId){
            $res = $this->store_house_model->get_peifang_num($uId);
            if($res){
                $result = array('code'=>1,'msg'=>'成功','time'=>time(),'data'=>$res);
            }else{
                $result = array('code'=>0,'msg'=>'物品不存在','time'=>time());
            }
        }else{
            $result = array('code'=>0,'msg'=>'没有此用户','time'=>time());
        }
        echo json_encode($result,JSON_UNESCAPED_UNICODE);
    }

    /**
     *接口名称：获取所有配方组成成分（点击配方，弹出所有配方组成成分）
     *接口地址：http://192.168.1.217/tobacco/index.php?d=api&c=store&m=peifang_form
     *接收方式：post
     *接收参数：
     *      md5_uid：'66e16d4c71fe0616c864c5d591ab0be7' 用户加密id(暂时写死)
     *      peifang_id：配方id
     *返回参数：
     * 	    code：返回码 0错误，1正确
     * 	    message：描述信息
     *      time: 时间戳
     *      data:
     *          yanyeId: 烟叶id
     *          goodsName: 烟叶名称
     *          thumb：烟叶图片
     *          yanyeNum： 需要烟叶的数量
     *          hasNum：当前仓库存储的数量
     *
     *          spiceId： 香料id
     *          goodsName：香料名称
     *          thumb：香料图片
     *          spiceNum： 所需香料的数量
     *          hasNum：当前仓库存储的数量
     *
     *          filterId： 滤嘴id
     *          goodsName：滤嘴名称
     *          thumb：滤嘴图片
     *          filterNum： 所需滤嘴的数量
     *          hasNum：当前仓库存储的数量
     **/
    public function peifang_form(){
        //根据md5Uid获取uId
        $md5_uid = $this->input->post("md5_uid");
        $uId = $this->user_model->get_uid($md5_uid);
        if($uId){
            $peifang_id = $this->input->post("peifang_id");
            $res[0] = $this->store_house_model->get_yanye_form($peifang_id);
            $res[1] = $this->store_house_model->get_spice_form($peifang_id);
            $res[2] = $this->store_house_model->get_filter_form($peifang_id);
            if($res){
                $result = array('code'=>1,'msg'=>'成功','time'=>time(),'data'=>$res);
            }else{
                $result = array('code'=>0,'msg'=>'配方不存在','time'=>time());
            }
        }else{
            $result = array('code'=>0,'msg'=>'没有此用户','time'=>time());
        }
        echo json_encode($result,JSON_UNESCAPED_UNICODE);
    }

    /**
     *接口名称：获取所有包装方式（点击包装工厂，弹出所有包装方式）
     *接口地址：http://192.168.1.217/tobacco/index.php?d=api&c=store&m=packing_lists
     *接收方式：post
     *接收参数：
     *      md5_uid：'66e16d4c71fe0616c864c5d591ab0be7' 用户加密id(暂时写死)
     *返回参数：
     * 	    code：返回码 0错误，1正确
     * 	    message：描述信息
     *      packing_id：包装id
     *      thumb：配方图片
     **/
    public function packing_lists(){
        //根据md5Uid获取uId
        $md5_uid = $this->input->post("md5_uid");
        $uId = $this->user_model->get_uid($md5_uid);
        if($uId){
            $res = $this->store_house_model->get_packing_num($uId);
            if($res){
                $result = array('code'=>1,'msg'=>'成功','time'=>time(),'data'=>$res);
            }else{
                $result = array('code'=>0,'msg'=>'物品不存在','time'=>time());
            }
        }else{
            $result = array('code'=>0,'msg'=>'没有此用户','time'=>time());
        }
        echo json_encode($result,JSON_UNESCAPED_UNICODE);
    }

    /**
     *接口名称：获取所有包装组成成分（点击包装，弹出所有包装组成成分）
     *接口地址：http://192.168.1.217/tobacco/index.php?d=api&c=store&m=packing_form
     *接收方式：post
     *接收参数：
     *      md5_uid：'66e16d4c71fe0616c864c5d591ab0be7' 用户加密id(暂时写死)
     *      packing_id：配方id
     *返回参数：
     * 	    code：返回码 0错误，1正确
     * 	    message：描述信息
     *      time: 时间戳
     *      data:
     *          cigaretteId: 烟支id
     *          goodsName: 烟支名称
     *          thumb：烟支图片
     *          cigaretteNum： 需要烟支的数量
     *          hasNum：当前仓库存储的数量
     **/
    public function packing_form(){
        //根据md5Uid获取uId
        $md5_uid = $this->input->post("md5_uid");
        $uId = $this->user_model->get_uid($md5_uid);
        if($uId){
            $packing_id = $this->input->post("packing_id");
            $res[0] = $this->store_house_model->get_cigarette_form($packing_id);
            if($res){
                $result = array('code'=>1,'msg'=>'成功','time'=>time(),'data'=>$res);
            }else{
                $result = array('code'=>0,'msg'=>'配方不存在','time'=>time());
            }
        }else{
            $result = array('code'=>0,'msg'=>'没有此用户','time'=>time());
        }
        echo json_encode($result,JSON_UNESCAPED_UNICODE);
    }







}
