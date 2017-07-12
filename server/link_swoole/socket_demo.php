<?php
namespace server\link_swoole;
use swoole_websocket_server;

class socket_demo
{
    private $socketServer;
    public function __construct()
    {
        $this->socketServer = new swoole_websocket_server('0.0.0.0', 8282 );
        $this->socketServer->set([
            'worker_num' => 4,
            'daemonize'  => false,
        ]);
        $this->socketServer->on('open', [$this, 'onOpen']);
        $this->socketServer->on('message', [$this, 'message']);
        $this->socketServer->on('close', [$this, 'onClose']);
        $this->socketServer->on('WorkerStart', [$this, 'onWorkerStart']);

    }
    public function onWorkerStart($serv, $worker_id){
        echo "workstart=>"."$worker_id"."\n";
    }
    public function message($socketServer, $frame)
    {
        echo "onmessage\n";

    }
    public function onOpen($socketServer, $request)
    {
        echo "onopen\n";
    }
    public function onClose($socketServer, $fd)
    {
        echo 'onclose'."\n";
    }
    public function start()
    {
        $this->socketServer->start();
    }
}