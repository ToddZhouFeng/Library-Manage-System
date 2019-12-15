<?php
header("Content-type:application/json");
if(!isset($_SESSION["readerID"])){
    echo "[{\"result\":\"-1\"}]";
    exit;
}
$ISBN = !empty($_GET['ISBN']) ? (trim($_GET['ISBN'])) : '-1';
$countNumber = !empty($_GET['countNumber']) ? (trim($_GET['countNumber'])) : '-1';
if($ISBN == "-1" || $countNumber == "-1"){
    echo "[{\"result\":\"0\"}]";
    exit;
}
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
else{
    //图书检查
    $check_state = "SELECT State FROM Books WHERE ISBN='$ISBN' AND CountNumber=$countNumber";
    $result = sqlsrv_query($connect, $check_state, array(), array('Scrollable' => 'buffered'));
    if(!$result){
        echo "[{\"result\":\"2\"}]"; //执行失败
        exit;
    }
    $num = sqlsrv_num_rows($result);
    if(!$num){
        echo "[{\"result\":\"3\"}]"; //没有这本书
        exit;
    }
    $row = sqlsrv_fetch_array($result);
    if(!$row["State"]){
        echo "[{\"result\":\"4\"}]"; //这本书不在馆
        exit;
    }

    //读者检查
    $readerID = $_SESSION["readerID"];
    $check_avail ="SELECT Availability FROM Readers WHERE ReaderID=$readerID";
    $result = sqlsrv_query($connect, $check_avail, array(), array('Scrollable' => 'buffered'));
    if(!$result){
        echo "[{\"result\":\"5\"}]"; //执行失败
        exit;
    }
    $row = sqlsrv_fetch_array($result);
    if(!$row["Availability"]){
        echo "[{\"result\":\"6\"}]"; //读者借书达到上限
        exit;
    }

    //读者是否有超时未还书
    $check_borrow = "SELECT * FROM Borrowing WHERE ReaderID=$readerID AND Timeout=1";
    $result = sqlsrv_query($connect, $check_borrow, array(), array('Scrollable' => 'buffered'));
    if(!$result){
        echo "[{\"result\":\"7\"}]"; //执行失败
        exit;
    }
    $num = sqlsrv_num_rows($result);
    if($num){
        echo "[{\"result\":\"8\"}]"; //读者有超时未还书
        exit;
    }

    //借书
    $borrow = "INSERT INTO Borrowing(ReaderID,ISBN,Borrowtime,Returntime,Timeout,Renew,CounterNum) VALUES( $readerID, '$ISBN', GETDATE(),(GETDATE()+30), 0,0, $countNumber)";
    $result = sqlsrv_query($connect, $borrow, array(), array('Scrollable' => 'buffered'));
    if(!$result){
        echo "[{\"result\":\"9\"}]"; //执行失败
        exit;
    }
    echo "[{\"result\":\"10\"}]";//借书成功
}
?>