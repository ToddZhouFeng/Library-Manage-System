<?php
    //session_start();//已设置auto start
    function loginOut(){
        unset($_SESSION['readerID']);
        unset($_SESSION['adminID']);
        unset($_SESSION['name']);
        // header('Location : /index.php');
    }
    loginOut();
    echo "<script> alert('已退出，请重新登录！') </script>";
    echo "<script>window.location.href='/test/index.php'</script>";
?>