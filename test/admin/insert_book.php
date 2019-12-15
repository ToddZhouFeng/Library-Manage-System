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

if (isset($_POST["operation"])) {
  if ($_POST["operation"] == "Add") {
    //add 功能在 upload_book.php 里
  }
  if ($_POST["operation"] == "Edit") {
    $ISBN = $_POST["ISBN"];
    $CountNumber = $_POST["CountNumber"];
    $SearchingID = $_POST["SearchingID"];
    $BookName = $_POST["BookName"];
    $Writer = $_POST["Writer"];
    $Trainslator = $_POST["Trainslator"];
    $PublishDat = $_POST["PublishDat"];
    $Price = $_POST["Price"];
    $State = $_POST["State"];
    $query = "
   UPDATE BFInf 
   SET SearchingID = '" . $SearchingID . "', 
   BookName = '" . $BookName . "',
   Writer = '" . $Writer . "', 
   Trainslator = '" . $Trainslator . "', 
   PublishDat = '" . $PublishDat . "', 
   Price = " . $Price . "
   WHERE ISBN = '$ISBN'
  ";
    $query1 = "UPDATE Books SET State = $State WHERE ISBN = '$ISBN' AND CountNumber = $CountNumber";
    if (sqlsrv_query($connect, $query) && sqlsrv_query($connect, $query1)) {
      echo "{ \"result\": \"更新成功\" }";
    } else {
      echo "{ \"result\": \"更新失败\" }";
    }
  }
}
?>