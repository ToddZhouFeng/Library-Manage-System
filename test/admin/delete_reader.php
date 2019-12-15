<?php
header("Content-type:application/json");
if (!isset($_SESSION['adminID'])) {
    echo "无管理员权限";
    exit; //未登录
}

//链接数据库
$serverName = "(local)";
$serverUid = "todd";
$serverPwd = "zhouzhenfeng0813";
$connectionInfo = array("UID" => $serverUid, "PWD" => $serverPwd, "Database" => "LMS", "CharacterSet" => "UTF-8");
$connect = sqlsrv_connect($serverName, $connectionInfo);
if (!$connect) {
    echo '链接数据库失败';
    var_dump(sqlsrv_errors());
    exit;
}

if(isset($_POST["ReaderID"])){
    $ReaderID = $_POST["ReaderID"];
    $delete = "DELETE FROM Readers WHERE ReaderID = $ReaderID";
    $result = sqlsrv_query($connect, $delete, array(), array('Scrollable' => 'buffered'));
    if($result){
        echo "{\"result\":\"已删除\"}";
    }
}

?>