<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include 'base.php';
class packing extends base {

    public function __construct()
    {
        parent::__construct();
    }

    /**
     *接口名称：开始包装接口
     *接口地址：http://192.168.1.217/tobacco/index.php?d=admin&c=main&m=start_packing
     *接收方式：post
     *接收参数：
     *      md5_uid：'66e16d4c71fe0616c864c5d591ab0be7' 用户加密id(暂时写死)
     *      packing_type：包装类型 0海韵包装，1鸿韵包装，2珍品包装
     *返回参数：
     * 	    code：返回码 0错误，1正确
     * 	    message：描述信息
     *      packing_record_id：包装过程id
     **/
    public function start_packing(){
        //根据md5Uid获取uId
        $md5_uid = $this->input->post("md5_uid");
        $uId = $this->user_model->get_uid($md5_uid);
        if($uId){
            $packing_type = $this->input->post("packing_type");
            //查询是否有此包装,并且仓库里面已经存有
            $packing_list = $this->goods_model->get_column_row("*",array("goodsClass"=>8,"goodsType"=>$packing_type));
            $packing_num = $this->store_house_model->get_column_row("num",array("goodsId"=>$packing_list['id']));
            if(!empty($packing_list)&&$packing_num['num']>0){
                //根据包装方式，查询仓库里的烟是否>=包装需要的烟数量
                $yan_num = $this->store_house_model->get_column_row("num",array("goodsId"=>$packing_list['cigaretteId']));
                if($yan_num['num']>=$packing_list['cigaretteNum']){
                    //成功包装一盒烟，仓库减去相应的原料
                    $update_yan_num['num'] = $yan_num['num']-$packing_list['cigaretteNum'];
                    $update_yan_num['updateTime'] = time();
                    $affect1 = $this->store_house_model->update($update_yan_num,array('goodsId' => $packing_list['cigaretteId'],'uId'=>$uId));

                    $update_packing_num['num'] = $packing_num['num']-1;//包装盒默认减 1
                    $update_packing_num['updateTime'] = time();
                    $affect2 = $this->store_house_model->update($update_packing_num,array('goodsId' => $packing_list['id'],'uId'=>$uId));

                    if($affect1&&$affect2){
                        //保存包装记录
                        $insert['uId'] = $uId;
                        $insert['goodsId'] = $packing_list['becomeGoodsId'];
                        $insert['packingId'] = $packing_list['id'];
                        $insert['startPackingTime'] = time();
                        $insert['status'] = 1;
                        $packing_record_id = $this->packing_record_model->insert($insert);
                        if($packing_record_id){
                            $result = array('code'=>1,'msg'=>'成功','time'=>time(),'packing_record_id'=>$packing_record_id);
                        }else{
                            $result = array('code'=>0,'msg'=>'保存包装记录表错误','time'=>time());
                        }
                    }else{
                        $result = array('code'=>0,'msg'=>'仓库库存更新成功','time'=>time());
                    }

                }else{
                    $result = array('code'=>0,'msg'=>'烟不足','time'=>time());
                }
            }else{
                $result = array('code'=>0,'msg'=>'没有此包装方式','time'=>time());
            }
        }else{
            $result = array('code'=>0,'msg'=>'没有此用户','time'=>time());
        }
        echo json_encode($result);
    }

