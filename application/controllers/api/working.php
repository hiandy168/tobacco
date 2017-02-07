<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include 'base.php';
class working extends base {

    public function __construct()
    {
        parent::__construct();
    }

    /**
     *接口名称：开始加工接口
     *接口地址：http://192.168.1.217/tobacco/index.php?d=api&c=working&m=start_working
     *接收方式：post
     *接收参数：
     *      md5_uid：'66e16d4c71fe0616c864c5d591ab0be7' 用户加密id(暂时写死)
     *      peifang_type：配方类型 0基础配方，1改良配方，2经典配方
     *返回参数：
     * 	    code：返回码 0错误，1正确
     * 	    message：描述信息
     *      working_record_id：加工过程id
     **/
    public function start_working(){
        //根据md5Uid获取uId
        $md5_uid = $this->input->post("md5_uid");
        $uId = $this->user_model->get_uid($md5_uid);
        if($uId){
            $peifang_type = $this->input->post("peifang_type");
            //查询是否有此配方,并且仓库里面已经存有
            $peifang_list = $this->goods_model->get_column_row("*",array("goodsClass"=>4,"goodsType"=>$peifang_type));
            $peifang_num = $this->store_house_model->get_column_row("num",array("goodsId"=>$peifang_list['id']));
            if(!empty($peifang_list)&&$peifang_num['num']>0){
                //根据配方，查询仓库里的烟叶是否>=配方需要的烟叶数量
                $yanye_num = $this->store_house_model->get_column_row("num",array("goodsId"=>$peifang_list['yanyeId']));
                if($yanye_num['num']>=$peifang_list['yanyeNum']){
                    //根据配方，查询仓库里的香料是否>=配方需要的香料数量
                    $spice_num = $this->store_house_model->get_column_row("num",array("goodsId"=>$peifang_list['spiceId']));
                    if($spice_num['num']>=$peifang_list['spiceNum']){
                        //根据配方，查询仓库里的滤嘴是否>=配方需要的滤嘴数量
                        $filter_num = $this->store_house_model->get_column_row("num",array("goodsId"=>$peifang_list['filterId']));
                        if($filter_num['num']>=$peifang_list['filterNum']){
                            //成功加工一支烟，仓库减去相应的原料
                            $update_yanye_num['num'] = $yanye_num['num']-$peifang_list['yanyeNum'];
                            $update_yanye_num['updateTime'] = time();
                            $affect1 = $this->store_house_model->update($update_yanye_num,array('goodsId' => $peifang_list['yanyeId'],'uId'=>$uId));

                            $update_spice_num['num'] = $spice_num['num']-$peifang_list['spiceNum'];
                            $update_spice_num['updateTime'] = time();
                            $affect2 = $this->store_house_model->update($update_spice_num,array('goodsId' => $peifang_list['spiceId'],'uId'=>$uId));

                            $update_filter_num['num'] = $filter_num['num']-$peifang_list['filterNum'];
                            $update_filter_num['updateTime'] = time();
                            $affect3 = $this->store_house_model->update($update_filter_num,array('goodsId' => $peifang_list['filterId'],'uId'=>$uId));

                            if($affect1&&$affect2&&$affect3){
                                //保存加工记录
                                $insert['uId'] = $uId;
                                $insert['goodsId'] = $peifang_list['becomeGoodsId'];
                                $insert['peifangId'] = $peifang_list['id'];
                                $insert['startWorkingTime'] = time();
                                $insert['status'] = 1;
                                $working_record_id = $this->working_record_model->insert($insert);
                                if($working_record_id){
                                    $result = array('code'=>1,'msg'=>'成功','time'=>time(),'working_record_id'=>$working_record_id);
                                }else{
                                    $result = array('code'=>0,'msg'=>'保存加工记录表错误','time'=>time());
                                }
                            }else{
                                $result = array('code'=>0,'msg'=>'仓库库存更新成功','time'=>time());
                            }
                        }else{
                            $result = array('code'=>0,'msg'=>'滤嘴不足','time'=>time());
                        }
                    }else{
                        $result = array('code'=>0,'msg'=>'香料不足','time'=>time());
                    }
                }else{
                    $result = array('code'=>0,'msg'=>'烟叶不足','time'=>time());
                }
            }else{
                $result = array('code'=>0,'msg'=>'没有此配方','time'=>time());
            }
        }else{
            $result = array('code'=>0,'msg'=>'没有此用户','time'=>time());
        }
        echo json_encode($result);
    }

