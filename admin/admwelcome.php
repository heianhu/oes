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
        <title>OES-DashBoard</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link rel="stylesheet" type="text/css" href="../oes.css"/>
    </head>
    <body>
        <?php
       /********************* Step 2 *****************************/
        if(isset($_GLOBALS['message'])) {
            echo "<div class=\"message\">".$_GLOBALS['message']."</div>";
        }
        ?>
        <div id="container">
            <div class="header">
                <img style="margin:10px 2px 2px 10px;float:left;" height="80" width="200" src="../images/logo.gif" alt="OES"/><h3 class="headtext"> &nbsp;在线考试系统 </h3><h4 style="color:#ffffff;text-align:center;margin:0 0 5px 5px;"><i>...因为 考试 很重要</i></h4>
            </div>
            <div class="menubar">

                <form name="admwelcome" action="admwelcome.php" method="post">
                    <ul id="menu">
                        <?php if(isset($_SESSION['admname'])){ ?>
                        <li><input type="submit" value="登出" name="logout" class="subbtn" title="Log Out"/></li>
                        <?php } ?>
                    </ul>
                </form>
            </div>
            <div class="admpage">
                <?php if(isset($_SESSION['admname'])){ ?>

        
                <!-- <img height="600" width="100%" alt="back" class="btmimg" src="../images/trans.png"/> -->
                <div class="topimg">
                    <!-- <p><img height="500" width="600" style="border:none;"  src="../images/admwelcome.jpg" alt="image"  usemap="#oesnav" /></p> -->

                   <!--  <map name="oesnav">
                        <area shape="circle" coords="150,120,70" href="usermng.php" alt="Manage Users" title="This takes you to User Management Section" />
                        <area shape="circle" coords="450,120,70" href="submng.php" alt="Manage Subjects" title="This takes you to Subjects Management Section" />
                        <area shape="circle" coords="300,250,60" href="rsltmng.php" alt="Manage Test Results" title="Click this to view Test Results." />
                        <area shape="circle" coords="150,375,70" href="testmng.php?forpq=true" alt="Prepare Questions" title="Click this to prepare Questions for the Test" />
                        <area shape="circle" coords="450,375,70" href="testmng.php" alt="Manage Tests" title="This takes you to Tests Management Section" />
                    </map> -->
                    <button onclick="{location.href='usermng.php'}" class="subbtn" >管理用户</button>
                    <button onclick="{location.href='submng.php'}" class="subbtn" >管理科目</button>
                    <button onclick="{location.href='rsltmng.php'}" class="subbtn" >管理测试结果</button>
                    <button onclick="{location.href='testmng.php?forpq=true'}" class="subbtn" >准备题目</button>
                    <button onclick="{location.href='testmng.php'}" class="subbtn" >管理测试</button>
                </div>
                <?php }?>

            </div>

          <div id="footer">
          <p style="font-size:70%;color:#ffffff;"> Developed By-<b>翻江倒海</b></p>
      </div>
      </div>
  </body>
</html>
