<?php
error_reporting(0);
session_start();
include_once 'oesdb.php';
if(!isset($_SESSION['stdname'])) {
    $_GLOBALS['message']="会话超时,请重新登录.";
}
else if(isset($_REQUEST['logout']))
{
    //Log out and redirect login page
    unset($_SESSION['stdname']);
    header('Location: index.php');

}
else if(isset($_REQUEST['dashboard'])){
    //redirect to dashboard
   
     header('Location: stdwelcome.php');

}
if(isset($_SESSION['starttime']))
{
    unset($_SESSION['starttime']);
    unset($_SESSION['endtime']);
    unset($_SESSION['tqn']);
    unset($_SESSION['qn']);
    unset($_SESSION['duration']);
    executeQuery("update studenttest set status='over' where testid=".$_SESSION['testid']." and stdid=".$_SESSION['stdid'].";");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
  <head>
    <title>测试确认</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <script type="text/javascript" src="validate.js" ></script>
    </head>
  <body >
       <?php

        if($_GLOBALS['message']) {
            echo "<script type='text/javascript'>alert('".$_GLOBALS['message']."');</script>";
        }
        ?>
     

       <?php require 'header.php' ?>

        <!-- Main -->
        <div id="main" class="wrapper style1">
          <div class="container">
            <header class="major">
              <h2>总结</h2>       
            </header>
           <form id="editprofile" action="editprofile.php" method="post">
          <div class="menubar">
            
          </div>
      <div class="page">
          <h3 style="button small fittext-align:center;">你的结果已成功提交!点此<b><a class="action small" href="viewresult.php">查看结果</a></b> </h3>
          <?php
                        }
          ?>
      </div>

           </form>
     </div>
       <?php require 'footer.php' ?>
            </div>
  </body>
</html>

