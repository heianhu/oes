


<?php


error_reporting(0);
session_start();
include_once 'oesdb.php';
if(!isset($_SESSION['stdname'])) {
    $_GLOBALS['message']="会话超时,请重新登录.";
}
else if(isset($_REQUEST['logout'])) {
    //Log out and redirect login page
        unset($_SESSION['stdname']);
        header('Location: index.php');

    }
    else if(isset($_REQUEST['back'])) {
        //redirect to View Result

            header('Location: viewresult.php');

        }
        else if(isset($_REQUEST['dashboard'])) {
        //redirect to dashboard

            header('Location: stdwelcome.php');

        }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
    <head>
        <title>查看结果</title>
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
            <?php    require 'header.php' ?>

       
          <div class="container">
            <header class="major">
              <h2>查看结果</h2>       
            </header>
          
            
            <form id="summary" action="viewresult.php" method="post">
                <div class="menubar">
                    <ul id="menu" class="actions  small">
                        <?php if(isset($_SESSION['stdname'])) {
                        // Navigations
                        if(isset($_REQUEST['details'])) {
              ?>
                       
                        <li><input type="submit" value="返回" name="back" class="subbtn" title="View Results"/></li>
                        <?php
                        }
                        else
                        {
                            ?>
                      
                       
                        <?php
                        }
                        ?>

                    </ul>


                </div>
                <div class="page">

                        <?php

                        if(isset($_REQUEST['details'])) {
                            $result=executeQuery("select s.stdname,t.testname,sub.subname,DATE_FORMAT(st.starttime,'%d %M %Y %H:%i:%s') as stime,TIMEDIFF(st.endtime,st.starttime) as dur,(select sum(marks) from question where testid=".$_REQUEST['details'].") as tm,IFNULL((select sum(q.marks) from studentquestion as sq, question as q where sq.testid=q.testid and sq.qnid=q.qnid and sq.answered='answered' and sq.stdanswer=q.correctanswer and sq.stdid=".$_SESSION['stdid']." and sq.testid=".$_REQUEST['details']."),0) as om from student as s,test as t, subject as sub,studenttest as st where s.stdid=st.stdid and st.testid=t.testid and t.subid=sub.subid and st.stdid=".$_SESSION['stdid']." and st.testid=".$_REQUEST['details'].";") ;
                            if(mysql_num_rows($result)!=0) {

                                $r=mysql_fetch_array($result);
                                ?>
                    <table cellpadding="20" cellspacing="30" border="0"  url(images/page.gif);text-align:left;line-height:20px;">
                        <tr>
                            <td colspan="2"><h3 style="button small fittext-align:center;">测试总结</h3></td>
                        </tr>
                        
                        <tr>
                            <td>学生名</td>
                            <td><?php echo htmlspecialchars_decode($r['stdname'],ENT_QUOTES); ?></td>
                        </tr>
                        <tr>
                            <td>测试</td>
                            <td><?php echo htmlspecialchars_decode($r['testname'],ENT_QUOTES); ?></td>
                        </tr>
                        <tr>
                            <td>科目</td>
                            <td><?php echo htmlspecialchars_decode($r['subname'],ENT_QUOTES); ?></td>
                        </tr>
                        <tr>
                            <td>日期</td>
                            <td><?php echo $r['stime']; ?></td>
                        </tr>
                        <tr>
                            <td>测试时间</td>
                            <td><?php echo $r['dur']; ?></td>
                        </tr>
                        <tr>
                            <td>满分</td>
                            <td><?php echo $r['tm']; ?></td>
                        </tr>
                        <tr>
                            <td>得分</td>
                            <td><?php echo $r['om']; ?></td>
                        </tr>
                        <tr>
                            <td>得分率</td>
                            <td><?php echo (($r['om']/$r['tm'])*100)." %"; ?></td>
                        </tr>
                        <tr>
                            <td colspan="2" ><hr style="border-width:0px;"/></td>
                        </tr>
                         <tr>
                            <td colspan="2"><h3 style="button small fittext-align:center;">测试详情</h3></td>
                        </tr>
                       
                    </table>
                                <?php

                                $result1=executeQuery("select q.qnid as questionid,q.question as quest,q.correctanswer as ca,sq.answered as status,sq.stdanswer as sa from studentquestion as sq,question as q where q.qnid=sq.qnid and sq.testid=q.testid and sq.testid=".$_REQUEST['details']." and sq.stdid=".$_SESSION['stdid']." order by q.qnid;" );

                                if(mysql_num_rows($result1)==0) {
                                    echo"<h3 style=\"button small fittext-align:center;\">1.Sorry because of some problems Individual questions Cannot be displayed.</h3>";
                                }
                                else {
                                    ?>
                    <table cellpadding="30" cellspacing="10" class="datatable">
                        <tr>
                            <th>问题号</th>
                            <th>题目</th>
                            <th>正确答案</th>
                            <th>你的选择</th>
                            <th>分数</th>
                            <th>&nbsp;</th>
                        </tr>
                                        <?php
                                        while($r1=mysql_fetch_array($result1)) {

                                        if(is_null($r1['sa']))
                                        $r1['sa']="question"; //any valid field of question
                                           $result2=executeQuery("select ".$r1['ca']." as corans,IF('".$r1['status']."'='answered',(select ".$r1['sa']." from question where qnid=".$r1['questionid']." and testid=".$_REQUEST['details']."),'unanswered') as stdans, IF('".$r1['status']."'='answered',IFNULL((select q.marks from question as q, studentquestion as sq where q.qnid=sq.qnid and q.testid=sq.testid and q.correctanswer=sq.stdanswer and sq.stdid=".$_SESSION['stdid']." and q.qnid=".$r1['questionid']." and q.testid=".$_REQUEST['details']."),0),0) as stdmarks from question where qnid=".$r1['questionid']." and testid=".$_REQUEST['details'].";");

                                            if($r2=mysql_fetch_array($result2)) {
                                                ?>
                        <tr>
                            <td><?php echo $r1['questionid']; ?></td>
                            <td><?php echo htmlspecialchars_decode($r1['quest'],ENT_QUOTES); ?></td>
                            <td><?php echo htmlspecialchars_decode($r2['corans'],ENT_QUOTES); ?></td>
                            <td><?php echo htmlspecialchars_decode($r2['stdans'],ENT_QUOTES); ?></td>
                            <td><?php echo $r2['stdmarks']; ?></td>
                                                    <?php
                                                    if($r2['stdmarks']==0) {
                                                        echo"<td class=\"tddata\"><img src=\"images/wrong.png\" title=\"Wrong Answer\" height=\"30\" width=\"40\" alt=\"Wrong Answer\" /></td>";
                                                    }
                                                    else {
                                                        echo"<td class=\"tddata\"><img src=\"images/correct.png\" title=\"Correct Answer\" height=\"30\" width=\"40\" alt=\"Correct Answer\" /></td>";
                                                    }
                                                    ?>
                        </tr>
                            <?php
                                                }
                                                else {
                                                    echo"<h3 style=\"button small fittext-align:center;\">Sorry because of some problems Individual questions Cannot be displayed.</h3>".mysql_error();
                                                }
                                            }

                                        }
                                    }
                                    else {
                                        echo"<h3 style=\"button small fittext-align:center;\">Something went wrong. Please logout and Try again.</h3>".mysql_error();
                                    }
                                    ?>
                    </table>
                                <?php

                        }
                        else {


                            $result=executeQuery("select st.*,t.testname,t.testdesc,DATE_FORMAT(st.starttime,'%d %M %Y %H:%i:%s') as startt from studenttest as st,test as t where t.testid=st.testid and st.stdid=".$_SESSION['stdid']." and st.status='over' order by st.testid;");
                            if(mysql_num_rows($result)==0) {
                                echo"<h3 style=\"button small fittext-align:center;\">我认为你还没有尝试任何考试. . !请您尝试后再试一次。</h3>";
                            }
                            else {
                            //editing components
                                ?>
                    <table cellpadding="30" cellspacing="10" class="datatable">
                        <tr>
                            <th>日期</th>
                            <th>测试名称</th>
                            <th>满分</th>
                            <th>得分</th>
                            <th>正确率</th>
                            <th>详情</th>
                        </tr>
            <?php
            while($r=mysql_fetch_array($result)) {
                                        $i=$i+1;
                                        $om=0;
                                        $tm=0;
                                        $result1=executeQuery("select sum(q.marks) as om from studentquestion as sq, question as q where sq.testid=q.testid and sq.qnid=q.qnid and sq.answered='answered' and sq.stdanswer=q.correctanswer and sq.stdid=".$_SESSION['stdid']." and sq.testid=".$r['testid']." order by sq.testid;");
                                        $r1=mysql_fetch_array($result1);
                                        $result2=executeQuery("select sum(marks) as tm from question where testid=".$r['testid'].";");
                                        $r2=mysql_fetch_array($result2);
                                        if($i%2==0) {
                                            echo "<tr class=\"alt\">";
                                        }
                                        else { echo "<tr>";}
                                        echo "<td>".$r['startt']."</td><td>".htmlspecialchars_decode($r['testname'],ENT_QUOTES)." : ".htmlspecialchars_decode($r['testdesc'],ENT_QUOTES)."</td>";
                                        if(is_null($r2['tm'])) {
                                            $tm=0;
                                            echo "<td>$tm</td>";
                                        }
                                        else {
                                            $tm=$r2['tm'];
                                            echo "<td>$tm</td>";
                                        }
                                        if(is_null($r1['om'])) {
                                            $om=0;
                                            echo "<td>$om</td>";
                                        }
                                        else {
                                            $om=$r1['om'];
                                            echo "<td>$om</td>";
                                        }
                                        if($tm==0) {
                                            echo "<td>0</td>";
                                        }
                                        else {
                                            echo "<td>".(($om/$tm)*100)." %</td>";
                                        }
                                        echo"<td class=\"tddata\"><a title=\"Details\" href=\"viewresult.php?details=".$r['testid']."\"><img src=\"images/detail.png\" height=\"30\" width=\"40\" alt=\"Details\" /></a></td></tr>";
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
            <?php require 'footer.php' ?>

  </body>
</html>

