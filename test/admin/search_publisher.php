<?php
header("Content-type:application/json");
if(!isset($_SESSION['adminID'])){
    exit;//未登录
}
$publishername = !empty($_GET['publishername']) ? (trim($_GET['publishername'])) : '0';
$ISBNP = !empty($_GET['ISBNP']) ? (trim($_GET['ISBNP'])) : '0';
$tel = !empty($_GET['tel']) ? (trim($_GET['tel'])) : '0';
$mail = !empty($_GET['mail']) ? (trim($_GET['mail'])) : '0';
$address = !empty($_GET['address']) ? (trim($_GET['address'])) : '0';
$ISBNP = strval($ISBNP);
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
    if ($ISBNP != '0') {
        $search = "SELECT * FROM PublisherInf WHERE ISBNP = $ISBNP";
    } else {
        if ($publishername != '0') {
            $search = "SELECT * FROM PublisherInf WHERE Name LIKE '%$publishername%'";
        } elseif ($address != '0') {
            $search = "SELECT * FROM PublisherInf WHERE Address LIKE '%$address%'";
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
            echo "[{\"result\":'0'}]";
        }
    } else {
        echo "[{\"result\":\"0\"}]";
    }
}
?>