<?php
if(isset($_SESSION['adminID'])){
    unset($_SESSION['adminID']);//退出管理员登录
}
$readerID = !empty($_POST['readerID']) ? (trim($_POST['readerID'])) : '没有数据';
$password = !empty($_POST['password']) ? trim($_POST['password']) : '没有数据';
$tel = $readerID;
$readerID = intval($readerID); //在数据库中 ReaderID 为 int
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

$sql = "SELECT ReaderID, ReaderName, ReaderPassword FROM Readers WHERE ReaderID = $readerID AND ReaderPassword = '$password' OR Tel = '$tel' AND ReaderPassword = '$password'";
$query = sqlsrv_query( $connect,$sql);
if(!$query) {
    echo '<script>alert("读取错误")</script>';
    echo "<script>window.location.href='/test/index.php'</script>";
    return;
}
else{
$row = sqlsrv_fetch_array($query);
    if($row['ReaderPassword'] == $password) {
        $_SESSION['readerID']  = $row['ReaderID'];  //把readerID存到session中
        $_SESSION['name']  = $row['ReaderName'];
        //echo '<script>alert("'.$row['ReaderName'].'登录成功")</script>'; //感觉加了这句用户体验不好
        echo "<script>window.location.href='/test'</script>";
    }
    else {
        echo '<script>alert("登录失败！请检查输入是否正确")</script>';
        echo "<script>window.location.href='/test/reader_login.html'</script>";
    }
}
?>