

<?php
/*
***************************************************
*** Online Examination System                   ***
***---------------------------------------------***
*** License: GNU General Public License V.3     ***
*** Author: Manjunath Baddi                     ***
*** Title: Student Welcome                      ***
***************************************************
*/

error_reporting(0);
session_start();
        if(!isset($_SESSION['stdname'])){
            $_GLOBALS['message']="Session Timeout.Click here to <a href=\"index.php\">Re-LogIn</a>";
        }
        else if(isset($_REQUEST['logout'])){
                unset($_SESSION['stdname']);
            $_GLOBALS['message']="You are Loggged Out Successfully.";
            header('Location: index.php');
        }
?>
<html>
    <head>
        <title>OES-DashBoard</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
       <!--  <link href="exam.css" rel="stylesheet" type="text/css">
        <link href="base.css" rel="stylesheet" type="text/css"> -->
        <link rel="stylesheet" type="text/css" href="oes.css"/>
    </head>
    <body>
        <?php
       
        if($_GLOBALS['message']) {
            echo "<div class=\"message\">".$_GLOBALS['message']."</div>";
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
