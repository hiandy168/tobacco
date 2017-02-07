<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include 'base.php';
class shop extends base {

    public function __construct()
    {
        parent::__construct();
    }

    /**
     *接口名称：商店列表接口
     *接口地址：http://192.168.1.217/tobacco/index.php?d=api&c=shop&m=lists
     *接收方式：post
     *接收参数：
     *      md5_uid：'66e16d4c71fe0616c864c5d591ab0be7' 用户加密id(暂时写死)
     *返回参数：
     * 	    code：返回码 1正确, 0错误
     * 	    message：描述信息
     *      id: 物品id
     *      goodsName :物品名称
     *      priceByLD：用乐豆购买物品的价格
     *      priceByJB：用金币购买物品的价格
     **/

    public function lists(){
        //根据md5Uid获取uId
        $md5_uid = $this->input->post("md5_uid");
        $uId = $this->user_model->get_uid($md5_uid);
        if($uId){
            $res = $this->goods_model->get_list("id,goodsName,priceByLD,priceByJB",'',$order = 'id',$offset = 0, $limit = 50);
            $result = array('code'=>1,'msg'=>'成功','time'=>time(),'data'=>$res);
        }else{
            $result = array('code'=>0,'msg'=>'没有此用户','time'=>time());
        }
        echo json_encode($result);
    }

    /**
     *接口名称：购买物品接口
     *接口地址：http://192.168.1.217/tobacco/index.php?d=api&c=shop&m=buy
     *接收方式：post
     *接收参数：
     *      md5_uid：'66e16d4c71fe0616c864c5d591ab0be7' 用户加密id(暂时写死)
     *      goods_id：购买物品的id
     *      goods_num：购买物品的数量
     *      pay_type：支付方式：0乐豆购买，1金币购买
     *返回参数：
     * 	    code：返回码 1正确, 0错误
     * 	    message：描述信息
     *      time:   时间戳
     *      money_total: 当前用户剩余乐豆或金币
     *      pay_type: 支付方式：0乐豆购买，1金币购买
     **/
    public function buy(){
        //根据md5Uid获取uId
        $md5_uid = $this->input->post("md5_uid");
        $uId = $this->user_model->get_uid($md5_uid);
        if($uId){
            //从物品表zy_goods获取种子属性信息
            $goods_num = $this->input->post("goods_num");
            if($goods_num){
                //查询仓库剩余多少容量
                $remain_num = $this->is_store_full($md5_uid);
                if($remain_num>=$goods_num){
                    $pay_type = $this->input->post("pay_type");
                    $goods_id = $this->input->post("goods_id");
                    //确定物品的单价,根据用户支付方式获取用户乐豆或金币
                    if($pay_type==0){
                        $goods_price_res = $this->goods_model->get_column_row('priceByLD',array('id'=>$goods_id));
                        $goods_price = $goods_price_res['priceByLD'];
                        $ld_num = $this->user_model->get_column_row('leDouNum',array('userId'=>$uId));
                        $money_total = $ld_num['leDouNum'];
                    }else if($pay_type==1){
                        $goods_price_res = $this->goods_model->get_column_row('priceByJB',array('id'=>$goods_id));
                        $goods_price = $goods_price_res['priceByJB'];
                        $jb_num = $this->user_model->get_column_row('goldNum',array('userId'=>$uId));
                        $money_total = $jb_num['goldNum'];
                    }

                    if($money_total>=$goods_price*$goods_num) {
                        $money_total = $money_total - $goods_price*$goods_num;
                        if($pay_type==0){
                            $update_ld['leDouNum'] = $money_total;
                        }else if($pay_type==1){
                            $update_ld['goldNum'] = $money_total;
                        }
                        $affect = $this->user_model->update($update_ld,array('userId'=>$uId));
                        if ($affect) {
                            //插入购买记录表
                            $insert_buy_record['goodsId'] = $goods_id;
                            $insert_buy_record['uId'] = $uId;
                            $insert_buy_record['singlePrice'] = $goods_price;
                            $insert_buy_record['totalNum'] = $goods_num;
                            $insert_buy_record['totalPrice'] = $goods_price*$goods_num;
                            $insert_buy_record['addTime'] = time();
                            $insert_buy_record['source'] = 0;
                            $insert_buy_record['payType'] = $pay_type;
                            $buy_record_id = $this->buy_record_model->insert($insert_buy_record);
                            if($buy_record_id){
                                if($goods_id==4){   //土地要单独存表
                                    //插入土地表zy_land
                                    $insert_land['buyRecordId'] = $buy_record_id;
                                    $insert_land['uId'] = $uId;
                                    $insert_land['addTime'] = time();
                                    for($i=0 ; $i<$goods_num ; $i++){
                                        $res = $this->land_model->insert($insert_land);
                                    }
                                }else{
                                    //查询仓库表 zy_store_house 是否已经存在该物品的记录，有则更新即可
                                    $isexist = $this->store_house_model->get_column_row("id,num",array('goodsId'=>$goods_id,'uId'=>$uId));
                                    if($isexist['id']){
                                        $update_seed['num'] = $isexist['num']+$goods_num;
                                        $update_seed['updateTime'] = time();
                                        $res = $this->store_house_model->update($update_seed,array('id'=>$isexist['id']));
                                    }else{
                                        $insert_seed['uId'] = $uId;
                                        $insert_seed['goodsId'] = $goods_id;
                                        $insert_seed['num'] = $goods_num;
                                        $insert_seed['updateTime'] = time();
                                        $res = $this->store_house_model->insert($insert_seed);
                                    }
                                }

                                if($res){
                                    $result = array('code'=>1,'msg'=>'购买成功','money_total'=>$money_total,'pay_type'=>$pay_type);
                                }else{
                                    $result = array('code'=>0,'msg'=>'购买失败','money_total'=>$money_total,'pay_type'=>$pay_type);
                                }
                            }else{
                                $result = array('code'=>0,'msg'=>'购买记录写入数据库错误','time'=>time());
                            }
                        }else{
                            $result = array('code'=>0,'msg'=>'更新乐豆数量错误','time'=>time());
                        }
                    }else{
                        $result = array('code'=>0,'msg'=>'乐豆或金币不足','time'=>time());
                    }
                }else{
                    $result = array('code'=>0,'msg'=>'仓库容量不足，请升级仓库','time'=>time());
                }
            }else{
                $result = array('code'=>0,'msg'=>'购买的物品数量必须大于0','time'=>time());
            }
        }else{
            $result = array('code'=>0,'msg'=>'没有此用户','time'=>time());
        }
        echo json_encode($result);
    }












}
