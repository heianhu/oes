<?php
/* Procedure
*********************************************

 * ----------- *
 * PHP Section *
 * ----------- *

Step 1: Perform Session Validation.

Step 2: Event to Process...
        Case 1 : Logout - perform session cleanup.
        Case 2 : Dashboard - redirect to Dashboard
        Case 3 : Back - Return back to Result Management.
     

 * ------------ *
 * HTML Section *
 * ------------ *

Step 3: Display the HTML Components for...
        Case 1: Display the test results in detail.
        Case 2: Default Mode.. in which test results are summarised.
        
*********************************************
*/

error_reporting(0);
session_start();
include_once '../oesdb.php';
/************************** Step 1 *************************/
if(!isset($_SESSION['admname'])) {
    $_GLOBALS['message']="会话超时.点击这里<a href=\"index.php\">重新登录</a>";
}
else if(isset($_REQUEST['logout'])) {
    /************************** Step 2 - Case 1 *************************/
    //Log out and redirect login page
        unset($_SESSION['admname']);
        header('Location: index.php');

    }
    else if(isset($_REQUEST['dashboard'])) {
    /************************** Step 2 - Case 2 *************************/
        //redirect to dashboard
            header('Location: admwelcome.php');

        }
        else if(isset($_REQUEST['back'])) {
    /************************** Step 2 - Case 3s *************************/
            //redirect to Result Management
                header('Location: rsltmng.php');

            }

?>
<html>
    <head>
        <title>OES-管理结果</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link rel="stylesheet" type="text/css" href="../oes.css"/>

    </head>
    <body>
        <?php

        if($_GLOBALS['message']) {
            echo "<script type='text/javascript'>alert('".$_GLOBALS['message']."');</script>";
        }
        ?>
