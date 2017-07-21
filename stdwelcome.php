<?php
error_reporting(0);
session_start();
        if(!isset($_SESSION['stdname'])){
            $_GLOBALS['message']="会话超时.点击这里<a href=\"index.php\">重新登录</a>";
        }
        else if(isset($_REQUEST['logout'])){
                unset($_SESSION['stdname']);
            $_GLOBALS['message']="您已成功注销";
            header('Location: index.php');
        }
?>
<html>
    <head>
        <title>主面板</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    </head>
    <body>
        <?php
       
        if($_GLOBALS['message']) {
            echo "<script type='text/javascript'>alert('".$_GLOBALS['message']."');</script>";
        }
        ?>
             <?php  require 'header.php'; ?>  
             
        <div id="main" class="wrapper style1">
            <div id="container">
           
                    <header class="major">
                  <h2>欢迎使用网络测试系统</h2>       
                   </header>
                    <div class="page">
                   <h3 style="text-align:center;">点击上方功能开始使用</h3> 
                    </div>
            </div>
            
            
          



         </div>
            <?php    require 'footer.php'; ?>

  </body>
</html>
