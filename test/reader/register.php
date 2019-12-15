<?php
//session_start();//重要
$name = !empty($_POST['name']) ? (trim($_POST['name'])) : '没有数据';
$phone = !empty($_POST['phone']) ? (trim($_POST['phone'])) : '没有数据';
$password = !empty($_POST['password']) ? trim($_POST['password']) : '没有数据';
$date=date('Y-m-d');
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

$phone_check = "select * from Readers where Tel = '$phone'";
$query = sqlsrv_query( $connect,$phone_check);
if(!$query) {
    echo '<script>alert("读取错误")</script>';
    echo "<script>window.location.href='/test'</script>";
}
$row = sqlsrv_fetch_array($query);
if($row['Tel'] == $phone) {
    echo '<script>alert("该电话已注册。请登录")</script>';
    echo "<script>window.location.href='/test/reader_login.html'</script>";
} 
else {
    $sql = "INSERT INTO Readers(Tel, ReaderName, ReaderPassword, RegistTime) VALUES('$phone', '$name', '$password', '$date')";
    $query = sqlsrv_query( $connect,$sql);
    if(!$query) {
        echo '<script>alert("读取错误")</script>';
        echo "<script>window.location.href='/test'</script>";
    }
    else{
        echo '<script>alert("注册成功！请登录")</script>';
        echo "<script>window.location.href='/test/reader_login.html'</script>";
    }
}
?>