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
    echo "{\"result\":\"连接数据库失败\"}";
    var_dump(sqlsrv_errors());
    exit;
}

if(isset($_POST["ISBN"]) && isset($_POST["CountNumber"])){
    $ISBN = $_POST["ISBN"];
    $CountNumber = $_POST["CountNumber"];
    $check_borrowing = "SELECT * FROM Borrowing WHERE ISBN = '$ISBN' AND CounterNum = $CountNumber";
    $result = sqlsrv_query($connect, $check_borrowing, array(), array('Scrollable' => 'buffered'));
    if($result){
        $num = sqlsrv_num_rows($result);
        if($num != 0){
            echo "{\"result\":\"该书处于借出状态，不可删除\"}";
            exit;
        }
    }
    else{
        echo "{\"result\":\"检查借书记录失败，删除失败\"}";
        exit;
    }
    
    $delete_books = "DELETE FROM Books WHERE ISBN = '$ISBN' AND CountNumber = $CountNumber";
    $result = sqlsrv_query($connect, $delete_books, array(), array('Scrollable' => 'buffered'));
    if(!$result){
        echo "{\"result\":\"删除失败\"}";
        exit;
    }
    $check_books = "SELECT * FROM Books WHERE ISBN = '$ISBN'";
    $result = sqlsrv_query($connect, $check_books, array(), array('Scrollable' => 'buffered'));
    if($result){
        $num = sqlsrv_num_rows($result);
        if($num == 0){
            $delete_BFInf = "DELETE FROM BFInf WHERE ISBN = '$ISBN'";
            $result = sqlsrv_query($connect, $delete_BFInf, array(), array('Scrollable' => 'buffered'));
            echo "{\"result\":\"已删除\"}";
            exit;
        }
        echo "{\"result\":\"已删除\"}";
    }
    else{
        echo "{\"result\":\"未彻底删除\"}";
    }
}
else{
    echo "{\"result\":\"无ISBN和复本号\"}";
}
?>