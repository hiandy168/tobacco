<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include 'base.php';
class breed extends base {

    public function __construct()
    {
        parent::__construct();
    }

    /**
     *接口名称：种子开始培育接口
     *接口地址：http://192.168.1.217/tobacco/index.php?d=api&c=breed&m=start_breed
     *接收方式：post
     *接收参数：
     *      md5_uid：'66e16d4c71fe0616c864c5d591ab0be7' 用户加密id(暂时写死)
     *      seed_id:  想要培育的种子的id （1代表a种子，2代表b种子，3代表c种子）
     *      pay_type：支付方式：0乐豆购买，1金币购买
     *返回参数：
     * 	    code：返回码 1正确, 0错误
     * 	    message：描述信息
     *      time: 时间戳
     *      breed_record_id:  种子培育记录id
     **/

    public function start_breed(){
        //根据md5Uid获取uId
        $md5_uid = $this->input->post("md5_uid");
        $uId = $this->user_model->get_uid($md5_uid);
        if($uId){
            $seed_id = $this->input->post("seed_id");
            //判断是否存在此种子,种子所在的类的代号为 1
            $res = $this->goods_model->get_column_row('goodsClass,breedLD,breedTime',array('id'=>$seed_id));
            if($res['goodsClass']==1){
                $pay_type = $this->input->post("pay_type");
                //确定物品的单价,根据用户支付方式获取用户乐豆或金币
                if($pay_type==0){
                    $goods_price_res = $this->goods_model->get_column_row('breedLD',array('id'=>$seed_id));
                    $goods_price = $goods_price_res['breedLD'];
                    $ld_num = $this->user_model->get_column_row('leDouNum',array('userId'=>$uId));
                    $money_total = $ld_num['leDouNum'];
                }else if($pay_type==1){
                    $goods_price_res = $this->goods_model->get_column_row('breedJB',array('id'=>$seed_id));
                    $goods_price = $goods_price_res['breedJB'];
                    $jb_num = $this->user_model->get_column_row('goldNum',array('userId'=>$uId));
                    $money_total = $jb_num['goldNum'];
                }
                if($money_total>=$goods_price){
                    $money_total = $money_total - $goods_price;
                    if($pay_type==0){
                        $update_ld['leDouNum'] = $money_total;
                    }else if($pay_type==1){
                        $update_ld['goldNum'] = $money_total;
                    }
                    $affect = $this->user_model->update($update_ld,array('userId'=>$uId));
                    if($affect){
                        //存入种子培育记录表
                        $insert_breed_record['goodsId'] = $seed_id;
                        $insert_breed_record['uId'] = $uId;
                        $insert_breed_record['startBreedTime'] = time();
                        $insert_breed_record['status'] = 1;
                        $insert_breed_record['payType'] = $pay_type;
                        $insert_breed_record['breedMoney'] = $goods_price;
                        $insert_breed_record['breedTime'] = $res['breedTime'];
                        $breed_record_id = $this->breed_record_model->insert($insert_breed_record);
                        if($breed_record_id){
                            $result = array('code'=>1,'msg'=>'成功','time'=>time(),'breed_record_id'=>$breed_record_id);
                        }else{
                            $result = array('code'=>0,'msg'=>'保存种子培育记录失败','time'=>time());
                        }
                    }else{
                        $result = array('code'=>0,'msg'=>'更新乐豆数量错误','time'=>time());
                    }
                }else{
                    $result = array('code'=>0,'msg'=>'乐豆或金币不足','time'=>time());
                }
            }else{
                $result = array('code'=>0,'msg'=>'不存在此种子','time'=>time());
            }
        }else{
            $result = array('code'=>0,'msg'=>'没有此用户','time'=>time());
        }
        echo json_encode($result,JSON_UNESCAPED_UNICODE);
    }

    /**
     *接口名称：结束种子培育接口
     *接口地址：http://192.168.1.217/tobacco/index.php?d=api&c=breed&m=end_breed
     *接收方式：post
     *接收参数：
     *      md5_uid：'66e16d4c71fe0616c864c5d591ab0be7' 用户加密id(暂时写死)
     *      breed_record_id:  种子培育记录id
     *返回参数：
     * 	    code：返回码 1正确, 0错误
     * 	    message：描述信息
     *      time: 时间戳
     *      breed_record_id:  培育记录id
     **/

