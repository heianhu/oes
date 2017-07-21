<?php

/*
***************************************************
*** 在线考试系统                   ***
***---------------------------------------------***
*** License: GNU General Public License V.3     ***
*** Author: Manjunath Baddi                     ***
*** Title: Admin Welcome                        ***
***************************************************
*/

/* Procedure
*********************************************
 * ----------- *
 * PHP Section *
 * ----------- *
Step 1: Perform Session Validation.
 * ------------ *
 * HTML Section *
 * ------------ *
Step 2: Display the Dashboard.

*********************************************
*/

error_reporting(0);
/********************* Step 1 *****************************/
session_start();
        if(!isset($_SESSION['admname'])){
            $_GLOBALS['message']="会话超时.点击这里<a href=\"index.php\">重新登录</a>";
        }
        else if(isset($_REQUEST['logout'])){
           unset($_SESSION['admname']);
            $_GLOBALS['message']="您已成功注销";
            header('Location: index.php');
        }
?>

<html>
    <head>
        <title>后台管理</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    </head>
    <body>
        <?php
       /********************* Step 2 *****************************/
        if(isset($_GLOBALS['message'])) {
            echo "<script type='text/javascript'>alert('".$_GLOBALS['message']."');</script>";
        }
        ?>

        <?php require 'admheader.php' ?>
        <div id="main" class="wrapper style1">

        <div id="container">
           
        <header class="major">
                  <h2>网络测试系统后台管理</h2>       
                   </header>
                    <div class="page">
                   <h3 style="text-align:center;">点击上方功能开始使用</h3> 
                    </div>

          <?php require '../footer.php' ?>

      </div>
  </body>
</html>
