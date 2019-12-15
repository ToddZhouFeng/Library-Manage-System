# 数据库的连接

由于使用的是 SQL Server，所以没有 MySQL 那么方便。需要下载一个 SQLSRV DRIVER 到 PHP 中。这个自行上网查找教程即可。

数据库的连接采用如下语句：
```PHP
//链接数据库
$serverName= "(local)";
$serverUid = "todd";
$serverPwd = "zhouzhenfeng0813";
$connectionInfo = array("UID"=>$serverUid, "PWD"=>$serverPwd, "Database"=>"LMS", "CharacterSet" => "UTF-8");
$connect = sqlsrv_connect($serverName, $connectionInfo);
if(!$connect) {
    echo '链接数据库失败';
    var_dump(sqlsrv_errors());
    return;
}
```
注意不要漏了 `"CharacterSet" => "UTF-8"`，否则中文会乱码