<?php
header("Content-type:application/json");
if(!isset($_SESSION['adminID'])){
    exit;//未登录
}
$BookName = !empty($_GET['BookName']) ? (trim($_GET['BookName'])) : '0';
$ISBN = !empty($_GET['ISBN']) ? (trim($_GET['ISBN'])) : '0';

//链接数据库
$serverName = "(local)";
$serverUid = "todd";
$serverPwd = "zhouzhenfeng0813";
$connectionInfo = array("UID" => $serverUid, "PWD" => $serverPwd, "Database" => "LMS", "CharacterSet" => "UTF-8");
$connect = sqlsrv_connect($serverName, $connectionInfo);
if (!$connect) {
    echo '链接数据库失败';
    var_dump(sqlsrv_errors());
    return;
} else {
    if ($ISBN != '0') {
        $search = "SELECT * FROM BFInf WHERE ISBN LIKE '%$ISBN%'";
    } else {
        if ($BookName != '0') {
            $search = "SELECT * FROM BFInf WHERE BookName LIKE '%%$BookName%%'";
        } else {
            echo "[{\"result\":\"1\"}]"; //无输入
            exit;
        }
    }

    $result = sqlsrv_query($connect, $search, array(), array('Scrollable' => 'buffered'));
    if ($result) {
        $num = sqlsrv_num_rows($result);
        if ($num != 0) {
            $search_result = array();
            while ($row = sqlsrv_fetch_array($result)) {
                $search_result[] = $row;
            }
            // 将数组转成json格式
            echo json_encode($search_result);
        } else {
            echo "[{\"result\":'$search'}]";
        }
    } else {
        echo "[{\"result\":\"3\"}]";
    }
}
?>