<?php require 'admheader.php' ?>

        
        <!-- Main -->
        <div id="main" class="wrapper style1">
          <div class="container">
            <header class="major">
              <h2>测试结果管理</h2>       
            </header>
        
            <form name="rsltmng" action="rsltmng.php" method="post">
                <div class="menubar">


                    <ul class="actions  small">
                        <?php if(isset($_SESSION['admname'])) {
                        // Navigations

                            ?>
                        
                            <?php  if(isset($_REQUEST['testid'])) { ?>
                        <li><input type="submit" value="Back" name="back" class="button small fit" title="Manage Results"/></li>
                            <?php }else { ?>
                       
                            <?php } ?>
                    </ul>
                </div>
                <div class="page">
                        <?php
                        if(isset($_REQUEST['testid'])) {
 /************************** Step 3 - Case 1 *************************/
 // Defualt Mode: Displays the Detailed Test Results.
                            $result=executeQuery("select t.testname,DATE_FORMAT(t.testfrom,'%d %M %Y') as fromdate,DATE_FORMAT(t.testto,'%d %M %Y %H:%i:%S') as todate,sub.subname,IFNULL((select sum(marks) from question where testid=".$_REQUEST['testid']."),0) as maxmarks from test as t, subject as sub where sub.subid=t.subid and t.testid=".$_REQUEST['testid'].";") ;
                            if(mysql_num_rows($result)!=0) {

                                $r=mysql_fetch_array($result);
                                ?>
                    <table cellpadding="20" cellspacing="30" border="0" >
                        <tr>
                            <td colspan="2"><h3 style="text-align:center;">Test Summary</h3></td>
                        </tr>
                        <tr>
                            <td colspan="2" ><hr style="color:#ff0000;border-width:4px;"/></td>
                        </tr>
                        <tr>
                            <td>测试名称</td>
                            <td><?php echo htmlspecialchars_decode($r['testname'],ENT_QUOTES); ?></td>
                        </tr>
                        <tr>
                            <td>主题名称</td>
                            <td><?php echo htmlspecialchars_decode($r['subname'],ENT_QUOTES); ?></td>
                        </tr>
                        <tr>
                            <td>有效性</td>
                            <td><?php echo $r['fromdate']." To ".$r['todate']; ?></td>
                        </tr>
                        <tr>
                            <td>最大分数</td>
                            <td><?php echo $r['maxmarks']; ?></td>
                        </tr>
                        <tr><td colspan="2"><hr style="color:#ff0000;border-width:2px;"/></td></tr>
                        <tr>
                            <td colspan="2"><h3 style="text-align:center;">测试学生</h3></td>
                        </tr>
                        <tr>
                            <td colspan="2" ><hr style="color:#ff0000;border-width:4px;"/></td>
                        </tr>

                    </table>
                                <?php

                                $result1=executeQuery("select s.stdname,s.emailid,IFNULL((select sum(q.marks) from studentquestion as sq,question as q where q.qnid=sq.qnid and sq.testid=".$_REQUEST['testid']." and sq.stdid=st.stdid and sq.stdanswer=q.correctanswer),0) as om from studenttest as st, student as s where s.stdid=st.stdid and st.testid=".$_REQUEST['testid'].";" );

                                if(mysql_num_rows($result1)==0) {
                                    echo"<h3 style=\"text-align:center;\">该测试还没有学生参加!</h3>";
                                }
                                else {
                                    ?>
                    <table cellpadding="30" cellspacing="10" class="datatable">
                        <tr>
                            <th>学生姓名</th>
                            <th>邮箱</th>
                            <th>获得的分数</th>
                            <th>结果(%)</th>

                        </tr>
                                        <?php
                                        while($r1=mysql_fetch_array($result1)) {

                                            ?>
                        <tr>
                            <td><?php echo htmlspecialchars_decode($r1['stdname'],ENT_QUOTES); ?></td>
                            <td><?php echo htmlspecialchars_decode($r1['emailid'],ENT_QUOTES); ?></td>
                            <td><?php echo $r1['om']; ?></td>
                            <td><?php echo ($r1['om']/$r['maxmarks']*100)." %"; ?></td>


                        </tr>
                                        <?php
                                        
                                        }

                                    }
                                }
                                else {
                                    echo"<h3 style=\"text-align:center;\">出错了,请注销, 然后重试。</h3>";
                                }
                                ?>
                    </table>


                        <?php

                        }
                        else {

                        /************************** Step 3 - Case 2 *************************/
                        // Defualt Mode: Displays the Test Results.
                            $result=executeQuery("select t.testid,t.testname,DATE_FORMAT(t.testfrom,'%d %M %Y') as fromdate,DATE_FORMAT(t.testto,'%d %M %Y %H:%i:%S') as todate,sub.subname,(select count(stdid) from studenttest where testid=t.testid) as attemptedstudents from test as t, subject as sub where sub.subid=t.subid;");
                            if(mysql_num_rows($result)==0) {
                                echo "<h3 style=\"text-align:center;\">尚未测试...!</h3>";
                            }
                            else {
                                $i=0;

                                ?>
                    <table cellpadding="30" cellspacing="10" class="datatable">
                        <tr>
                            <th>测试名称</th>
                            <th>有效性</th>
                            <th>主题名称</th>
                            <th>测试学生</th>
                            <th>详细</th>
                        </tr>
            <?php
                                    while($r=mysql_fetch_array($result)) {
                                        $i=$i+1;
                                        if($i%2==0) {
                                            echo "<tr class=\"alt\">";
                                        }
                                        else { echo "<tr>";}
                                        echo "<td>".htmlspecialchars_decode($r['testname'],ENT_QUOTES)."</td><td>".$r['fromdate']." To ".$r['todate']." PM </td>"
                                            ."<td>".htmlspecialchars_decode($r['subname'],ENT_QUOTES)."</td><td>".$r['attemptedstudents']."</td>"
                                            ."<td class=\"tddata\"><a title=\"Details\" href=\"rsltmng.php?testid=".$r['testid']."\"><img src=\"../images/detail.png\" height=\"30\" width=\"40\" alt=\"Details\" /></a></td></tr>";
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
  </div>
        </div>
            <?php require '../footer.php' ?>
            

      </div>
  </body>
</html>

