<?php
/**
 * Gitlab Webhooks自动部署
 * $arr => k 项目名称-也是Webhooks设置的token
 * $arr => v 项目路径
 * var_dump 在 cli 输出
 */
$serv = new swoole_http_server("127.0.0.1", 9502);
$serv->on('Request', function($request, $response) {
    $arr = array(
        'mczs'=>'/home/mczs/web',
        'qctt'=>'/home/qctt/web',
        );
    $log = '/home/mczs/logs/deploy.log';
    foreach ($arr as $k => $v) {
        if ($k==$request->header["x-gitlab-token"]) {
            date_default_timezone_set('PRC');
            $date = date('Y-m-d H:i:s',time());
            $shell = "(cd $v&&echo''&&echo $k&&echo $date&&git reset --hard&&git pull)>>$log";
            shell_exec($shell);
        }
    }
    $response->header("X-Server", "Swoole");
    $response->end("<h1>Hello Webhooks!</h1>");
});
$serv->start();
?>