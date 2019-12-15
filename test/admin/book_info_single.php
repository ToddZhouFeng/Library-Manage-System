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

if (isset($_POST["ISBN"]) && isset($_POST["CountNumber"])) {
    //$output = array();
    $query = "SELECT BookName, Price, Books.ISBN, PublishDat, Trainslator, Writer, SearchingID, State, CountNumber, Name FROM Books LEFT JOIN BFinf ON Books.ISBN = BFinf.ISBN LEFT JOIN PublisherInf ON Books.PublisherID = PublisherInf.ISBNP WHERE Books.ISBN = '".$_POST["ISBN"]."' AND Books.CountNumber = ".$_POST["CountNumber"]."";
    $result = sqlsrv_query($connect, $query);
    if(!$result){
        echo "[{\"result\":\"3\"}]";
        exit;
    }
    while ($row = sqlsrv_fetch_array($result)) {
        $output["ISBN"] = $row["ISBN"];
        $output["CountNumber"] = $row["CountNumber"];
        $output["SearchingID"] = $row["SearchingID"];
        $output["BookName"] = $row["BookName"];
        $output["Writer"] = $row["Writer"];
        $output["Trainslator"] = $row["Trainslator"];
        $output["PublishDat"] = $row["PublishDat"];
        $output["Price"] = $row["Price"];
        $output["State"] = $row["State"];
        $output["Name"] = $row["Name"];
    }
    echo json_encode($output);
}
else{
    echo "[{\"result\":\"1\"}]";
}
?>