<?php
header("Content-type:application/json");
$ReaderPassword = $_GET["password"];
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
    $update = "UPDATE Readers SET ReaderPassword = '".$ReaderPassword."' WHERE ReaderID = ".$_SESSION["readerID"];
    $result = sqlsrv_query($connect, $update);
    if($result){
        echo "[{\"result\":\"1\"}]";
        // 将数组转成json格式
    }
    else{
        echo "[{\"result\":\"0\"}]";
    }
?>