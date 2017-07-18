 <?php
 /* Procedure
*********************************************

 * ----------- *
 * PHP Section *
 * ----------- *
Step 1: Event to Process...
        Case 1 : Register - redirect to Registration Page.
        Case 2 : Authenticate
       

 * ------------ *
 * HTML Section *
 * ------------ *
Step 2: Display the Html page to receive Authentication Parameters(Name & Password).

*********************************************
*/
 
      error_reporting(0);
      session_start();
      include_once 'oesdb.php';
/***************************** Step 1 : Case 1 ****************************/
 //redirect to registration page
      if(isset($_REQUEST['register']))
      {
            header('Location: register.php');
      }
      else if($_REQUEST['stdsubmit'])
      {
/***************************** Step 1 : Case 2 ****************************/
 //Perform Authentication
          $result=executeQuery("select *,DECODE(stdpassword,'oespass') as std from student where stdname='".htmlspecialchars($_REQUEST['name'],ENT_QUOTES)."' and stdpassword=ENCODE('".htmlspecialchars($_REQUEST['password'],ENT_QUOTES)."','oespass')");
          if(mysql_num_rows($result)>0)
          {

              $r=mysql_fetch_array($result);
              if(strcmp(htmlspecialchars_decode($r['std'],ENT_QUOTES),(htmlspecialchars($_REQUEST['password'],ENT_QUOTES)))==0)
              {
                  $_SESSION['stdname']=htmlspecialchars_decode($r['stdname'],ENT_QUOTES);
                  $_SESSION['stdid']=$r['stdid'];
                  unset($_GLOBALS['message']);
                  header('Location: stdwelcome.php');
              }else
          {
              $_GLOBALS['message']="检查您的用户名和密码。";
          }

          }
          else
          {
              $_GLOBALS['message']="检查您的用户名和密码。";
          }
          closedb();
      }
 ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
  <head>
    <title>在线考试系统</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <link rel="stylesheet" type="text/css" href="oes.css"/>
	<script type="text/javascript" src="validate.js" ></script>
  </head>
  
  <body>
      <?php

        if($_GLOBALS['message'])
        {
			//echo "<div class=\"message\">".$_GLOBALS['message']."</div>";
			echo "<script type='text/javascript'>alert('".$_GLOBALS['message']."');</script>";
        }
      ?>
      
      <?php require 'header.php' ?>

      <!-- Main -->
      
        <div id="main" class="wrapper style1">
          <div class="container">
            <header class="major">
              <h2>用户登陆</h2>       
            </header>
        

     <form id="stdloginform" action="index.php" method="post">
      <div class="menubar">
       
       <ul id="menu" class="actions  small">
                    <?php if(isset($_SESSION['stdname'])){
                          header('Location: stdwelcome.php');}else{  
                          /***************************** Step 2 ****************************/
                        ?>

                      <!--  <li><input type="submit" value="Register" name="register" class="subbtn" title="Register"/></li>-->
        
                        <?php } ?>
                    </ul>

      </div>
                
      <div class="page">
              
              <table cellpadding="30" cellspacing="10">
              <tr>
                  <td>用户名</td>
                  <td><input type="text" tabindex="1" name="name" value="" size="16" /></td>

              </tr>
              <tr>
                  <td>密码</td>
                  <td><input type="password" tabindex="2" name="password" value="" size="16" /></td>
              </tr>

              <tr>
                  <td colspan="2">
                      <input type="submit" tabindex="3" value="登录" name="stdsubmit" class="button" />
                  
                      
                    <a href="register.php" class="button ">注册</a>

                  </td>

              </tr>
            </table>


      </div>
       </form>
  </div>
        </div>
     <?php require 'footer.php' ?>
      </div>
  </body>
</html>
