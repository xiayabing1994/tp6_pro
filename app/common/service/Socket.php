<?php
namespace app\common\service;
/**
 * Created by Xiami.
 * User: beinianxiaoyao@163.com
 * Desc: Socket服务类
 * Date: 2019/12/22 0022
 * Time: 9:59
 */
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Socket implements MessageComponentInterface{

    protected $clients;
    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }
    public function getConn(){
        $app = new \Ratchet\App('localhost', 8080);
        $app->route('/chat', new Self, array('*'));
        $app->run();
    }
    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
    }
    public function onMessage(ConnectionInterface $from, $msg) {
        file_put_contents('./chat.log',$msg."ww\r\n",FILE_APPEND);
        file_put_contents('./chat.log',$this->clients,FILE_APPEND);
        foreach ($this->clients as $client) {

            if ($from != $client) {

                $client->send($msg);
            }
        }
    }
    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
    }
    public function onError(ConnectionInterface $conn, \Exception $e) {
        $conn->close();
    }
}
