<?php
header("Content-type:application/json");
if (!isset($_SESSION['readerID'])) {
    echo "{\"result\":\"读者未登陆\"}";
    exit; //未登录
}
$ReaderID = $_SESSION['readerID'];

//链接数据库
$serverName = "(local)";
$serverUid = "todd";
$serverPwd = "zhouzhenfeng0813";
$connectionInfo = array("UID" => $serverUid, "PWD" => $serverPwd, "Database" => "LMS", "CharacterSet" => "UTF-8");
$connect = sqlsrv_connect($serverName, $connectionInfo);
if (!$connect) {
    echo "{\"result\":\"连接数据库失败\"}";
    var_dump(sqlsrv_errors());
    exit;
}

if(isset($_POST["ISBN"]) && isset($_POST["CountNumber"])){
    $ISBN = $_POST["ISBN"];
    $CountNumber = $_POST["CountNumber"];
    $check_borrowing = "SELECT * FROM Borrowing WHERE ISBN = '$ISBN' AND CounterNum = $CountNumber AND ReaderID = $ReaderID";
    $result = sqlsrv_query($connect, $check_borrowing, array(), array('Scrollable' => 'buffered'));
    if($result){
        $num = sqlsrv_num_rows($result);
        if($num == 0){
            echo "{\"result\":\"您未借该本书\"}";
            exit;
        }
    }
    else{
        echo "{\"result\":\"检查借书记录失败，续借失败\"}";
        exit;
    }

    $row = sqlsrv_fetch_array($result);
    if($row["renew"] == 1){
        echo "{\"result\":\"您已续借该本书\"}";
        exit;
    }
    else{
        $returndate=date_create($row["Returntime"]);
        date_add($returndate,date_interval_create_from_date_string("30 days"));
        $new_returndate = date_format($returndate,"Y-m-d");
    }
    

    $delete_books = "UPDATE Borrowing SET Renew = 1, Returntime = '$new_returndate' WHERE ISBN = '$ISBN' AND CountNumber = $CountNumber AND ReaderID = $ReaderID";
    $result = sqlsrv_query($connect, $delete_books, array(), array('Scrollable' => 'buffered'));
    if($result){
        echo "{\"result\":\"续借成功\"}";
        exit;
    }
    else{
        echo "{\"result\":\"续借失败\"}";
        exit;
    }
}
else{
    echo "{\"result\":\"无ISBN和复本号\"}";
}
?>