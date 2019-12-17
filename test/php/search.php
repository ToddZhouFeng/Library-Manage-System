<?php
header("Content-type:application/json");
$keywords = $_GET["keywords"];
$area = "all";
$area = trim($_GET["area"]);
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

//过滤关键词左右空格
$keyword = trim($keywords);
if (empty($keyword)) {
    //如果关键词为空，则返回result=0
    echo "[{\"result\":\"0\"}]";
}else{
    $sql = "SELECT BookName, Price, Books.ISBN, PublishDat, Trainslator, Writer, SearchingID, State, CountNumber, Name FROM Books LEFT JOIN BFInf ON Books.ISBN = BFInf.ISBN LEFT JOIN PublisherInf ON Books.PublisherID = PublisherInf.ISBNP";
    if (!empty($_GET["keywords"])) {
        if($area == "book" ){
            $sql .= " WHERE (BFinf.BookName LIKE '%%".$keywords."%%') ";
        }
        else if($area == "writer"){
            $sql .= " WHERE (BFinf.Writer LIKE '%%".$keywords."%%')";
        }
        else if($area == "publisher"){
            $sql .= " WHERE (PublisherInf.Name LIKE '%%".$keywords."%%')";
        }
        else{
            $sql .= " WHERE (BFinf.BookName LIKE '%%".$keywords."%%') ";
            $sql .= "OR (PublisherInf.Name LIKE '%%" . $keywords . "%%') ";
            $sql .= "OR (BFInf.Writer LIKE '%%" . $keywords . "%%') ";
            $sql .= "OR (BFInf.Trainslator LIKE '%%" . $keywords . "%%') ";
        }
    }
    $result = sqlsrv_query($connect, $sql, array(), array('Scrollable' => 'buffered'));
    $num = sqlsrv_num_rows($result);
    if ($num) {
        $search_result = array();
        while($row = sqlsrv_fetch_array($result)){
            $search_result[] = $row;
        }
        // 将数组转成json格式
        echo json_encode($search_result);

    }else{
    //如果查询无果，则返回result=1
    echo "[{\"result\":\"1\"}]";
    }
}
?>