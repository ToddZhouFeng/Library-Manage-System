<?php
header("Content-type:application/json");
if (!isset($_SESSION['adminID'])) {
    exit; //未登录
}
$bookname = !empty($_GET['bookname']) ? (trim($_GET['bookname'])) : '0';
$searchingID = !empty($_GET['searchingID']) ? (trim($_GET['searchingID'])) : '0';
$ISBN = !empty($_GET['ISBN']) ? (trim($_GET['ISBN'])) : '0';
$writer = !empty($_GET['writer']) ? (trim($_GET['writer'])) : '0';
$translator = !empty($_GET['translator']) ? (trim($_GET['translator'])) : '0';
$publishDate = !empty($_GET['publishDate']) ? (trim($_GET['publishDate'])) : '0';
$publishDate = str_replace('/', '-', $publishDate);
$price = !empty($_GET['price']) ? (trim($_GET['price'])) : '0';
$price = intval($price);
if ($bookname == '0' || $ISBN == '0') {
    echo "[{\"result\":\"1\"}]"; //无输入
    exit;
}
$ISBNP = explode('-', $ISBN);
if (sizeof($ISBNP) == 4) $ISBNP = $ISBNP[1];
else $ISBNP = $ISBNP[2];
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
} else {
    if ($bookname == '0' || $ISBN == '0') {
        echo "[{\"result\":\"1\"}]"; //无输入
        exit;
    } else {
        $add_pub = 0; //判断是否需要添加出版社信息
        $is_add_book = "SELECT * FROM BFinf WHERE ISBN = '$ISBN'";
        $result = sqlsrv_query($connect, $is_add_book, array(), array('Scrollable' => 'buffered'));
        if ($result) {
            $num = sqlsrv_num_rows($result);
            if ($num == 0) {
                //向图书信息表 BFInf 中插入信息
                $sql = "INSERT INTO BFInf(SearchingID, BookName, Price, ISBN, PublishDat, Trainslator, Writer) VALUES('$searchingID','$bookname', $price, '$ISBN', '$publishDate', '$translator', '$writer')";
                $result = sqlsrv_query($connect, $sql, array(), array('Scrollable' => 'buffered'));
                if (!$result) {
                    echo "[{\"result\":\"5\"}]";
                    exit;
                }
            }
            $is_add_pub = "SELECT * FROM PublisherInf WHERE ISBNP = $ISBNP";
            $result = sqlsrv_query($connect, $is_add_pub, array(), array('Scrollable' => 'buffered'));
            $num = sqlsrv_num_rows($result);
            if ($num == 0) {
                $add_pub = $ISBNP;
            } else {
                $add_pub = 0;
            }
            $adminID = strval($_SESSION['adminID']);
            //向具体图书表 Books 中插入一本新书
            $upload_book = "INSERT INTO Books( Replenish, State, PublisherID, Adnumber, ISBN, CountNumber) VALUES( 0, 1, $ISBNP, $adminID,'$ISBN', (SELECT COUNT(ISBN) FROM Books WHERE ISBN = '$ISBN')+1)";
            $result = sqlsrv_query($connect, $upload_book, array(), array('Scrollable' => 'buffered'));
            if ($result) {
                $result = sqlsrv_query($connect, $is_add_book, array(), array('Scrollable' => 'buffered'));
                while ($row = sqlsrv_fetch_array($result)) {
                    $search_result[] = $row;
                }
                $search_result[0]["add_pub"] = $add_pub;
                // 将数组转成json格式
                echo json_encode($search_result);
            } else echo "[{\"result\":\"2\"}]";
        } else {
            echo "[{\"result\":\"3\"}]";
            exit;
        }
    }
}
?>