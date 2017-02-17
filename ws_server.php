<?php
//加载GatewayClient GatewayWorker-for-win
//require_once './GatewayWorker-for-win/vendor/workerman/gateway-worker-for-win/src/Protocols/GatewayProtocol.php';
//require_once './GatewayWorker-for-win/vendor/workerman/gateway-worker-for-win/src/Lib/Context.php';
//require_once './GatewayWorker-for-win/vendor/workerman/gateway-worker-for-win/src/Lib/Gateway.php';
require_once './GatewayWorker-for-win/vendor/workerman/workerman-for-win/Worker.php';
require_once './GatewayWorker-for-win/vendor/workerman/workerman-for-win/Lib/Timer.php';
require_once './GatewayWorker-for-win/vendor/autoload.php';

//use \GatewayWorker\Lib\Gateway;
use \Workerman\Worker;
use \Workerman\Lib\Timer;
$task = new Worker();
// 开启多少个进程运行定时任务，注意多进程并发问题
$task->count = 1;
$task->onWorkerStart = function($task)
{
    // 每2.5秒执行一次
    $time_interval = 2.5;
    Timer::add($time_interval, function()
    {
        echo "my task run\n";
    });
};

// 运行worker
Worker::runAll();