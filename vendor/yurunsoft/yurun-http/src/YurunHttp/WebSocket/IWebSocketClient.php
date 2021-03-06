<?php
namespace Yurun\Util\YurunHttp\WebSocket;

interface IWebSocketClient
{
    /**
     * 初始化
     *
     * @param \Yurun\Util\YurunHttp\Handler\IHandler $httpHandler
     * @param \Yurun\Util\YurunHttp\Http\Request $request
     * @param \Yurun\Util\YurunHttp\Http\Response $response
     * @return void
     */
    public function init($httpHandler, $request, $response);

    /**
     * 获取 Http Handler
     *
     * @return  \Yurun\Util\YurunHttp\Handler\IHandler
     */ 
    public function getHttpHandler();

    /**
     * 获取 Http Request
     *
     * @return \Yurun\Util\YurunHttp\Http\Request
     */
    public function getHttpRequest();

    /**
     * 获取 Http Response
     *
     * @return \Yurun\Util\YurunHttp\Http\Response
     */
    public function getHttpResponse();

    /**
     * 连接
     *
     * @return bool
     */
    public function connect();

    /**
     * 关闭连接
     *
     * @return void
     */
    public function close();

    /**
     * 发送数据
     *
     * @param mixed $data
     * @return bool
     */
    public function send($data);

    /**
     * 接收数据
     *
     * @return mixed
     */
    public function recv();

    /**
     * 是否已连接
     *
     * @return boolean
     */
    public function isConnected();

}