<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include 'base.php';
class plant extends base {

    public function __construct()
    {
        parent::__construct();
    }

    /**
     *接口名称：播种接口
     *接口地址：http://192.168.1.217/tobacco/index.php?d=api&c=plant&m=start_plant
     *接收方式：post
     *接收参数：
     *      md5_uid：'66e16d4c71fe0616c864c5d591ab0be7' 用户加密id(暂时写死)
     *      land_id：土地编号 0,1,2,3,4,5 ......
     *      seed_type：种子类型 1巴西种子，2海南种子，3古巴种子
     *返回参数：
     * 	    code：返回码 1正确, 0错误
     * 	    message：描述信息
     *      time:   时间戳
     *      id: 土地id
     *      plant_record_id :种植记录id
     **/

    public function start_plant(){
        //$uId = $_SESSION['userId'];
        //根据md5Uid获取uId
        $md5_uid = $this->input->post("md5_uid");
        $uId = $this->user_model->get_uid($md5_uid);
        if($uId){
            $land_id = $this->input->post("land_id");
            $seedType = $this->input->post("seed_type");
            $is_land_free = $this->get_land_status($land_id);//判断土地是否空闲
            //判断种子是否充足
            $num = $this->store_house_model->get_column_row('num',array('goodsId'=>$seedType,'uId'=>$uId));
            if($num['num']>=1){
                //更新仓库表,播下一颗种子，相应从仓库减去一颗
                $update_num['num'] = $num['num']-1;
                $affect = $this->store_house_model->update($update_num,array('goodsId' => $seedType,'uId'=>$uId));
                if($affect){
                    //更新土地表的种植状态
                    $update_land['landStatus'] = 1;
                    $res = $this->land_model->update($update_land,array('id' => $land_id,'uId'=>$uId));
                    if($res=1){
                        //zy_plant_record插入种植记录
                        $insert['goodsId'] = $seedType;
                        $insert['uId'] = $uId;
                        $insert['landId'] = $land_id;
                        $insert['startPlantTime'] = time();
                        $insert['status'] = 1;
                        $plant_record_id = $this->plant_record_model->insert($insert);
                        if($plant_record_id){
                            $result = array('code'=>1,'msg'=>'成功','time'=>time(),'id'=>$land_id,'plant_record_id'=>$plant_record_id);
                        }else{
                            $result = array('code'=>0,'msg'=>'保存种植表错误','time'=>time(),'id'=>$land_id,'plant_record_id'=>$plant_record_id);
                        }
                    }else{
                        $result = array('code'=>0,'msg'=>'更新土地表错误','time'=>time(),'id'=>$land_id);
                    }
                }else{
                    $result = array('code'=>0,'msg'=>'更新仓库表错误','time'=>time(),'id'=>$land_id);
                }
            }else{
                $result = array('code'=>0,'msg'=>'种子数量不足','time'=>time(),'id'=>$land_id);
            }
        }else{
            $result = array('code'=>0,'msg'=>'没有此用户','time'=>time());
        }
        echo json_encode($result);
    }

    /**
     *接口名称：种子成熟接口
     *接口地址：http://192.168.1.217/tobacco/index.php?d=api&c=plant&m=end_plant
     *接收方式：post
     *接收参数：
     *      md5_uid：'66e16d4c71fe0616c864c5d591ab0be7' 用户加密id(暂时写死)
     *      land_id：土地编号 0,1,2,3,4,5 .....
     *      plant_record_id：种植记录id
     *返回参数：
     * 	    code：返回码 1正确, 0错误
     * 	    message：描述信息
     *      id: 土地id
     *      plant_record_id :种植记录id
     **/
    public function end_plant(){
        //$uId = $_SESSION['userId'];
        //根据md5Uid获取uId
        $md5_uid = $this->input->post("md5_uid");
        $uId = $this->user_model->get_uid($md5_uid);
        if($uId){
            //判断种子种植时间是否已经到达成熟的时间
            $plant_record_id = $this->input->post("plant_record_id");
            $plant_arr = $this->plant_record_model->get_column_row('goodsId,status,startPlantTime', array('id'=>$plant_record_id,'uId'=>$uId));
            if($plant_arr['goodsId']&&$plant_arr['status']==1){ //判断种植记录是否存在，并且status==1
                $need_time = $this->goods_model->get_column_row('needTime', array('id'=>$plant_arr['goodsId']));
                if($need_time['needTime']){
                    if( (intval(time()) - intval($plant_arr['startPlantTime'])) >= $need_time['needTime']){
                        //更新土地表种植状态
                        $id = $this->input->post("land_id");
                        $update_land['landStatus'] = 2;
                        $res = $this->land_model->update($update_land,array('id' => $id,'uId'=>$uId));
                        if($res){
                            //更新种植记录表种植状态
                            $update_plant_record['endPlantTime'] = time();
                            $update_plant_record['status'] = 2;
                            $res = $this->plant_record_model->update($update_plant_record,array('id' => $plant_record_id,'uId'=>$uId));
                            if($res){
                                $result = array('code'=>1,'msg'=>'成功','time'=>time(),'id'=>$id,'plant_record_id'=>$plant_record_id);
                            }else{
                                $result = array('code'=>0,'msg'=>'更新种植记录表错误','time'=>time());
                            }
                        }else{
                            $result = array('code'=>0,'msg'=>'更新土地表错误','time'=>time());
                        }
                    }else{
                        $result = array('code'=>0,'msg'=>'种子尚未成熟','time'=>time());
                    }
                }else{
                    $result = array('code'=>0,'msg'=>'种子成熟时间未设置','time'=>time());
                }
            }else{
                $result = array('code'=>0,'msg'=>'种植记录不存在','time'=>time());
            }
        }else{
            $result = array('code'=>0,'msg'=>'没有此用户','time'=>time());
        }

        echo json_encode($result);
    }