    /**
     *接口名称：包装结束接口
     *接口地址：http://192.168.1.217/tobacco/index.php?d=api&c=packing&m=end_packing
     *接收方式：post
     *接收参数：
     *      md5_uid：'66e16d4c71fe0616c864c5d591ab0be7' 用户加密id(暂时写死)
     *      packing_record_id：包装过程id
     *返回参数：
     * 	    code：返回码 0错误，1正确
     * 	    message：描述信息
     *      packing_record_id：包装过程id
     **/
    public function end_packing(){
        $md5_uid = $this->input->post("md5_uid");
        $uId = $this->user_model->get_uid($md5_uid);
        if($uId){
            //判断包装时间是否已经到达完成的时间
            $packing_record_id = $this->input->post("packing_record_id");
            $packing_arr = $this->packing_record_model->get_column_row('packingId,status,startPackingTime', array('id'=>$packing_record_id,'uId'=>$uId));
            if($packing_arr['packingId']&&$packing_arr['status']==1){ //判断加工记录是否存在，并且status==1
                $need_time = $this->goods_model->get_column_row('needTime', array('id'=>$packing_arr['packingId']));
                if($need_time['needTime']){
                    if( (intval(time()) - intval($packing_arr['startPackingTime'])) >= $need_time['needTime']){
                        //更新包装记录表加工状态
                        $update_packing_record['endPackingTime'] = time();
                        $update_packing_record['status'] = 2;
                        $res = $this->packing_record_model->update($update_packing_record,array('id' => $packing_record_id,'uId'=>$uId));
                        if($res){
                            $result = array('code'=>1,'msg'=>'成功','time'=>time(),'packing_record_id'=>$packing_record_id);
                        }else{
                            $result = array('code'=>0,'msg'=>'更新包装记录表错误','time'=>time());
                        }

                    }else{
                        $result = array('code'=>0,'msg'=>'包装尚未完成','time'=>time());
                    }
                }else{
                    $result = array('code'=>0,'msg'=>'包装时间未设置','time'=>time());
                }
            }else{
                $result = array('code'=>0,'msg'=>'包装记录不存在','time'=>time());
            }
        }else{
            $result = array('code'=>0,'msg'=>'没有此用户','time'=>time());
        }

        echo json_encode($result);
    }

    /**
     *接口名称：包装完成后，将成品烟存入仓库接口
     *接口地址：http://192.168.1.217/tobacco/index.php?d=api&c=packing&m=complete_packing
     *接收方式：post
     *接收参数：
     *      md5_uid：'66e16d4c71fe0616c864c5d591ab0be7' 用户加密id(暂时写死)
     *      packing_record_id：包装记录id
     *返回参数：
     * 	    code：返回码 0错误，1正确，2仓库已满，暂缓收割
     * 	    message：描述信息
     *      packing_record_id :包装记录id
     **/
    public function complete_packing(){
        //$uId = $_SESSION['userId'];
        //根据md5Uid获取uId
        $md5_uid = $this->input->post("md5_uid");
        $uId = $this->user_model->get_uid($md5_uid);
        if($uId){
            //判断包装时间是否已经到达完成的时间
            $packing_record_id = $this->input->post("packing_record_id");
            $packing_status = $this->packing_record_model->get_column_row('status,goodsId', array('id'=>$packing_record_id,'uId'=>$uId));
            if($packing_status['status']==2){
                //判断仓库是否已经存满物品
                if($this->is_store_full($md5_uid)){
                    //更新加工记录表加工状态
                    $update_packing_record['status'] = 3;//加工记录状态变为3：已经存入仓库。
                    $res = $this->packing_record_model->update($update_packing_record,array('id' => $packing_record_id,'uId'=>$uId));
                    if($res){
                        //收获一盒烟，仓库多一盒烟
                        $isexist = $this->db->get_where('zy_store_house', array('goodsId' => $packing_status['goodsId'],'uId'=>$uId))->row_array();
                        if($isexist['goodsId']){
                            $update_num['num'] = $isexist['num']+1;
                            $update_num['updateTime'] = time();
                            $affect = $this->store_house_model->update($update_num,array('goodsId' => $packing_status['goodsId'],'uId'=>$uId));
                            if($affect){
                                $result = array('code'=>1,'msg'=>'成功','time'=>time(),'packing_record_id'=>$packing_record_id);
                            }else{
                                $result = array('code'=>0,'msg'=>'更新仓库表错误','time'=>time());
                            }
                        }else{
                            $insert['goodsId'] = $packing_status['goodsId'];
                            $insert['uId'] = $uId;
                            $insert['num'] = 1;
                            $insert['updateTime'] = time();
                            $res = $this->store_house_model->insert($insert);
                            if($res){
                                $result = array('code'=>1,'msg'=>'成功','time'=>time(),'packing_record_id'=>$packing_record_id);
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
                $result = array('code'=>0,'msg'=>'包装尚未完成','time'=>time());
            }
        }else{
            $result = array('code'=>0,'msg'=>'没有此用户','time'=>time());
        }
        echo json_encode($result);
    }
















}
