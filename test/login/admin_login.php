<?php
if(isset($_SESSION['readerID'])){
    unset($_SESSION['readerID']);//退出读者登录
}
//本来应该是 Adnumber的，但一开始写了 adminID，就懒得改名字了
$adminID = !empty($_POST['adminID']) ? trim($_POST['adminID']) : '没有数据';
$password = !empty($_POST['password']) ? trim($_POST['password']) : '没有数据';
$adminID = intval($adminID);//在数据库中 Adnumber 为 int
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

$sql = "select Adnumber,Adname,ADpassword from Administrator where (Adnumber = $adminID AND ADpassword = '$password') ";
$query = sqlsrv_query( $connect,$sql);
if(!$query) {
    echo '<script>alert("读取错误")</script>';
    echo "<script>window.location.href='/test/index.php'</script>";
    return;
}
else{
$row = sqlsrv_fetch_array($query);
    if($row['ADpassword'] == $password) {
        $_SESSION['adminID']  = $adminID;  //把username存到session中
        $_SESSION['name'] = $row['Adname'];
        echo "<script>window.location.href='/test'</script>";
    } else {
        echo "<script>alert('登录失败！请检查输入是否正确".$row["ADpassword"]."')</script>";
        echo "<script>window.location.href='/test/admin_login.html'</script>";
    }
}
?>