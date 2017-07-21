<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<?php
error_reporting(0);
session_start();
include_once 'oesdb.php';
if(!isset($_SESSION['stdname'])) {
    $_GLOBALS['message']="会话超时.点击这里<a href=\"index.php\">重新登录</a>";
}
else if(isset($_REQUEST['logout'])) {
    //Log out and redirect login page
        unset($_SESSION['stdname']);
        header('Location: index.php');

    }
    else if(isset($_REQUEST['dashboard'])) {
        //redirect to dashboard
            header('Location: stdwelcome.php');

        }
        else if(isset($_REQUEST['resume'])) {
            //test code preparation
                if($r=mysql_fetch_array($result=executeQuery("select testname from test where testid=".$_REQUEST['resume'].";"))) {
                    $_SESSION['testname']=htmlspecialchars_decode($r['testname'],ENT_QUOTES);
                    $_SESSION['testid']=$_REQUEST['resume'];
                }
            }
            else if(isset($_REQUEST['resumetest'])) {
                //Prepare the parameters needed for Test Conducter and redirect to test conducter
                    if(!empty($_REQUEST['tc'])) {
                        $result=executeQuery("select DECODE(testcode,'oespass') as tcode from test where testid=".$_SESSION['testid'].";");

                        if($r=mysql_fetch_array($result)) {
                            if(strcmp(htmlspecialchars_decode($r['tcode'],ENT_QUOTES),htmlspecialchars($_REQUEST['tc'],ENT_QUOTES))!=0) {
                                $display=true;
                                $_GLOBALS['message']="您输入了无效的测试代码。请再次尝试。";
                            }
                            else {
                            //now prepare parameters for Test Conducter and redirect to it.

                                $result=executeQuery("select totalquestions,duration from test where testid=".$_SESSION['testid'].";");
                                $r=mysql_fetch_array($result);
                                $_SESSION['tqn']=htmlspecialchars_decode($r['totalquestions'],ENT_QUOTES);
                                $_SESSION['duration']=htmlspecialchars_decode($r['duration'],ENT_QUOTES);
                                $result=executeQuery("select DATE_FORMAT(starttime,'%Y-%m-%d %H:%i:%s') as startt,DATE_FORMAT(endtime,'%Y-%m-%d %H:%i:%s') as endt from studenttest where testid=".$_SESSION['testid']." and stdid=".$_SESSION['stdid'].";");
                                $r=mysql_fetch_array($result);
                                $_SESSION['starttime']=$r['startt'];
                                $_SESSION['endtime']=$r['endt'];
                                $_SESSION['qn']=1;
                                header('Location: testconducter.php');
                            }

                        }
                        else {
                            $display=true;
                            $_GLOBALS['message']="您输入了无效的测试代码。请再次尝试。";
                        }
                    }
                    else {
                        $display=true;
                        $_GLOBALS['message']="先输入测试码！";
                    }
                }


?>

<html>
    <head>
        <title>恢复测试</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta http-equiv="CACHE-CONTROL" content="NO-CACHE"/>
        <meta http-equiv="PRAGMA" content="NO-CACHE"/>
        <meta name="ROBOTS" content="NONE"/>
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
              <h2>恢复测试</h2>       
            </header>
          </div>
        </div>

            <form id="summary" action="resumetest.php" method="post">
                <div class="menubar">
                    <ul id="menu">
                    <?php if(isset($_SESSION['stdname'])) {
// Navigations
                    ?>
                       

                    </ul>


                </div>
                <div class="page">

    <?php
    if(isset($_REQUEST['resume'])) {
        echo "<div class=\"pmsg\" style=\"text-align:center;\">What is the Code of ".$_SESSION['testname']." ? </div>";
    }
    else {
        echo "<div class=\"pmsg\" style=\"text-align:center;\">待恢复测试</div>";
    }
    ?>
                        <?php

                        if(isset($_REQUEST['resume'])|| $display==true) {
                            ?>
                    <table cellpadding="30" cellspacing="10">
                        <tr>
                            <td>输入测试码</td>
                            <td><input type="text" tabindex="1" name="tc" value="" size="16" /></td>
                            <td><div class="help"><b>注意:</b><br/>快速输入测试码<br/>后按继续按钮以使用剩余时间</div></td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <input type="submit" tabindex="3" value="Resume Test" name="resumetest" class="subbtn" />
                            </td>
                        </tr>
                    </table>


    <?php
    }
    else {

        $result=executeQuery("select t.testid,t.testname,DATE_FORMAT(st.starttime,'%d %M %Y %H:%i:%s') as startt,sub.subname as sname,TIMEDIFF(st.endtime,CURRENT_TIMESTAMP) as remainingtime from subject as sub,studenttest as st,test as t where sub.subid=t.subid and t.testid=st.testid and st.stdid=".$_SESSION['stdid']." and st.status='inprogress' order by st.starttime desc;");
        if(mysql_num_rows($result)==0) {
            echo"<h3 style=\"text-align:center;\">没有可恢复的未完成测试，请重试..!</h3>";
        }
        else {
        //editing components
            ?>
                    <table cellpadding="30" cellspacing="10" class="datatable">
                        <tr>
                            <th>Date and Time</th>
                            <th>Test</th>
                            <th>Subject</th>
                            <th>Remaining Time</th>
                            <th>Resume</th>
                        </tr>
                                <?php
                                while($r=mysql_fetch_array($result)) {
                                    $i=$i+1;
                                    if($r['remainingtime']<0) {
                //IF Suppose MySQL Event fails for some reasons to change status this condtion becomes true.

                //   executeQuery("update studenttest set status='over' where stdid=".$_SESSION['stdid']." and testid=".$r['testid'].";");
                //      continue ;
                }

                if($i%2==0) {
                    echo "<tr class=\"alt\">";
                                        }
                                        else { echo "<tr>";}
                                        echo "<td>".$r['startt']."</td><td>".htmlspecialchars_decode($r['testname'],ENT_QUOTES)."</td><td>".htmlspecialchars_decode($r['sname'],ENT_QUOTES)."</td><td>".$r['remainingtime']."</td>";
                                        echo"<td class=\"tddata\"><a title=\"Resume\" href=\"resumetest.php?resume=".$r['testid']."\"><img src=\"images/resume.png\" height=\"30\" width=\"60\" alt=\"Resume\" /></a></td></tr>";
                                    }

                                    ?>

                    </table>
                                <?php
                                }

                            }

                            closedb();
                        }
                        ?>
                        
                </div>

            </form>
           <?php require 'footer.php' ?>

  </body>
</html>

