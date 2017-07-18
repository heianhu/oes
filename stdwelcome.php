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
       <!--  <link href="exam.css" rel="stylesheet" type="text/css">
        <link href="base.css" rel="stylesheet" type="text/css"> -->
        <link rel="stylesheet" type="text/css" href="oes.css"/>
    </head>
    <body>
        <?php
       
        if($_GLOBALS['message']) {
            echo "<script type='text/javascript'>alert('".$_GLOBALS['message']."');</script>";
        }
        ?>
        <div id="container">
           <div class="header">
           

            </div>
            <div class="menubar">

                <form name="stdwelcome" action="stdwelcome.php" method="post">
                    <ul id="menu">
                        <?php if(isset($_SESSION['stdname'])){ ?>
                    
                        <li><input type="submit" value="登出" name="logout" class="subbtn" title="Log Out"/></li>
                        <?php } ?>
                    </ul>
                </form>
            </div>
            <div class="stdpage">
                <?php if(isset($_SESSION['stdname'])){ 

                require 'header.php';
            
              
            }php ?>

            </div>


      </div>
            <?php    require 'footer.php'; ?>

  </body>
</html>
