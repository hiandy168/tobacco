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
     *      seed_id：种子ID 1a种子，2b种子，3c种子
     *返回参数：
     * 	    code：返回码 1正确, 0错误
     * 	    message：描述信息
     *      time:   时间戳
     *      land_id: 土地id
     *      plant_record_id :种植记录id
     *      begin_thumb : 刚播种时的苗子的图片
     **/

    public function start_plant(){
        //$uId = $_SESSION['userId'];
        //根据md5Uid获取uId
        $md5_uid = $this->input->post("md5_uid");
        $uId = $this->user_model->get_uid($md5_uid);
        if($uId){
            $land_id = $this->input->post("land_id");
            $seed_id = $this->input->post("seed_id");
            $is_land_free = $this->get_land_status($land_id);//判断土地是否空闲
            if($is_land_free){
                $result = array('code'=>0,'msg'=>'土地不是空闲状态','time'=>time());
                echo json_encode($result,JSON_UNESCAPED_UNICODE);
                exit;
            }
            //判断种子是否充足
            $num = $this->store_house_model->get_column_row('num',array('goodsId'=>$seed_id,'uId'=>$uId));
            if($num['num']>=1){
                //更新仓库表,播下一颗种子，相应从仓库减去一颗
                $update_num['num'] = $num['num']-1;
                $affect = $this->store_house_model->update($update_num,array('goodsId' => $seed_id,'uId'=>$uId));
                if($affect){
                    //更新土地表的种植状态
                    $update_land['landStatus'] = 1;
                    $res = $this->land_model->update($update_land,array('id' => $land_id,'uId'=>$uId));
                    if($res=1){
                        //zy_plant_record插入种植记录
                        $insert['goodsId'] = $seed_id;
                        $insert['uId'] = $uId;
                        $insert['landId'] = $land_id;
                        $insert['startPlantTime'] = time();
                        $insert['status'] = 1;
                        $plant_record_id = $this->plant_record_model->insert($insert);
                        if($plant_record_id){
                            //获取该种子苗子的图片
                            $begin_thumb = $this->goods_model->get_column_row('beginThumb',array('id'=>$seed_id));
                            $result = array('code'=>1,'msg'=>'成功','time'=>time(),'land_id'=>$land_id,'plant_record_id'=>$plant_record_id,'begin_thumb'=>$begin_thumb['beginThumb']);
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
        echo json_encode($result,JSON_UNESCAPED_UNICODE);
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
     *      land_id: 土地id
     *      plant_record_id :种植记录id
     *      complete_thumb : 作物成熟时的图片
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
                        $land_id = $this->input->post("land_id");
                        $update_land['landStatus'] = 2;
                        $res = $this->land_model->update($update_land,array('id' => $land_id,'uId'=>$uId));
                        if($res){
                            //更新种植记录表种植状态
                            $update_plant_record['endPlantTime'] = time();
                            $update_plant_record['status'] = 2;
                            $res = $this->plant_record_model->update($update_plant_record,array('id' => $plant_record_id,'uId'=>$uId));
                            if($res){
                                //获取该作物成熟时的图片
                                $complete_thumb = $this->goods_model->get_column_row('completeThumb',array('id'=>$plant_arr['goodsId']));
                                $result = array('code'=>1,'msg'=>'成功','time'=>time(),'land_id'=>$land_id,'plant_record_id'=>$plant_record_id,'complete_thumb'=>$complete_thumb['completeThumb']);
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

        echo json_encode($result,JSON_UNESCAPED_UNICODE);
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
                    $land_id = $this->input->post("land_id");
                    $update_land['landStatus'] = 0;//(收割完毕所以土地状态变为0：空闲状态)
                    $res = $this->land_model->update($update_land,array('id' => $land_id,'uId'=>$uId));
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
                                    $result = array('code'=>1,'msg'=>'成功','time'=>time(),'land_id'=>$land_id,'plant_record_id'=>$plant_record_id);
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
                                    $result = array('code'=>1,'msg'=>'成功','time'=>time(),'land_id'=>$land_id,'plant_record_id'=>$plant_record_id);
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
        echo json_encode($result,JSON_UNESCAPED_UNICODE);
    }

    /**
     *接口名称：获取所有土地及其种植的物品、状态；加工状态；包装状态；种子培育状态；配方研究状态
     *接口地址：http://192.168.1.217/tobacco/index.php?d=api&c=plant&m=initialize
     *接收方式：post
     *接收参数：
     *      md5_uid：'66e16d4c71fe0616c864c5d591ab0be7' 用户加密id(暂时写死)
     *返回参数：
     * 	    code：返回码 1正确, 0错误
     * 	    message：描述信息
     *      time:  时间戳
     *      data:
     *          plant: 所有种植状态
     *              land_id: 土地id
     *              landStatus : 土地状态 0空闲、1正在种植、2已经成熟(但未收割)
     *              plant_record_id：种植记录id
     *              goodsId：种植物品（种子）id
     *              startPlantTime：开始种植时间
     *              endPlantTime：结束种植时间
     *              needTime:   成长所需要的时长
     *              goodsName:  种植物品（种子）中文名称
     *              becomeGoodsId:  成熟之后的物品（叶子）id（种子成熟后，变为叶子）
     *              becomeGoodsName:  成熟之后的物品（叶子）的中文名称
     *          working: 所有加工状态
     *              working_record_id: 加工id
     *              peifangId : 加工所用的配方id
     *              startWorkingTime：开始加工时间
     *              endWorkingTime：结束加工时间
     *              status：加工状态 （1正在加工、2加工完成）
     *              goodsName：加工所用的配方的中文名称
     *              needTime:   加工所需要的时长
     *              becomeGoodsId:  加工完成之后的物品（烟支）的id
     *              becomeGoodsName:  加工完成之后的物品（烟支）的中文名称（烟叶经过加工后，变为“海韵”烟支）
     *          packing: 所有包装状态
     *              packing_record_id: 包装记录id
     *              packingId : 包装方式id
     *              startPackingTime：开始包装时间
     *              endPackingTime：结束包装时间
     *              status：包装状态 （1正在包装、2包装完成）
     *              goodsName：包装方式的中文名称
     *              needTime:   包装所需要的时长
     *              becomeGoodsId:  包装完成之后的物品（盒装烟）的id
     *              becomeGoodsName:  包装完成之后的物品（盒装烟）的中文名称（烟支经过包装后，变为“海韵”盒装烟）
     *          breed:  所有种子培育状态
     *              breed_record_id: 种子培育记录id
     *              startBreedTime：开始培育时间
     *              endBreedTime：结束培育时间（当培育状态为正在培育时，即尚未培育完成，该值为空）
     *              status：培育状态 （1正在培育、2培育完成）
     *              goodsId：所要培育的种子的id
     *              goodsName：所要培育的种子的中文名称
     *              needTime:   培育种子所需要的时长
     *          research:  所有配方研究状态
     *              research_record_id: 配方研究记录id
     *              startResearchTime：开始研究时间
     *              endResearchTime：结束研究时间（当培育状态为正在培育时，即尚未培育完成，该值为空）
     *              status：研究状态 （1正在研究、2培育研究）
     *              goodsId：所要研究的配方的id
     *              goodsName：所要研究的配方的中文名称
     *              needTime:   配方研究所需要的时长
     **/
    public function initialize(){
        $md5_uid = $this->input->post("md5_uid");
        $uId = $this->user_model->get_uid($md5_uid);
        $time  = time();
        if($uId){
            //种植
            $res = $this->land_model->get_current_all_plant($uId);
            foreach($res as $key=>$value){
                if($value['landStatus']!=0){
                    $need_time = $this->goods_model->get_column_row('goodsName,needTime,becomeGoodsId',array('id'=>$value['goodsId']));
                    //判断是否已经成熟，成熟更新土地表、种植表
                    if((intval(time())-intval($value['startPlantTime']) >= intval($need_time['needTime']))&&$value['landStatus']==1){
                        $update_land['landStatus'] = 2;
                        $this->land_model->update($update_land,array('id'=>$value['land_id'],'uId'=>$uId));
                        $update_plant_record['status'] = 2;
                        $update_plant_record['endPlantTime'] = time();
                        $this->plant_record_model->update($update_plant_record,array('id'=>$value['plant_record_id'],'uId'=>$uId));
                        $res[$key]['landStatus'] = 2;
                    }
                    if($value['landStatus']==1){
                        $res[$key]['sy_time'] = $need_time['needTime']-($time-$value['startPlantTime']);
                    }
                    $res[$key]['needTime'] = $need_time['needTime'];
                    $res[$key]['goodsName'] = $need_time['goodsName'];
                    //根据种子id,查找种子成熟后对应的物品
                    $become = $this->goods_model->get_column_row('goodsName',array('id'=>$need_time['becomeGoodsId']));
                    $res[$key]['becomeGoodsId'] = $need_time['becomeGoodsId'];
                    $res[$key]['becomeGoodsName'] = $become['goodsName'];
                }else{
                    $res[$key]['needTime'] = null;
                    $res[$key]['goodsName'] = null;
                }
            }
            //加工
            $working_res = $this->working_record_model->get_current_all_working($uId);
            foreach($working_res as $key=>$value){
                $need_working = $this->goods_model->get_column_row('goodsName,needTime',array('id'=>$value['peifangId']));
                if($value['status']==1){
                    //判断是否已经加工完成，完成则更新加工表
                    if(intval(time())-intval($value['startWorkingTime']) >= intval($need_working['needTime'])){
                        $update_working_record['status'] = 2;
                        $update_working_record['endWorkingTime'] = time();
                        $this->working_record_model->update($update_working_record,array('id'=>$value['working_record_id'],'uId'=>$uId));
                        $working_res[$key]['status'] = 2;
                        $working_res[$key]['endWorkingTime'] = $update_working_record['endWorkingTime'];
                    }
                }
                if($value['status']==1){
                    $working_res[$key]['sy_time'] = $need_working['needTime']-($time-$value['startWorkingTime']);
                }
                $working_res[$key]['needTime'] = $need_working['needTime'];
                $working_res[$key]['goodsName'] = $need_working['goodsName'];
                //根据配方id,查找配方加工后对应的物品
                $become = $this->goods_model->get_column_row('goodsName',array('id'=>$value['goodsId']));
                $working_res[$key]['becomeGoodsId'] = $value['goodsId'];
                $working_res[$key]['becomeGoodsName'] = $become['goodsName'];
                unset($working_res[$key]['goodsId']);
            }
            //包装
            $packing_res = $this->packing_record_model->get_current_all_packing($uId);
            foreach($packing_res as $key=>$value){
                $need_packing = $this->goods_model->get_column_row('goodsName,needTime',array('id'=>$value['packingId']));
                if($value['status']==1){
                    //判断是否已经包装完成，完成则更新包装表
                    if(intval(time())-intval($value['startPackingTime']) >= intval($need_packing['needTime'])){
                        $update_packing_record['status'] = 2;
                        $update_packing_record['endPackingTime'] = time();
                        $this->packing_record_model->update($update_packing_record,array('id'=>$value['packing_record_id'],'uId'=>$uId));
                        $packing_res[$key]['status'] = 2;
                        $packing_res[$key]['endPackingTime'] = $update_packing_record['endPackingTime'];
                    }
                }
                if($value['status']==1){
                    $packing_res[$key]['sy_time'] = $need_packing['needTime']-($time-$value['startPackingTime']);
                }
                $packing_res[$key]['needTime'] = $need_packing['needTime'];
                $packing_res[$key]['goodsName'] = $need_packing['goodsName'];
                //根据包装id,查找包装完成后对应的物品
                $become = $this->goods_model->get_column_row('goodsName',array('id'=>$value['goodsId']));
                $packing_res[$key]['becomeGoodsId'] = $value['goodsId'];
                $packing_res[$key]['becomeGoodsName'] = $become['goodsName'];
                unset($packing_res[$key]['goodsId']);
            }
            //种子培育
            $breed_res = $this->breed_record_model->get_current_all_breed($uId);
            foreach($breed_res as $key=>$value){
                $need_breed = $this->goods_model->get_column_row('goodsName,breedTime',array('id'=>$value['goodsId']));
                if($value['status']==1){
                    //判断是否已经培育完成，完成则更新种子培育表
                    if(intval(time())-intval($value['startBreedTime']) >= intval($need_breed['breedTime'])){
                        $update_breed_record['status'] = 2;
                        $update_breed_record['endBreedTime'] = time();
                        $this->breed_record_model->update($update_breed_record,array('id'=>$value['breed_record_id'],'uId'=>$uId));
                        $breed_res[$key]['status'] = 2;
                        $breed_res[$key]['endBreedTime'] = $update_breed_record['endBreedTime'];
                    }
                }
                if($value['status']==1){
                    $breed_res[$key]['sy_time'] = $need_breed['breedTime']-($time-$value['startBreedTime']);
                }
                $breed_res[$key]['needTime'] = $need_breed['breedTime'];
                $breed_res[$key]['goodsName'] = $need_breed['goodsName'];
            }
            //配方研究
            $research_res = $this->research_record_model->get_current_all_research($uId);
            foreach($research_res as $key=>$value){
                $need_research = $this->goods_model->get_column_row('goodsName,breedTime',array('id'=>$value['goodsId']));
                if($value['status']==1){
                    //判断配方是否已经研究完成，完成则更新配方研究表
                    if(intval(time())-intval($value['startResearchTime']) >= intval($need_research['breedTime'])){
                        $update_research_record['status'] = 2;
                        $update_research_record['endResearchTime'] = time();
                        $this->research_record_model->update($update_research_record,array('id'=>$value['research_record_id'],'uId'=>$uId));
                        $research_res[$key]['status'] = 2;
                        $research_res[$key]['endResearchTime'] = $update_research_record['endResearchTime'];
                    }
                }
                if($value['status']==1){
                    $research_res[$key]['sy_time'] = $need_research['breedTime']-($time-$value['startResearchTime']);
                }
                $research_res[$key]['needTime'] = $need_research['breedTime'];
                $research_res[$key]['goodsName'] = $need_research['goodsName'];
            }

            $data['plant'] = $res;
            $data['working'] = $working_res;
            $data['packing'] = $packing_res;
            $data['breed'] = $breed_res;
            $data['research'] = $research_res;
            $result = array('code'=>1,'msg'=>'成功','time'=>time(),'data'=>$data);
        }else{
            $result = array('code'=>0,'msg'=>'没有此用户','time'=>time());
        }
        echo json_encode($result,JSON_UNESCAPED_UNICODE);
    }


    //判断某块土地状态
    private function get_land_status($land_id){
        $res = $this->land_model->get_column_row('landStatus',array('id'=>$land_id));
        return $res['landStatus'];
    }















}
