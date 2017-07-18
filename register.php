<?php
 /* Procedure
*********************************************

 * ----------- *
 * PHP Section *
 * ----------- *
Step 1: Event to Process...
        Case 1 : Submit - Add the new Student to the System.
      
 * ------------ *
 * HTML Section *
 * ------------ *
Step 2: Display the Html page to receive the required information.

*********************************************
*/

error_reporting(0);
session_start();
include_once 'oesdb.php';

if(isset($_REQUEST['stdsubmit']))
{
 /***************************** Step 1 : Case 1 ****************************/
 //Add the new user information in the database
     $result=executeQuery("select max(stdid) as std from student");
     $r=mysql_fetch_array($result);
     if(is_null($r['std']))
     $newstd=1;
     else
     $newstd=$r['std']+1;

     $result=executeQuery("select stdname as std from student where stdname='".htmlspecialchars($_REQUEST['cname'],ENT_QUOTES)."';");

    // $_GLOBALS['message']=$newstd;
    if(empty($_REQUEST['cname'])||empty ($_REQUEST['password'])||empty ($_REQUEST['email']))
    {
         $_GLOBALS['message']="一些必填字段为空";
    }else if(mysql_num_rows($result)>0)
    {
        $_GLOBALS['message']="对不起，用户名不可用，请尝试其他名称。";
    }
    else
    {
     $query="insert into student values($newstd,'".htmlspecialchars($_REQUEST['cname'],ENT_QUOTES)."',ENCODE('".htmlspecialchars($_REQUEST['password'],ENT_QUOTES)."','oespass'),'".htmlspecialchars($_REQUEST['email'],ENT_QUOTES)."','".htmlspecialchars($_REQUEST['contactno'],ENT_QUOTES)."','".htmlspecialchars($_REQUEST['address'],ENT_QUOTES)."','".htmlspecialchars($_REQUEST['city'],ENT_QUOTES)."','".htmlspecialchars($_REQUEST['pin'],ENT_QUOTES)."')";
     if(!@executeQuery($query))
                $_GLOBALS['message']=mysql_error();
     else
     {
        $success=true;
        $_GLOBALS['message']="成功创建您的帐户。点击<a href=\"index.php\">此处</a>登录";
       // header('Location: index.php');
     }
    }
    closedb();
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
  <head>
    <title>注册</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <link rel="stylesheet" type="text/css" href="oes.css"/>
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
              <h2>新用户注册</h2>       
            </header>
         
          <div class="menubar">
              <?php if(!$success): ?>

            
              <?php endif; ?>
             
          </div>
      <div class="page">
          <?php
          if($success)
          {
                echo "<h2 style=\"text-align:center;color:#0000ff;\">感谢您注册在线考试系统。<br/><a href=\"index.php\">立即登录</a></h2>";
          }
          else
          {
           /***************************** Step 2 ****************************/
          ?>
          <form id="admloginform"  action="register.php" method="post" onsubmit="return validateform('admloginform');">
                   <table cellpadding="20" cellspacing="20" >
              <tr>
                  <td>用户名</td>
                  <td><input type="text" name="cname" value="" size="16" onkeyup="isalphanum(this)"/></td>

              </tr>

                      <tr>
                  <td>密码</td>
                  <td><input type="password" name="password" value="" size="16" onkeyup="isalphanum(this)" /></td>

              </tr>
                      <tr>
                  <td>重复密码</td>
                  <td><input type="password" name="repass" value="" size="16" onkeyup="isalphanum(this)" /></td>

              </tr>
              <tr>
                  <td>邮箱</td>
                  <td><input type="text" name="email" value="" size="16" /></td>
              </tr>
                       <tr>
                  <td>联系号码</td>
                  <td><input type="text" name="contactno" value="" size="16" onkeyup="isnum(this)"/></td>
              </tr>

                  <tr>
                  <td>地址</td>
                  <td><textarea name="address" cols="20" rows="3"></textarea></td>
              </tr>
                       <tr>
                  <td>城市</td>
                  <td><input type="text" name="city" value="" size="16" /></td>
              </tr>
                       <tr>
                  <td>PIN码</td>
                  <td><input type="text" name="pin" value="" size="16" onkeyup="isnum(this)" /></td>
              </tr>
                       <tr>
                           <td style="text-align:right;"><input type="submit" name="stdsubmit" value="Register" class="subbtn" /></td>
                  <td><input type="reset" name="reset" value="Reset" class="subbtn"/></td>
              </tr>
            </table>
        </form>
       <?php } ?>
        
            <?php require 'footer.php' ?>
 </div>
        </div>
      </div>
  </body>
</html>

