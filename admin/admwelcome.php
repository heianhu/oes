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

        <?php require 'admheader.php' ?>

        <div id="container">
            
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

        
               
                <?php }?>

            </div>


          <?php require '../footer.php' ?>

      </div>
  </body>
</html>