    public function end_breed(){
        //根据md5Uid获取uId
        $md5_uid = $this->input->post("md5_uid");
        $uId = $this->user_model->get_uid($md5_uid);
        if($uId){
            $breed_record_id = $this->input->post("breed_record_id");
            //判断该培育记录是否存在
            $breed_arr = $this->breed_record_model->get_column_row('goodsId,status,startBreedTime', array('id'=>$breed_record_id,'uId'=>$uId));
            if($breed_arr['goodsId']&&$breed_arr['status']==1){
                $need_time = $this->goods_model->get_column_row('breedTime', array('id'=>$breed_arr['goodsId']));
                if($need_time['breedTime']){
                    if( (intval(time()) - intval($breed_arr['startBreedTime'])) >= $need_time['breedTime']){
                        //更新培育记录表培育状态
                        $update_breed_record['endBreedTime'] = time();
                        $update_breed_record['status'] = 2;
                        $res = $this->breed_record_model->update($update_breed_record,array('id' => $breed_record_id,'uId'=>$uId));
                        if($res){
                            $result = array('code'=>1,'msg'=>'成功','time'=>time(),'breed_record_id'=>$breed_record_id);
                        }else{
                            $result = array('code'=>0,'msg'=>'更新培育记录表错误','time'=>time());
                        }
                    }else{
                        $result = array('code'=>0,'msg'=>'培育尚未完成','time'=>time());
                    }
                }else{
                    $result = array('code'=>0,'msg'=>'培育时间未设置','time'=>time());
                }
            }else{
                $result = array('code'=>0,'msg'=>'培育记录不存在','time'=>time());
            }
        }else{
            $result = array('code'=>0,'msg'=>'没有此用户','time'=>time());
        }
        echo json_encode($result,JSON_UNESCAPED_UNICODE);
    }

    /**
     *接口名称：种子培育完成后，将种子存入仓库接口
     *接口地址：http://192.168.1.217/tobacco/index.php?d=api&c=breed&m=complete_breed
     *接收方式：post
     *接收参数：
     *      md5_uid：'66e16d4c71fe0616c864c5d591ab0be7' 用户加密id(暂时写死)
     *      breed_record_id：培育记录id
     *返回参数：
     * 	    code：返回码 0错误，1正确，2仓库已满，暂缓收割
     * 	    message：描述信息
     *      breed_record_id :培育记录id
     **/

    public function complete_breed(){
        //根据md5Uid获取uId
        $md5_uid = $this->input->post("md5_uid");
        $uId = $this->user_model->get_uid($md5_uid);
        if($uId){
            //判断培育时间是否已经到达完成的时间
            $breed_record_id = $this->input->post("breed_record_id");
            $breed_status = $this->breed_record_model->get_column_row('status,goodsId', array('id'=>$breed_record_id,'uId'=>$uId));
            if($breed_status['status']==2){
                //判断仓库是否已经存满物品
                if($this->is_store_full($md5_uid)){
                    //更新培育记录表培育状态
                    $update_breed_record['status'] = 3;//加工记录状态变为3：已经存入仓库。
                    $res = $this->breed_record_model->update($update_breed_record,array('id' => $breed_record_id,'uId'=>$uId));
                    if($res){
                        //收获一支烟，仓库多一支烟
                        $isexist = $this->db->get_where('zy_store_house', array('goodsId' => $breed_status['goodsId'],'uId'=>$uId))->row_array();
                        if($isexist['goodsId']){
                            $update_num['num'] = $isexist['num']+1;
                            $update_num['updateTime'] = time();
                            $affect = $this->store_house_model->update($update_num,array('goodsId' => $breed_status['goodsId'],'uId'=>$uId));
                            if($affect){
                                $result = array('code'=>1,'msg'=>'成功','time'=>time(),'breed_record_id'=>$breed_record_id);
                            }else{
                                $result = array('code'=>0,'msg'=>'更新仓库表错误','time'=>time());
                            }
                        }else{
                            $insert['goodsId'] = $breed_status['goodsId'];
                            $insert['uId'] = $uId;
                            $insert['num'] = 1;
                            $insert['updateTime'] = time();
                            $res = $this->store_house_model->insert($insert);
                            if($res){
                                $result = array('code'=>1,'msg'=>'成功','time'=>time(),'breed_record_id'=>$breed_record_id);
                            }else{
                                $result = array('code'=>0,'msg'=>'保存仓库表错误','time'=>time());
                            }
                        }
                    }else{
                        $result = array('code'=>0,'msg'=>'更新培育记录表错误','time'=>time());
                    }
                }else{
                    $result = array('code'=>2,'msg'=>'仓库已满，请升级仓库','time'=>time());
                }
            }else{
                $result = array('code'=>0,'msg'=>'培育尚未完成','time'=>time());
            }
        }else{
            $result = array('code'=>0,'msg'=>'没有此用户','time'=>time());
        }
        echo json_encode($result,JSON_UNESCAPED_UNICODE);
    }








}
