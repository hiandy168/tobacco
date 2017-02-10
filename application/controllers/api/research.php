<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include 'base.php';
class research extends base {

    public function __construct()
    {
        parent::__construct();
    }

    /**
     *接口名称：配方开始研究接口
     *接口地址：http://192.168.1.217/tobacco/index.php?d=api&c=research&m=start_research
     *接收方式：post
     *接收参数：
     *      md5_uid：'66e16d4c71fe0616c864c5d591ab0be7' 用户加密id(暂时写死)
     *      peifang_id:  想要研究的配方的id （8代表a基础配方，9代表b改良配方，10代表c经典配方）
     *      pay_type：支付方式：0乐豆购买，1金币购买
     *返回参数：
     * 	    code：返回码 1正确, 0错误
     * 	    message：描述信息
     *      time: 时间戳
     *      research_record_id:  配方研究记录id
     **/

    public function start_research(){
        //根据md5Uid获取uId
        $md5_uid = $this->input->post("md5_uid");
        $uId = $this->user_model->get_uid($md5_uid);
        if($uId){
            $peifang_id = $this->input->post("peifang_id");
            //判断是否存在此配方,配方所在的类的代号为 4
            $res = $this->goods_model->get_column_row('goodsClass,breedLD,breedTime',array('id'=>$peifang_id));
            if($res['goodsClass']==4){
                $pay_type = $this->input->post("pay_type");
                //确定物品的单价,根据用户支付方式获取用户乐豆或金币
                if($pay_type==0){
                    $goods_price_res = $this->goods_model->get_column_row('breedLD',array('id'=>$peifang_id));
                    $goods_price = $goods_price_res['breedLD'];
                    $ld_num = $this->user_model->get_column_row('leDouNum',array('userId'=>$uId));
                    $money_total = $ld_num['leDouNum'];
                }else if($pay_type==1){
                    $goods_price_res = $this->goods_model->get_column_row('breedJB',array('id'=>$peifang_id));
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
                        $insert_research_record['goodsId'] = $peifang_id;
                        $insert_research_record['uId'] = $uId;
                        $insert_research_record['startResearchTime'] = time();
                        $insert_research_record['status'] = 1;
                        $insert_research_record['payType'] = $pay_type;
                        $insert_research_record['researchMoney'] = $goods_price;
                        $insert_research_record['researchTime'] = $res['breedTime'];
                        $research_record_id = $this->research_record_model->insert($insert_research_record);
                        if($research_record_id){
                            $result = array('code'=>1,'msg'=>'成功','time'=>time(),'research_record_id'=>$research_record_id);
                        }else{
                            $result = array('code'=>0,'msg'=>'保存配方研究记录失败','time'=>time());
                        }
                    }else{
                        $result = array('code'=>0,'msg'=>'更新乐豆数量错误','time'=>time());
                    }
                }else{
                    $result = array('code'=>0,'msg'=>'乐豆或金币不足','time'=>time());
                }
            }else{
                $result = array('code'=>0,'msg'=>'不存在此配方','time'=>time());
            }
        }else{
            $result = array('code'=>0,'msg'=>'没有此用户','time'=>time());
        }
        echo json_encode($result,JSON_UNESCAPED_UNICODE);
    }

    /**
     *接口名称：结束配方研究接口
     *接口地址：http://192.168.1.217/tobacco/index.php?d=api&c=research&m=end_research
     *接收方式：post
     *接收参数：
     *      md5_uid：'66e16d4c71fe0616c864c5d591ab0be7' 用户加密id(暂时写死)
     *      research_record_id:  配方研究记录id
     *返回参数：
     * 	    code：返回码 1正确, 0错误
     * 	    message：描述信息
     *      time: 时间戳
     *      research_record_id:  配方研究记录id
     **/

    public function end_research(){
        //根据md5Uid获取uId
        $md5_uid = $this->input->post("md5_uid");
        $uId = $this->user_model->get_uid($md5_uid);
        if($uId){
            $research_record_id = $this->input->post("research_record_id");
            //判断该培育记录是否存在
            $research_arr = $this->research_record_model->get_column_row('goodsId,status,startResearchTime', array('id'=>$research_record_id,'uId'=>$uId));
            if($research_arr['goodsId']&&$research_arr['status']==1){
                $need_time = $this->goods_model->get_column_row('breedTime', array('id'=>$research_arr['goodsId']));
                if($need_time['breedTime']){
                    if( (intval(time()) - intval($research_arr['startResearchTime'])) >= $need_time['breedTime']){
                        //更新配方研究记录表研究状态
                        $update_research_record['endResearchTime'] = time();
                        $update_research_record['status'] = 2;
                        $res = $this->research_record_model->update($update_research_record,array('id' => $research_record_id,'uId'=>$uId));
                        if($res){
                            $result = array('code'=>1,'msg'=>'成功','time'=>time(),'research_record_id'=>$research_record_id);
                        }else{
                            $result = array('code'=>0,'msg'=>'更新配方研究记录表错误','time'=>time());
                        }
                    }else{
                        $result = array('code'=>0,'msg'=>'配方研究尚未完成','time'=>time());
                    }
                }else{
                    $result = array('code'=>0,'msg'=>'配方研究时间未设置','time'=>time());
                }
            }else{
                $result = array('code'=>0,'msg'=>'配方研究记录不存在','time'=>time());
            }
        }else{
            $result = array('code'=>0,'msg'=>'没有此用户','time'=>time());
        }
        echo json_encode($result,JSON_UNESCAPED_UNICODE);
    }

    /**
     *接口名称：配方研究完成后，将种子存入仓库接口
     *接口地址：http://192.168.1.217/tobacco/index.php?d=api&c=research&m=complete_research
     *接收方式：post
     *接收参数：
     *      md5_uid：'66e16d4c71fe0616c864c5d591ab0be7' 用户加密id(暂时写死)
     *      research_record_id：配方研究记录id
     *返回参数：
     * 	    code：返回码 0错误，1正确，2仓库已满，暂缓收割
     * 	    message：描述信息
     *      research_record_id :配方研究记录id
     **/

    public function complete_research(){
        //根据md5Uid获取uId
        $md5_uid = $this->input->post("md5_uid");
        $uId = $this->user_model->get_uid($md5_uid);
        if($uId){
            //判断培育时间是否已经到达完成的时间
            $research_record_id = $this->input->post("research_record_id");
            $research_status = $this->research_record_model->get_column_row('status,goodsId', array('id'=>$research_record_id,'uId'=>$uId));
            if($research_status['status']==2){
                //判断仓库是否已经存满物品
                if($this->is_store_full($md5_uid)){
                    //更新培育记录表培育状态
                    $update_research_record['status'] = 3;//加工记录状态变为3：已经存入仓库。
                    $res = $this->research_record_model->update($update_research_record,array('id' => $research_record_id,'uId'=>$uId));
                    if($res){
                        //收获一支烟，仓库多一支烟
                        $isexist = $this->db->get_where('zy_store_house', array('goodsId' => $research_status['goodsId'],'uId'=>$uId))->row_array();
                        if($isexist['goodsId']){
                            $update_num['num'] = $isexist['num']+1;
                            $update_num['updateTime'] = time();
                            $affect = $this->store_house_model->update($update_num,array('goodsId' => $research_status['goodsId'],'uId'=>$uId));
                            if($affect){
                                $result = array('code'=>1,'msg'=>'成功','time'=>time(),'research_record_id'=>$research_record_id);
                            }else{
                                $result = array('code'=>0,'msg'=>'更新仓库表错误','time'=>time());
                            }
                        }else{
                            $insert['goodsId'] = $research_status['goodsId'];
                            $insert['uId'] = $uId;
                            $insert['num'] = 1;
                            $insert['updateTime'] = time();
                            $res = $this->store_house_model->insert($insert);
                            if($res){
                                $result = array('code'=>1,'msg'=>'成功','time'=>time(),'research_record_id'=>$research_record_id);
                            }else{
                                $result = array('code'=>0,'msg'=>'保存仓库表错误','time'=>time());
                            }
                        }
                    }else{
                        $result = array('code'=>0,'msg'=>'更新配方研究表错误','time'=>time());
                    }
                }else{
                    $result = array('code'=>2,'msg'=>'仓库已满，请升级仓库','time'=>time());
                }
            }else{
                $result = array('code'=>0,'msg'=>'配方研究尚未完成','time'=>time());
            }
        }else{
            $result = array('code'=>0,'msg'=>'没有此用户','time'=>time());
        }
        echo json_encode($result,JSON_UNESCAPED_UNICODE);
    }








}
