<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#main-navbar">
                <span class="sr-only">切换导航</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="./">图书馆
                <?php
                if (isset($_SESSION['readerID'])) {
                    echo '&nbsp欢迎您' . $_SESSION["name"];
                }
                elseif(isset($_SESSION['adminID'])){
                    echo '&nbsp管理员：' . $_SESSION["name"];
                }
                ?>
            </a>
        </div>
        <div class="collapse navbar-collapse" id="main-navbar">
            <ul class="nav navbar-nav">
                <li class="dropdown">
                    <a href="index.html" class="dropdown-toggle" data-toggle="dropdown">
                        简介 <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="history.html">历史</a></li>
                        <li><a href="#">馆藏资源</a></li>
                        <li><a href="#">开放日期</a></li>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        读者 <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        <?php if (isset($_SESSION['readerID'])) : ?>
                            <li><a href="reader_info.html">个人信息</a></li>
                            <li><a href="book_borrow.html">借还书</a></li>
                            <li><a href="login/logout.php">退出</a></li>
                        <?php else : ?>
                            <li><a href="reader_login.html">登录</a></li>
                            <li><a href="reader_register.html">注册</a></li>
                        <?php endif; ?>
                    </ul>
                </li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        管理员 <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu multi-level">
                        <?php if (isset($_SESSION['adminID'])) : ?>
                            <li><a href="book_edit.html">添加图书</a></li>
                            <li><a href="book_manage.html">图书管理</a></li>
                            <li><a href="reader_manage.html">读者管理</a></li>
                            <li><a href="login/logout.php">退出</a></li>
                        <?php else : ?>
                            <li><a href="admin_login.html">管理员登录</a></li>
                            <li><a href="admin_register.html">管理员注册</a></li>
                        <?php endif; ?>
                    </ul>
                </li>
                <li>

                </li>
            </ul>
            <div>
                <form class="navbar-form navbar-right" role="search">
                    <div class="form-group input-group">
                        <input type="text" class="form-control" placeholder="搜索图书" id="bookname">
                        <span class="input-group-btn">
                            <a class="btn btn-default" data-toggle="popover" title="搜索" onclick="searchbook()">
                                <span class="glyphicon glyphicon-search"></span>
                                <!--搜索-->
                            </a>
                        </span>
                </form>
            </div>
        </div>
    </div>
</nav>

<script>
    function searchbook() {
        var bookname = document.getElementById("bookname").value;
        if (bookname != '') {
            var url = 'book_search.html?bookname=' + bookname;
            url = encodeURI(url);
            window.open(url);
        }
    }
</script>

<script>
        function onKeyDown(event) {
            var e = event || window.event || arguments.callee.caller.arguments[0];
            if (e && e.keyCode == 13) { // enter 键
                document.getElementById("bookname").click();
            }
        }
    </script>