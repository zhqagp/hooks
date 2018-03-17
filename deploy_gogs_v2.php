<?php
/**
 * Gogs Webhooks自动部署
 * $arr => k 项目名称也是Webhooks设置的密钥文本
 * 备注：Gogs 数据格式：application/x-www-form-urlencoded
 * $arr => v 项目路径
 * var_dump 在 cli 输出
 */
$serv = new swoole_http_server("127.0.0.1", 18340);
$serv->on('Request', function($request, $response) {
    $arr = array(
        'ehuatai'=>'/data/wwwroot/default/ehuatai',
        );
    $log = '/home/www/gogs_hook.log';
    $payload = json_decode($request->post['payload'], true);
    $repository_name = $payload['repository']['name'];
    if (isset($arr[$repository_name])) {
        date_default_timezone_set('PRC');
        $date = date('Y-m-d H:i:s',time());
        $shell = "(cd $arr[$repository_name]&&echo''&&echo $repository_name&&echo $date&&git reset --hard&&git pull)>>$log";
        var_dump($shell);
        shell_exec($shell);
    }
    $response->header("X-Server", "Swoole");
    $response->end("<h1>Hello Webhooks!</h1>".$repository_name);
});
$serv->start();
?>
