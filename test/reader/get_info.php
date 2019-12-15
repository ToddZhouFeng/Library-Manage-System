<?php
header("Content-type:application/json");
//$keywords = $_GET["keywords"];
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

    $sql = 'SELECT ReaderID, Tel,ReaderName, Fine, RegistTime FROM Readers WHERE ReaderID = '.$_SESSION["readerID"];
    $result = sqlsrv_query($connect, $sql);
    $num = sqlsrv_num_rows($result);

        $search_result = array();
        while($row = sqlsrv_fetch_array($result)){
            $search_result[] = $row;
        }
        // 将数组转成json格式
        echo json_encode($search_result);
?>