    /**
     *接口名称：收割接口
     *接口地址：http://192.168.1.217/tobacco/index.php?d=api&c=plant&m=complete_plant
     *接收方式：post
     *接收参数：
     *      md5_uid：'66e16d4c71fe0616c864c5d591ab0be7' 用户加密id(暂时写死)
     *      land_id：土地编号 0,1,2,3,4,5 ......
     *      plant_record_id：种植记录id
     *返回参数：
     * 	    code：返回码 0错误，1正确，2仓库已满，暂缓收割
     * 	    message：描述信息
     *      id: 土地id
     *      plant_record_id :种植记录id
     **/
    public function complete_plant(){
        //$uId = $_SESSION['userId'];
        //根据md5Uid获取uId
        $md5_uid = $this->input->post("md5_uid");
        $uId = $this->user_model->get_uid($md5_uid);
        if($uId){
            //判断种子种植时间是否已经到达成熟的时间
            $plant_record_id = $this->input->post("plant_record_id");
            $plant_status = $this->plant_record_model->get_column_row('status', array('id'=>$plant_record_id,'uId'=>$uId));
            if($plant_status['status']==2){
                //判断仓库是否已经存满物品
                if($this->is_store_full($md5_uid)){
                    //更新土地表种植状态
                    $id = $this->input->post("land_id");
                    $update_land['landStatus'] = 0;//(收割完毕所以土地状态变为0：空闲状态)
                    $res = $this->land_model->update($update_land,array('id' => $id,'uId'=>$uId));
                    if($res){
                        //更新种植记录表种植状态
                        $update_plant_record['status'] = 3;//种植记录状态变为3：已经收割完毕。
                        $plant_record_id = $this->input->post("plant_record_id");
                        $res = $this->plant_record_model->update($update_plant_record,array('id' => $plant_record_id,'uId'=>$uId));
                        if($res){
                            //收割一颗种子，仓库多一张烟叶（一期只能采摘粗质烟叶）
                            $isexist = $this->db->get_where('zy_store_house', array('goodsId' => 5,'uId'=>$uId))->row_array();
                            if($isexist['goodsId']){
                                $update_num['num'] = $isexist['num']+1;
                                $update_num['updateTime'] = time();
                                $affect = $this->store_house_model->update($update_num,array('goodsId' => 5,'uId'=>$uId));
                                if($affect){
                                    $result = array('code'=>1,'msg'=>'成功','time'=>time(),'id'=>$id,'plant_record_id'=>$plant_record_id);
                                }else{
                                    $result = array('code'=>0,'msg'=>'更新仓库表错误','time'=>time());
                                }
                            }else{
                                $insert['goodsId'] = 5;
                                $insert['uId'] = $uId;
                                $insert['buyRecourdId'] = '';
                                $insert['num'] = 1;
                                $insert['updateTime'] = time();
                                $res = $this->store_house_model->insert($insert);
                                if($res){
                                    $result = array('code'=>1,'msg'=>'成功','time'=>time(),'id'=>$id,'plant_record_id'=>$plant_record_id);
                                }else{
                                    $result = array('code'=>0,'msg'=>'保存仓库表错误','time'=>time());
                                }
                            }
                        }else{
                            $result = array('code'=>0,'msg'=>'更新种植记录表错误','time'=>time());
                        }
                    }else{
                        $result = array('code'=>0,'msg'=>'更新土地表错误','time'=>time());
                    }
                }else{
                    $result = array('code'=>2,'msg'=>'仓库已满，请升级仓库','time'=>time());
                }
            }else{
                $result = array('code'=>0,'msg'=>'种子尚未成熟','time'=>time());
            }
        }else{
            $result = array('code'=>0,'msg'=>'没有此用户','time'=>time());
        }
        echo json_encode($result);
    }

    /**
     *接口名称：获取所有土地及其种植的物品、状态
     *接口地址：http://192.168.1.217/tobacco/index.php?d=admin&c=main&m=current_all_plant
     *接收方式：post
     *接收参数：
     *      md5_uid：'66e16d4c71fe0616c864c5d591ab0be7' 用户加密id(暂时写死)
     *返回参数：
     * 	    code：返回码 1正确, 0错误
     * 	    message：描述信息
     *      id: 土地id
     *      landStatus : 土地状态 0空闲、1正在种植、2已经成熟(但未收割)
     *      plant_record_id：种植记录id
     *      goodsId：种植物品id
     *      startPlantTime：开始种植时间
     *      needTime:   成长所需要的时长
     *      goodsName:  物品中文名称
     *
     **/
    public function current_all_plant(){
        $md5_uid = $this->input->post("md5_uid");
        $uId = $this->user_model->get_uid($md5_uid);
        if($uId){
            $res = $this->land_model->get_current_all_plant($uId);
            foreach($res as $key=>$value){
                if($value['landStatus']!=0){
                    $need_time = $this->goods_model->get_column_row('goodsName,needTime',array('id'=>$value['goodsId']));
                    $res[$key]['needTime'] = $need_time['needTime'];
                    $res[$key]['goodsName'] = $need_time['goodsName'];
                }else{
                    $res[$key]['needTime'] = null;
                    $res[$key]['goodsName'] = null;
                }
            }
            $result = array('code'=>1,'msg'=>'成功','time'=>time(),'data'=>$res);
        }else{
            $result = array('code'=>0,'msg'=>'没有此用户','time'=>time());
        }
        echo json_encode($result);
    }


    //判断某块土地状态
    private function get_land_status($land_id){
        $res = $this->land_model->get_column_row('landStatus',array('id'=>$land_id));
        return $res['landStatus'];
    }















}
