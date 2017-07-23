<?php
error_reporting(0);
session_start();
include_once 'oesdb.php';
if (!isset($_SESSION['stdname'])) {
    $_GLOBALS['message'] = "会话超时,请重新登录.";
} else if (isset($_SESSION['starttime'])) {
    header('Location: testconducter.php');
} else if (isset($_REQUEST['logout'])) {
    //Log out and redirect login page
    unset($_SESSION['stdname']);
    header('Location: index.php');
} else if (isset($_REQUEST['dashboard'])) {
    //redirect to dashboard
    //
    header('Location: stdwelcome.php');
} else if (isset($_REQUEST['starttest'])) {
    //Prepare the parameters needed for Test Conducter and redirect to test conducter
    if (!empty($_REQUEST['tc'])) {
        $result = executeQuery("select DECODE(testcode,'oespass') as tcode from test where testid=" . $_SESSION['testid'] . ";");

        if ($r = mysql_fetch_array($result)) {
            if (strcmp(htmlspecialchars_decode($r['tcode'], ENT_QUOTES), htmlspecialchars($_REQUEST['tc'], ENT_QUOTES)) != 0) {
                $display = true;
                $_GLOBALS['message'] = "您输入了无效的测试代码,请再次尝试.";
            } else {
                //now prepare parameters for Test Conducter and redirect to it.
                //first step: Insert the questions into table

                $result = executeQuery("select * from question where testid=" . $_SESSION['testid'] . " order by qnid;");
                if (mysql_num_rows($result) == 0) {
                    $_GLOBALS['message'] = "无法选择测试题,请过一段时间重试.";
                } else {
                  //  executeQuery("COMMIT");
                    $error = false;
                //    executeQuery("delimiter |");
                 /*   if (!executeQuery("create event " . $_SESSION['stdname'] . time() . "
ON SCHEDULE AT (select endtime from studenttest where stdid=" . $_SESSION['stdid'] . " and testid=" . $_SESSION['testid'] . ") + INTERVAL (select duration from test where testid=" . $_SESSION['testid'] . ") MINUTE
DO update studenttest set correctlyanswered=(select count(*) from studentquestion as sq,question as q where sq.qnid=q.qnid and sq.testid=q.testid and sq.answered='answered' and sq.stdanswer=q.correctanswer and sq.stdid=" . $_SESSION['stdid'] . " and sq.testid=" . $_SESSION['testid'] . "),status='over' where stdid=" . $_SESSION['stdid'] . " and testid=" . $_SESSION['testid'] . "|"))
                        $_GLOBALS['message'] = "error" . mysql_error();
                    executeQuery("delimiter ;");*/
                    if (!executeQuery("insert into studenttest values(" . $_SESSION['stdid'] . "," . $_SESSION['testid'] . ",(select CURRENT_TIMESTAMP),date_add((select CURRENT_TIMESTAMP),INTERVAL (select duration from test where testid=" . $_SESSION['testid'] . ") MINUTE),0,'inprogress')"))
                        $_GLOBALS['message'] = "error" . mysql_error();
                    else {
                        while ($r = mysql_fetch_array($result)) {
                            if (!executeQuery("insert into studentquestion values(" . $_SESSION['stdid'] . "," . $_SESSION['testid'] . "," . $r['qnid'] . ",'unanswered',NULL)")) {
                                $_GLOBALS['message'] = "获取信息失败,请再试一次.";
                                $error = true;
                            }
                        }
                        if ($error == true) {
                      //      executeQuery("rollback;");
                        } else {
                            $result = executeQuery("select totalquestions,duration from test where testid=" . $_SESSION['testid'] . ";");
                            $r = mysql_fetch_array($result);
                            $_SESSION['tqn'] = htmlspecialchars_decode($r['totalquestions'], ENT_QUOTES);
                            $_SESSION['duration'] = htmlspecialchars_decode($r['duration'], ENT_QUOTES);
                            $result = executeQuery("select DATE_FORMAT(starttime,'%Y-%m-%d %H:%i:%s') as startt,DATE_FORMAT(endtime,'%Y-%m-%d %H:%i:%s') as endt from studenttest where testid=" . $_SESSION['testid'] . " and stdid=" . $_SESSION['stdid'] . ";");
                            $r = mysql_fetch_array($result);
                            $_SESSION['starttime'] = $r['startt'];
                            $_SESSION['endtime'] = $r['endt'];
                            $_SESSION['qn'] = 1;
                            header('Location: testconducter.php');
                        }
                    }
                }
            }
        } else {
            $display = true;
            $_GLOBALS['message'] = "您输入了无效的测试代码,请再次尝试.";
        }
    } else {
        $display = true;
        $_GLOBALS['message'] = "请先输入测试代码!";
    }
} else if (isset($_REQUEST['testcode'])) {
    //test code preparation
    if ($r = mysql_fetch_array($result = executeQuery("select testid from test where testname='" . htmlspecialchars($_REQUEST['testcode'], ENT_QUOTES) . "';"))) {
        $_SESSION['testname'] = $_REQUEST['testcode'];
        $_SESSION['testid'] = $r['testid'];
    }
} else if (isset($_REQUEST['savem'])) {
    //updating the modified values
    if (empty($_REQUEST['cname']) || empty($_REQUEST['password']) || empty($_REQUEST['email'])) {
        $_GLOBALS['message'] = "一些必填字段为空,请返回检查.";
    } else {
        $query = "update student set stdname='" . htmlspecialchars($_REQUEST['cname'], ENT_QUOTES) . "', stdpassword=ENCODE('" . htmlspecialchars($_REQUEST['password'], ENT_QUOTES) . "','oespass'),emailid='" . htmlspecialchars($_REQUEST['email'], ENT_QUOTES) . "',contactno='" . htmlspecialchars($_REQUEST['contactno'], ENT_QUOTES) . "',address='" . htmlspecialchars($_REQUEST['address'], ENT_QUOTES) . "',city='" . htmlspecialchars($_REQUEST['city'], ENT_QUOTES) . "',pincode='" . htmlspecialchars($_REQUEST['pin'], ENT_QUOTES) . "' where stdid='" . $_REQUEST['student'] . "';";
        if (!@executeQuery($query))
            $_GLOBALS['message'] = mysql_error();
        else
            $_GLOBALS['message'] = "您的个人资料已成功更新.";
    }
    closedb();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
    <head>
        <title>提供的测试</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <meta http-equiv="CACHE-CONTROL" content="NO-CACHE"/>
        <meta http-equiv="PRAGMA" content="NO-CACHE"/>
        <meta name="ROBOTS" content="NONE"/>
        <script type="text/javascript" src="validate.js" ></script>
    </head>
    <body >
        <?php
        if ($_GLOBALS['message']) {
            echo "<script type='text/javascript'>alert('".$_GLOBALS['message']."');</script>";
        }
        ?>
        <?php require 'header.php' ?>
        

        <!-- Main -->
        <div id="main" class="wrapper style1">
          <div class="container">
            <header class="major">
              <h2>参加新测试</h2>       
            </header>
         

            <form id="stdtest" action="stdtest.php" method="post">
                <div class="menubar">
                    <ul id="menu">
                        <?php
                        if (isset($_SESSION['stdname'])) {
                            // Navigations
                        ?>
                           


                        </ul>
                    </div>
                    <div class="page">
                    <?php
                            if (isset($_REQUEST['testcode'])) {
                                echo "<div class=\"pmsg\" style=\"text-align:center;\">你还记得 " . $_SESSION['testname'] . " 的测试密码么? </div>";
                            } else {
                                echo "<div class=\"pmsg\" style=\"text-align:center;\">提供的测试</div>";
                            }
                    ?>
                    <?php
                            if (isset($_REQUEST['testcode']) || $display == true) {
                    ?>
                                <table cellpadding="30" cellspacing="10">
                                    <tr >
                                        <td style=" vertical-align:middle;">输入测试密码</td>
                                        <td style=" vertical-align:middle;"><input type="text" tabindex="1" name="tc" value="" size="16" /></td>
                                        <td><b>注意:</b><br/>一旦点击开始<br/>则会开始计时</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <input type="submit" tabindex="3" value="开始测试" name="starttest" class="subbtn" />
                                        </td>
                                    </tr>
                                </table>


                    <?php
                            } else {
                                $result = executeQuery("select t.*,s.subname from test as t, subject as s where s.subid=t.subid and CURRENT_TIMESTAMP<t.testto and t.totalquestions=(select count(*) from question where testid=t.testid) and NOT EXISTS(select stdid,testid from studenttest where testid=t.testid and stdid=" . $_SESSION['stdid'] . ");");
                                if (mysql_num_rows($result) == 0) {
                                    echo"<h3 style=\"text-align:center;\">没有可用的测试.</h3>";
                                } else {
                                    //editing components
                    ?>
                                    <table cellpadding="30" cellspacing="10" class="datatable">
                                        <tr>
                                            <th>测试名称</th>
                                            <th>测试描述</th>
                                            <th>科目名</th>
                                            <th>测试时间(分钟)</th>
                                            <th>总题数</th>
                                            <th>开始测试</th>
                                        </tr>
                        <?php
                                    while ($r = mysql_fetch_array($result)) {
                                        $i = $i + 1;
                                        if ($i % 2 == 0) {
                                            echo "<tr class=\"alt\">";
                                        } else {
                                            echo "<tr>";
                                        }
                                        echo "<td>" . htmlspecialchars_decode($r['testname'], ENT_QUOTES) . "</td><td>" . htmlspecialchars_decode($r['testdesc'], ENT_QUOTES) . "</td><td>" . htmlspecialchars_decode($r['subname'], ENT_QUOTES)
                                        . "</td><td>" . htmlspecialchars_decode($r['duration'], ENT_QUOTES) . "</td><td>" . htmlspecialchars_decode($r['totalquestions'], ENT_QUOTES) . "</td>"
                                        . "<td class=\"tddata\"><a title=\"Start Test\" href=\"stdtest.php?testcode=" . htmlspecialchars_decode($r['testname'], ENT_QUOTES) . "\"><img src=\"images/starttest.png\" height=\"30\" width=\"40\" alt=\"Start Test\" /></a></td></tr>";
                                    }
                        ?>
                                </table>
                    <?php
                                }
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

