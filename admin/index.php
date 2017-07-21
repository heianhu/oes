<?php
 /* Procedure
*********************************************
 * ------------ *
 * HTML Section *
 * ------------ *
Step 1: Display the Html page to receive Authentication Parameters(Name & Password).
 * ----------- *
 * PHP Section *
 * ----------- *
Step 2: IF POST array has some varibles then, perform authentication.

*********************************************
*/
      error_reporting(0);
      session_start();
      include_once '../oesdb.php';

      /***************************** Step 2 ****************************/
      if(isset($_REQUEST['admsubmit']))
      {
          
          $result=executeQuery("select * from adminlogin where admname='".htmlspecialchars($_REQUEST['name'],ENT_QUOTES)."' and admpassword='".md5(htmlspecialchars($_REQUEST['password'],ENT_QUOTES))."'");
        
         // $result=mysql_query("select * from adminlogin where admname='".htmlspecialchars($_REQUEST['name'])."' and admpassword='".md5(htmlspecialchars($_REQUEST['password']))."'");
          if(mysql_num_rows($result)>0)
          {
              
              $r=mysql_fetch_array($result);
              if(strcmp($r['admpassword'],md5(htmlspecialchars($_REQUEST['password'],ENT_QUOTES)))==0)
              {
                  $_SESSION['admname']=htmlspecialchars_decode($r['admname'],ENT_QUOTES);
                  unset($_GLOBALS['message']);
                  header('Location: admwelcome.php');
              }else
          {
             $_GLOBALS['message']="检查您的用户名和密码.";
                 
          }

          }
          else
          {
              $_GLOBALS['message']="检查您的用户名和密码.";
              
          }
          closedb();
      }
 ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
  <head>
    <title>管理员登录</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
  </head>
  <body>
<!--
*********************** Step 1 ****************************
-->
      <?php
      
        if(isset($_GLOBALS['message']))
        {
         echo "<script type='text/javascript'>alert('".$_GLOBALS['message']."');</script>";
        }
      ?>

      <?php require 'admheader.php' ?>


<!-- Main -->
        <div id="main" class="wrapper style1">
          <div class="container">
            <header class="major">
              <h2>管理员登录</h2>       
            </header>
        

     

      
      <div class="page">
              <form id="indexform" action="index.php" method="post">
              <table align="center">
              <tr>
                  <td>管理员名称</td>
                  <td><input type="text" name="name" value="" size="16" /></td>
              </tr>
              <tr>
                  <td> 密码</td>
                  <td><input type="password" name="password" value="" size="16" /></td>
              </tr>

              <tr>
                  <td colspan="2" align="center">
                      <input type="submit" value="登录" name="admsubmit" class="button  special  " />
                  </td>
              </tr>
            </table>

        </form>

      </div>

        </div>

            <?php require '../footer.php' ?>

      </div>
  </body>
</html>
