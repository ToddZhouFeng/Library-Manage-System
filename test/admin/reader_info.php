<?php
header("Content-type:application/json");
if (!isset($_SESSION['adminID'])) {
    exit; //未登录管理员
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
    return;
}

$query = '';
$data = array();
$records_per_page = -1;
$start_from = 0;
$current_page_number = 1;
if (isset($_POST["rowCount"])) {
    $records_per_page = $_POST["rowCount"];
    $start_from = ($current_page_number - 1) * $records_per_page;

}
if (isset($_POST["current"])) {
    $current_page_number = $_POST["current"];
}


$query .= "SELECT ReaderID, ReaderName, Tel, Availability, Fine, RegistTime FROM Readers ";

if (!empty($_POST["searchPhrase"])) {//搜索关键词
    $query .= " WHERE ReaderName LIKE '%%".$_POST["searchPhrase"]."%%'";
    $query .= " OR Tel LIKE '%" . $_POST["searchPhrase"] . "%' ";
}


$order_by = '';
if (isset($_POST["sort"]) && is_array($_POST["sort"])) {
    foreach ($_POST["sort"] as $key => $value) {
        $order_by .= " $key $value, ";
    }
} else {
    $query .= 'ORDER BY ReaderID DESC '; //默认按读者ID排序
}

if ($order_by != '') {
    $query .= ' ORDER BY ' . substr($order_by, 0, -2); //排序
}
/*
if ($records_per_page != -1) {
    $query .= " LIMIT " . $start_from . ", " . $records_per_page;
}
*/

$result = sqlsrv_query($connect, $query, array(), array('Scrollable' => 'buffered'));

if ($result) {
    while ($row = sqlsrv_fetch_array($result)) {
        $data[] = $row;
    }
}

$query1 = "SELECT * FROM Readers";
$result1 = sqlsrv_query($connect, $query1, array(), array('Scrollable' => 'buffered'));
if($result1){
$total_records = sqlsrv_num_rows($result1);
}

$output = array(
    'current'  => intval($_POST["current"]),
    'rowCount'  => 10,
    'total'   => intval($total_records),
    'rows'   => $data
);

echo json_encode($output);
?>