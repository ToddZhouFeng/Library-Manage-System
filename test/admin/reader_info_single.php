<?php
header("Content-type:application/json");
if (!isset($_SESSION['adminID'])) {
    exit; //未登录管理员
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
    return;
}

if (isset($_POST["ReaderID"])) {
    //$output = array();
    $query = "SELECT * FROM Readers WHERE ReaderID = '" . $_POST["ReaderID"] . "'";
    $result = sqlsrv_query($connect, $query);
    while ($row = sqlsrv_fetch_array($result)) {
        $output["ReaderID"] = $row["ReaderID"];
        $output["ReaderName"] = $row["ReaderName"];
        $output["Tel"] = $row["Tel"];
        $output["Fine"] = $row["Fine"];
        $output["Availability"] = $row["Availability"];
        $output["ReaderPassword"] = $row["ReaderPassword"];
    }
    echo json_encode($output);
}
?>