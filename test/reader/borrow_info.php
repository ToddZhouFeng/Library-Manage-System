<?php
header("Content-type:application/json");
if (!isset($_SESSION['readerID'])) {
    exit; //未登录管理员
}
$ReaderID = $_SESSION['readerID'];
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


$query .= "SELECT BFInf.ISBN, Borrowtime, Returntime, BookName, Writer, CounterNum, Price, Name FROM Borrowing LEFT JOIN BFinf ON Borrowing.ISBN = BFInf.ISBN LEFT JOIN Books ON (Borrowing.ISBN = Books.ISBN AND Borrowing.CounterNum = Books.CountNumber) LEFT JOIN PublisherInf ON Books.PublisherID = PublisherInf.ISBNP";

if (!empty($_POST["searchPhrase"])) {
    $query .= " WHERE (BFinf.BookName LIKE '%%".$_POST["searchPhrase"]."%%'";
    $query .= "OR PublisherInf.Name LIKE '%%" . $_POST["searchPhrase"] . "%%' ";
    $query .= "OR BFInf.Writer LIKE '%%" . $_POST["searchPhrase"] . "%%') ";
    $query .= " AND Borrowing.ReaderID = $ReaderID";
}
else{
 $query .= " WHERE Borrowing.ReaderID = $ReaderID";
}


$order_by = '';
if (isset($_POST["sort"]) && is_array($_POST["sort"])) {
    foreach ($_POST["sort"] as $key => $value) {
        $order_by .= " $key $value, ";
    }
} else {
    $query .= " ORDER BY BookName DESC ";
}

if ($order_by != '') {
    $query .= ' ORDER BY ' . substr($order_by, 0, -2);
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

$query1 = "SELECT * FROM Borrowing WHERE Borrowing.ReaderID = $ReaderID";
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