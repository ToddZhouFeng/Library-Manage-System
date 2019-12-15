<?php
//insert.php
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
    echo '链接数据库失败';
    var_dump(sqlsrv_errors());
    exit;
}

if(isset($_POST["operation"]))
{
 if($_POST["operation"] == "Add")
 {
  $ReaderName=$_POST["ReaderName"];
  $Tel=$_POST["Tel"];
  $Availability=$_POST["Availability"];
  $Fine=$_POST["Fine"];
  $ReaderPassword=$_POST["ReaderPassword"];
  $query = "
   INSERT INTO Readers(ReaderName, Tel, Availability, Fine, ReaderPassword) 
   VALUES ('".$ReaderName."', '".$Tel."', ".$Availability.", ".$Fine.", '".$ReaderPassword."')
  ";
  if(sqlsrv_query($connect, $query))
  {
   echo "{ \"result\": \"插入成功\" }";
  }
 }
 if($_POST["operation"] == "Edit")
 {
    $ReaderName=$_POST["ReaderName"];
    $Tel=$_POST["Tel"];
    $Availability=$_POST["Availability"];
    $Fine=$_POST["Fine"];
    $ReaderPassword=$_POST["ReaderPassword"];
  $query = "
   UPDATE Readers 
   SET ReaderName = '".$ReaderName."', 
   Tel = '".$Tel."', 
   Availability = ".$Availability.",
   Fine = ".$Fine.", 
   ReaderPassword = '".$ReaderPassword."' 
   WHERE ReaderID = '".$_POST["ReaderID"]."'
  ";
  if(sqlsrv_query($connect, $query))
  {
   echo "{ \"result\": \"更新成功\" }";
  }
  else{
    echo "{ \"result\": \"更新失败\" }";
  }
 }
}
?>