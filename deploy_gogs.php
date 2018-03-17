<?php
/**
 * Gogs Webhooks自动部署
 * $arr => k 项目名称也是Webhooks设置的密钥文本
 * 备注：Gogs 数据格式：application/x-www-form-urlencoded
 * $arr => v 项目路径
 * var_dump 在 cli 输出
 */
$serv = new swoole_http_server("127.0.0.1", 50666);
$serv->on('Request', function($request, $response) {
    $arr = array(
        '110.com'=>'/alidata/www/110',
        );
    var_dump($request->get['app']);
    $log = '/alidata/logs/deploy.log';
    $payload = json_decode($request->post['payload'], true);
    $secret = $payload['secret'];
    foreach ($arr as $k => $v) {
        if ($k==$secret) {
            date_default_timezone_set('PRC');
            $date = date('Y-m-d H:i:s',time());
            $shell = "(cd $v&&echo''&&echo $k&&echo $date&&git reset --hard&&git pull)>>$log";
            var_dump($shell);
            shell_exec($shell);
        }
    }
    $response->header("X-Server", "Swoole");
    $response->end("<h1>Hello Webhooks!</h1>");
});
$serv->start();
?>