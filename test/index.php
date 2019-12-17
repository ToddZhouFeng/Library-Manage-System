<!DOCTYPE html>
<html>
<head>
  <title>图书馆</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!--采用 Staticfile CDN 上的 Bootstrap 3 库 -->
  <link rel="stylesheet" href="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/css/bootstrap.min.css"></script>
  <script src="https://cdn.staticfile.org/jquery/2.1.1/jquery.min.js"></script>
  <script src="https://cdn.staticfile.org/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <script src="https://cdn.staticfile.org/jquery/1.10.2/jquery.min.js"></script>
</head>                                                                                                                                                                                                                                                                                                                                               

<body>
  <!-- 导航栏-->
  <div id="main_navbar"></div>
  <script>
    $('#main_navbar').load("layout/navbar.php");
  </script>

<br>
<br>
<br>

<div class="container">
  <div class="jumbotron">
    <h1>图书管理系统</h1>
    <p>github.com/ToddZhouFeng/Library-Manage-System</p> 
  </div>
  <div class="row">
    <div class="col-sm-4">
      <h3 href="book-search.html">轻松查找图书</h3>
      <p>在线搜索</p>
    </div>
    <div class="col-sm-4">
      <h3>简单借还图书</h3>
      <p>自助借还</p>
    </div>
    <div class="col-sm-4">
      <h3>方便管理信息</h3>        
      <p>自动统计</p>
    </div>
  </div>
</div>

</body>
</html>