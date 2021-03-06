<?php
/* Procedure
*********************************************

 * ----------- *
 * PHP Section *
 * ----------- *

Step 1: Perform Session Validation.

Step 2: Event to Process...
        Case 1 : Logout - perform session cleanup.
        Case 2 : Dashboard - redirect to Dashboard.
        Case 3 : Edit - Update the Information.
        

 * ------------ *
 * HTML Section *
 * ------------ *

Step 3: Display the HTML Components for...
        Case 1: Default Mode - Displays the saved information.
*********************************************
*/

error_reporting(0);
session_start();
include_once 'oesdb.php';
/************************** Step 1 *************************/
if(!isset($_SESSION['stdname'])) {
    $_GLOBALS['message']="会话超时,请重新登录.";
}
else if(isset($_REQUEST['logout']))
{
    /************************** Step 2 - Case 1 *************************/
    //Log out and redirect login page
    unset($_SESSION['stdname']);
    header('Location: index.php');

}
else if(isset($_REQUEST['dashboard'])){
     /************************** Step 2 - Case 2 *************************/
        //redirect to dashboard
     header('Location: stdwelcome.php');

    }else if(isset($_REQUEST['savem']))
{
      /************************** Step 2 - Case 3 *************************/
                //updating the modified values
    if(empty($_REQUEST['cname'])||empty ($_REQUEST['password'])||empty ($_REQUEST['email']))
    {
         $_GLOBALS['message']="一些必填字段为空,请返回检查.";
    }
    else
    {
     $query="update student set stdname='".htmlspecialchars($_REQUEST['cname'],ENT_QUOTES)."', stdpassword='".htmlspecialchars($_REQUEST['password'],ENT_QUOTES)."',emailid='".htmlspecialchars($_REQUEST['email'],ENT_QUOTES)."',contactno='".htmlspecialchars($_REQUEST['contactno'],ENT_QUOTES)."',address='".htmlspecialchars($_REQUEST['address'],ENT_QUOTES)."',city='".htmlspecialchars($_REQUEST['city'],ENT_QUOTES)."',pincode='".htmlspecialchars($_REQUEST['pin'],ENT_QUOTES)."' where stdid='".$_REQUEST['student']."';";
     if(!@executeQuery($query))
        $_GLOBALS['message']=mysql_error();
     else
        $_GLOBALS['message']="您的个人资料已成功更新.";
    }
    closedb();

}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
  
  <?php require 'header.php' ?>


<!-- Main -->
        <div id="main" class="wrapper style1">
          <div class="container">
            <header class="major">
              <h2>个人资料</h2>       
            </header>
          

  <body >
       <?php

        if($_GLOBALS['message']) {
            echo "<script type='text/javascript'>alert('".$_GLOBALS['message']."');</script>";
        }
        ?>

           <form id="editprofile" action="editprofile.php" method="post">
          
      <div class="page">
          <?php
                       
 /************************** Step 3 - Case 1 *************************/
        // Default Mode - Displays the saved information.
                        $result=executeQuery("select stdid,stdname,stdpassword as stdpass ,emailid,contactno,address,city,pincode from student where stdname='".$_SESSION['stdname']."';");
                        if(mysql_num_rows($result)==0) {
                           header('Location: stdwelcome.php');
                        }
                        else if($r=mysql_fetch_array($result))
                        {
                           //editing components
                 ?>
           <table cellpadding="20" cellspacing="20"  >
              <tr>
                  <td>用户名</td>
                  <td><input type="text" name="cname" value="<?php echo htmlspecialchars_decode($r['stdname'],ENT_QUOTES); ?>" size="16" onkeyup="isalphanum(this)"/></td>

              </tr>

                      <tr>
                  <td>密码</td>
                  <td><input type="password" name="password" value="<?php echo htmlspecialchars_decode($r['stdpass'],ENT_QUOTES); ?>" size="16" onkeyup="isalphanum(this)" /></td>
                 
              </tr>

              <tr>
                  <td>邮箱</td>
                  <td><input type="text" name="email" value="<?php echo htmlspecialchars_decode($r['emailid'],ENT_QUOTES); ?>" size="16" /></td>
              </tr>
                       <tr>
                  <td>联系电话</td>
                  <td><input type="text" name="contactno" value="<?php echo htmlspecialchars_decode($r['contactno'],ENT_QUOTES); ?>" size="16" onkeyup="isnum(this)"/></td>
              </tr>

                  <tr>
                  <td>地址</td>
                  <td><textarea name="address" cols="20" rows="3"><?php echo htmlspecialchars_decode($r['address'],ENT_QUOTES); ?></textarea></td>
              </tr>
                       <tr>
                  <td>城市</td>
                  <td><input type="text" name="city" value="<?php echo htmlspecialchars_decode($r['city'],ENT_QUOTES); ?>" size="16" /></td>
              </tr>
                       <tr>
                  <td>PIN码</td>
                  <td><input type="hidden" name="student" value="<?php echo $r['stdid']; ?>"/><input type="text" name="pin" value="<?php echo htmlspecialchars_decode($r['pincode'],ENT_QUOTES); ?>" size="16" onkeyup="isnum(this)" /></td>
              </tr>

            </table>
            <div class="menubar">
             
                        <?php if(isset($_SESSION['stdname'])) {
                         // Navigations
                         ?>
                        
                      <ul class="actions fit small">
                        <li><input type="submit" value="保存" name="savem" class="button special" onclick="validateform('editprofile')" title="Save the changes"/></li>
                     </ul>
                     
               
          </div>
<?php
                        closedb();
                        }
                        
                        }
  ?>
      </div>

           </form>
           </div>
        </div>
      <?php require 'footer.php' ?>

      </div>
  </body>
</html>
