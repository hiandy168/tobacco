<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//加载GatewayClient GatewayWorker-for-win
require_once './GatewayWorker-for-win/vendor/workerman/gateway-worker-for-win/src/Protocols/GatewayProtocol.php';
require_once './GatewayWorker-for-win/vendor/workerman/gateway-worker-for-win/src/Lib/Context.php';
require_once './GatewayWorker-for-win/vendor/workerman/gateway-worker-for-win/src/Lib/Gateway.php';
require_once './GatewayWorker-for-win/vendor/workerman/workerman-for-win/Worker.php';
require_once './GatewayWorker-for-win/vendor/workerman/workerman-for-win/Lib/Timer.php';


use \Workerman\Worker;
use \Workerman\WebServer;
use \GatewayWorker\Gateway;
use \GatewayWorker\BusinessWorker;
use \Workerman\Autoloader;

// 自动加载类
require_once './GatewayWorker-for-win/vendor/autoload.php';
// GatewayClient 3.0.0版本开始要使用命名空间
/*use \GatewayWorker\Lib\Gateway;
use \Workerman\Worker;*/
use \Workerman\Lib\Timer;
class bind extends CI_Controller {
    /**
    //加载GatewayClient
    require_once '/your/path/GatewayClient/Gateway.php';
    // GatewayClient 3.0.0版本开始要使用命名空间
    use GatewayClient\Gateway;
    // 设置GatewayWorker服务的Register服务ip和端口，请根据实际情况改成实际值
    Gateway::$registerAddress = '127.0.0.1:1236';

    // 假设用户已经登录，用户uid和群组id在session中
    $uid      = $_SESSION['uid'];
    $group_id = $_SESSION['group'];
    // client_id与uid绑定
    Gateway::bindUid($client_id, $uid);
    // 加入某个群组（可调用多次加入多个群组）
    Gateway::joinGroup($client_id, $group_id);
     */

    public function index()
    {
        //注册地址,与start_gateway.php中的registerAddress、start_businessworker.php中的registerAddress、 start_register.php中的地址一致
        Gateway::$registerAddress = '127.0.0.1:8090';
        // 假设用户已经登录，用户uid和群组id在session中
        $uid      = $_SESSION['userId'];
        $client_id = $_POST['client_id'];
        //Gateway::bindUid($client_id, $uid);     // client_id与uid绑定
        //$data['client_id'] = Gateway::getClientIdByUid($uid);

        $group = array();
        //Gateway::joinGroup($client_id, $group);
        //$data['count'] = Gateway::getClientSessionsByGroup($group);


        $data['type'] = 'bind';
        $data['msg'] = "绑定成功";
        Gateway::sendToClient($client_id, json_encode($data));
    }

    public function sent_to_all(){
        Gateway::$registerAddress = '127.0.0.1:8090';
        $message = array("type"=>"say_to_all","content"=>"hello everyone");
        Gateway::sendToAll(json_encode($message['content']));
    }

    public function timer(){
        //Gateway::$registerAddress = '127.0.0.1:8090';
        //$message = array("type"=>"say_to_all","content"=>"add timer success");
        $time_interval = 5;
        Timer::add($time_interval, function()
        {
            Gateway::sendToAll(json_encode("add timer success"));
        });
    }

}

