<?php
//session_start();//重要
$name = !empty($_POST['name']) ? (trim($_POST['name'])) : '没有数据';
$ID = !empty($_POST['ID']) ? (trim($_POST['ID'])) : '没有数据';
$password = !empty($_POST['password']) ? trim($_POST['password']) : '没有数据';

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

$id_check = "select * from Administrator where AdID = '$ID'";
$query = sqlsrv_query($connect,$id_check);
if(!$query) {
    echo '<script>alert("读取数据库错误1")</script>';
    echo "<script>window.location.href='/test/admin_register.html'</script>";
}
$row = sqlsrv_fetch_array($query);
if($row['ID'] == $ID) {
    echo '<script>alert("该身份证已注册。请登录")</script>';
    echo "<script>window.location.href='/test/admin_login.html'</script>";
} 
else {
    $sql = "INSERT INTO Administrator(AdID, Adname, ADpassword) VALUES('$ID', '$name', '$password')";
    $query = sqlsrv_query( $connect,$sql);
    if(!$query) {
        echo '<script>alert("读取错误2")</script>';
        echo "<script>window.location.href='/test'</script>";
    }
    else{
        $get_number = "SELECT Adnumber FROM Administrator WHERE AdID = '$ID'";
        $query = sqlsrv_query( $connect,$get_number);
        $row = sqlsrv_fetch_array($query);
        echo "<script>alert('注册成功！你的管理员编号是 $row[Adnumber]，请记牢')</script>";
        echo "<script>window.location.href='/test/admin_login.html'</script>";
    }
}
?>