    /**
     *接口名称：加工结束接口
     *接口地址：http://192.168.1.217/tobacco/index.php?d=api&c=working&m=end_working
     *接收方式：post
     *接收参数：
     *      md5_uid：'66e16d4c71fe0616c864c5d591ab0be7' 用户加密id(暂时写死)
     *      working_record_id：加工过程id
     *返回参数：
     * 	    code：返回码 0错误，1正确
     * 	    message：描述信息
     *      working_id：加工过程id
     **/
    public function end_working(){
        $md5_uid = $this->input->post("md5_uid");
        $uId = $this->user_model->get_uid($md5_uid);
        if($uId){
            //判断加工时间是否已经到达完成的时间
            $working_record_id = $this->input->post("working_record_id");
            $working_arr = $this->working_record_model->get_column_row('peifangId,status,startWorkingTime', array('id'=>$working_record_id,'uId'=>$uId));
            if($working_arr['peifangId']&&$working_arr['status']==1){ //判断加工记录是否存在，并且status==1
                $need_time = $this->goods_model->get_column_row('needTime', array('id'=>$working_arr['peifangId']));
                if($need_time['needTime']){
                    if( (intval(time()) - intval($working_arr['startWorkingTime'])) >= $need_time['needTime']){
                        //更新加工记录表加工状态
                        $update_working_record['endWorkingTime'] = time();
                        $update_working_record['status'] = 2;
                        $res = $this->working_record_model->update($update_working_record,array('id' => $working_record_id,'uId'=>$uId));
                        if($res){
                            $result = array('code'=>1,'msg'=>'成功','time'=>time(),'working_record_id'=>$working_record_id);
                        }else{
                            $result = array('code'=>0,'msg'=>'更新加工记录表错误','time'=>time());
                        }

                    }else{
                        $result = array('code'=>0,'msg'=>'加工尚未完成','time'=>time());
                    }
                }else{
                    $result = array('code'=>0,'msg'=>'配方时间未设置','time'=>time());
                }
            }else{
                $result = array('code'=>0,'msg'=>'加工记录不存在','time'=>time());
            }
        }else{
            $result = array('code'=>0,'msg'=>'没有此用户','time'=>time());
        }

        echo json_encode($result);
    }

    /**
     *接口名称：加工完成后，将成品烟存入仓库接口
     *接口地址：http://192.168.1.217/tobacco/index.php?d=api&c=working&m=complete_working
     *接收方式：post
     *接收参数：
     *      md5_uid：'66e16d4c71fe0616c864c5d591ab0be7' 用户加密id(暂时写死)
     *      working_record_id：加工记录id
     *返回参数：
     * 	    code：返回码 0错误，1正确，2仓库已满，暂缓收割
     * 	    message：描述信息
     *      working_record_id :加工记录id
     **/
    public function complete_working(){
        //$uId = $_SESSION['userId'];
        //根据md5Uid获取uId
        $md5_uid = $this->input->post("md5_uid");
        $uId = $this->user_model->get_uid($md5_uid);
        if($uId){
            //判断加工时间是否已经到达完成的时间
            $working_record_id = $this->input->post("working_record_id");
            $working_status = $this->working_record_model->get_column_row('status,goodsId', array('id'=>$working_record_id,'uId'=>$uId));
            if($working_status['status']==2){
                //判断仓库是否已经存满物品
                if($this->is_store_full($md5_uid)){
                    //更新加工记录表加工状态
                    $update_working_record['status'] = 3;//加工记录状态变为3：已经存入仓库。
                    $res = $this->working_record_model->update($update_working_record,array('id' => $working_record_id,'uId'=>$uId));
                    if($res){
                        //收获一支烟，仓库多一支烟
                        $isexist = $this->db->get_where('zy_store_house', array('goodsId' => $working_status['goodsId'],'uId'=>$uId))->row_array();
                        if($isexist['goodsId']){
                            $update_num['num'] = $isexist['num']+1;
                            $update_num['updateTime'] = time();
                            $affect = $this->store_house_model->update($update_num,array('goodsId' => $working_status['goodsId'],'uId'=>$uId));
                            if($affect){
                                $result = array('code'=>1,'msg'=>'成功','time'=>time(),'working_record_id'=>$working_record_id);
                            }else{
                                $result = array('code'=>0,'msg'=>'更新仓库表错误','time'=>time());
                            }
                        }else{
                            $insert['goodsId'] = $working_status['goodsId'];
                            $insert['uId'] = $uId;
                            $insert['num'] = 1;
                            $insert['updateTime'] = time();
                            $res = $this->store_house_model->insert($insert);
                            if($res){
                                $result = array('code'=>1,'msg'=>'成功','time'=>time(),'working_record_id'=>$working_record_id);
                            }else{
                                $result = array('code'=>0,'msg'=>'保存仓库表错误','time'=>time());
                            }
                        }
                    }else{
                        $result = array('code'=>0,'msg'=>'更新加工记录表错误','time'=>time());
                    }
                }else{
                    $result = array('code'=>2,'msg'=>'仓库已满，请升级仓库','time'=>time());
                }
            }else{
                $result = array('code'=>0,'msg'=>'加工尚未完成','time'=>time());
            }
        }else{
            $result = array('code'=>0,'msg'=>'没有此用户','time'=>time());
        }
        echo json_encode($result);
    }
















}
