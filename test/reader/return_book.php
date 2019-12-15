<?php
header("Content-type:application/json");
if(!isset($_SESSION["readerID"])){
    echo "{\"result\":\"-1\"}";
    exit;
}
$ISBN = !empty($_GET['ISBN']) ? (trim($_GET['ISBN'])) : '-1';
$countNumber = !empty($_GET['countNumber']) ? (trim($_GET['countNumber'])) : '-1';
$readerID = $_SESSION["readerID"];
if($ISBN == "-1" || $countNumber == "-1"){
    echo "{\"result\":\"0\"}";
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
    //借书记录检查
    $check_borrow = "SELECT * FROM Borrowing WHERE ISBN='$ISBN' AND CounterNum=$countNumber AND ReaderID = $readerID";
    $result = sqlsrv_query($connect, $check_borrow, array(), array('Scrollable' => 'buffered'));
    if(!$result){
        echo "{\"result\":\"2\"}"; //执行失败
        exit;
    }
    $num = sqlsrv_num_rows($result);
    if(!$num){
        echo "{\"result\":\"3\"}"; //没有借书记录
        exit;
    }

    //计算罚金
    $row = sqlsrv_fetch_array($result);
    $today=strtotime(date("Y-m-d"));
    $returnday=strtotime($row["Returntime"]->format('Y-m-d'));
    if($today > $returnday){
        $day=ceil(($today - $returnday)/86400);
        $money = intval($day)*0.1;
        $add_money = "UPDATE Readers SET Fine = Fine + $money WHERE ReaderID = $readerID";
        $result = sqlsrv_query($connect, $add_money, array(), array('Scrollable' => 'buffered'));
        if(!$result){
            echo "{\"result\":\"4\"}"; //增加罚款失败
            exit;
        }
    }
    $day=strval($row["Borrowtime"]->format('Y-m-d'));
    $return = "INSERT INTO History(ReaderID, ISBN, Borrowtime, Returntime, CounterNum, Counter) VALUES($readerID,'$ISBN','$day', GETDATE(), $countNumber, (SELECT COUNT(*) FROM History WHERE ISBN='$ISBN' AND CounterNum=$countNumber)+1)";
    $result = sqlsrv_query($connect, $return, array(), array('Scrollable' => 'buffered'));
    if(!$result){
        echo "{\"result\":\"5\"}"; //还书失败
        exit;
    }

    $delete = "DELETE FROM Borrowing WHERE ISBN='$ISBN' AND CounterNum=$countNumber";
    $result = sqlsrv_query($connect, $delete, array(), array('Scrollable' => 'buffered'));

    echo "{\"result\":\"6\"}";
}
?>