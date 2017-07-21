<?php
error_reporting(0);
session_start();
include_once 'oesdb.php';
$final=false;
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
    //
     header('Location: stdwelcome.php');

    }else if(isset($_REQUEST['next']) || isset($_REQUEST['summary']) || isset($_REQUEST['viewsummary']))
    {
        //next question
        $answer='unanswered';
        if(time()<strtotime($_SESSION['endtime']))
        {
            if(isset($_REQUEST['markreview']))
            {
                $answer='review';
            }
            else if(isset($_REQUEST['answer']))
            {
                $answer='answered';
            }
            else
            {
                $answer='unanswered';
            }
            if(strcmp($answer,"unanswered")!=0)
            {
                if(strcmp($answer,"answered")==0)
                {
                    $query="update studentquestion set answered='answered',stdanswer='".htmlspecialchars($_REQUEST['answer'],ENT_QUOTES)."' where stdid=".$_SESSION['stdid']." and testid=".$_SESSION['testid']." and qnid=".$_SESSION['qn'].";";
                }
                else
                {
                    $query="update studentquestion set answered='review',stdanswer='".htmlspecialchars($_REQUEST['answer'],ENT_QUOTES)."' where stdid=".$_SESSION['stdid']." and testid=".$_SESSION['testid']." and qnid=".$_SESSION['qn'].";";
                }
                if(!executeQuery($query))
                {
                // to do
                $_GLOBALS['message']="您以前的答案没有更新,请再次回答.";
                }
                closedb();
            }
            if(isset($_REQUEST['viewsummary']))
            {
                 header('Location: summary.php');
            }
            if(isset($_REQUEST['summary']))
             {
                     //summary page
                     header('Location: summary.php');
             }
        }
        if((int)$_SESSION['qn']<(int)$_SESSION['tqn'])
        {
        $_SESSION['qn']=$_SESSION['qn']+1;
       
        }
        if((int)$_SESSION['qn']==(int)$_SESSION['tqn'])
        {
           $final=true;
        }

    }
    else if(isset($_REQUEST['previous']))
    {
    // Perform the changes for current question
        $answer='unanswered';
        if(time()<strtotime($_SESSION['endtime']))
        {
            if(isset($_REQUEST['markreview']))
            {
                $answer='review';
            }
            else if(isset($_REQUEST['answer']))
            {
                $answer='answered';
            }
            else
            {
                $answer='unanswered';
            }
            if(strcmp($answer,"unanswered")!=0)
            {
                if(strcmp($answer,"answered")==0)
                {
                    $query="update studentquestion set answered='answered',stdanswer='".htmlspecialchars($_REQUEST['answer'],ENT_QUOTES)."' where stdid=".$_SESSION['stdid']." and testid=".$_SESSION['testid']." and qnid=".$_SESSION['qn'].";";
                }
                else
                {
                    $query="update studentquestion set answered='review',stdanswer='".htmlspecialchars($_REQUEST['answer'],ENT_QUOTES)."' where stdid=".$_SESSION['stdid']." and testid=".$_SESSION['testid']." and qnid=".$_SESSION['qn'].";";
                }
                if(!executeQuery($query))
                {
                // to do
                $_GLOBALS['message']="您以前的答案没有更新,请再次回答.";
                }
                closedb();
            }
        }
        //previous question
        if((int)$_SESSION['qn']>1)
        {
            $_SESSION['qn']=$_SESSION['qn']-1;
        }

    }
    else if(isset($_REQUEST['fs']))
    {
        //Final Submission
        header('Location: testack.php');
    }
?>
<?php
header("Cache-Control: no-cache, must-revalidate");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html>
  <head>
    <title>测试</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta http-equiv="CACHE-CONTROL" content="NO-CACHE"/>
    <meta http-equiv="PRAGMA" content="NO-CACHE"/>
    <meta name="ROBOTS" content="NONE"/>
    <script type="text/javascript" src="validate.js" ></script>
    <script type="text/javascript" src="cdtimer.js" ></script>
    <script type="text/javascript" >
    <!--
        <?php
                $elapsed=time()-strtotime($_SESSION['starttime']);
                if(((int)$elapsed/60)<(int)$_SESSION['duration'])
                {
                    $result=executeQuery("select TIME_FORMAT(TIMEDIFF(endtime,CURRENT_TIMESTAMP),'%H') as hour,TIME_FORMAT(TIMEDIFF(endtime,CURRENT_TIMESTAMP),'%i') as min,TIME_FORMAT(TIMEDIFF(endtime,CURRENT_TIMESTAMP),'%s') as sec from studenttest where stdid=".$_SESSION['stdid']." and testid=".$_SESSION['testid'].";");
                    if($rslt=mysql_fetch_array($result))
                    {
                     echo "var hour=".$rslt['hour'].";";
                     echo "var min=".$rslt['min'].";";
                     echo "var sec=".$rslt['sec'].";";
                    }
                    else
                    {
                        $_GLOBALS['message']="请再次尝试.";
                    }
                    closedb();
                }
                else
                {
                    echo "var sec=01;var min=00;var hour=00;";
                }
        ?>
        
    -->
    </script>

    </head>
  <body >
      <noscript><h2>您必须使用支持Javascript的浏览器才能使用正确的功能</h2></noscript>
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
              <h2>参加新测试</h2>       
            </header>

           <form id="testconducter" action="testconducter.php" method="post">
          
      <div class="page">
          <?php
         
          if(isset($_SESSION['stdname']))
          {
                $result=executeQuery("select stdanswer,answered from studentquestion where stdid=".$_SESSION['stdid']." and testid=".$_SESSION['testid']." and qnid=".$_SESSION['qn'].";");
                $r1=mysql_fetch_array($result);
                $result=executeQuery("select * from question where testid=".$_SESSION['testid']." and qnid=".$_SESSION['qn'].";");
                $r=mysql_fetch_array($result);
          ?>
          <div class="tc">

              <table  >
                  <tr>
                      <th ></span></h3></th>
                      <th >问题号: <?php echo $_SESSION['qn']; ?> </h4></th>
                      <th ><input type="checkbox" name="markreview" id="markreview" value="mark"> </input><label for="markreview">标记以供审阅</label></h4></th>
                  </tr>
              </table>
             <textarea cols="100" rows="8" name="question" readonly ><?php echo htmlspecialchars_decode($r['question'],ENT_QUOTES); ?></textarea>
              <table border="0" width="100%" class="ntab">
                  <tr><td>&nbsp;</td></tr>
                  <tr><td >1. <input type="radio" name="answer" id="answera" value="optiona" </input><label for="answera"><?php if((strcmp(htmlspecialchars_decode($r1['answered'],ENT_QUOTES),"review")==0 ||strcmp(htmlspecialchars_decode($r1['answered'],ENT_QUOTES),"answered")==0)&& strcmp(htmlspecialchars_decode($r1['stdanswer'],ENT_QUOTES),"optiona")==0 ){echo "已标记";} ?><?php echo htmlspecialchars_decode($r['optiona'],ENT_QUOTES); ?></label></td></tr>
                  <tr><td >2. <input type="radio" name="answer" id="answerb" value="optionb" </input><label for="answerb"><?php if((strcmp(htmlspecialchars_decode($r1['answered'],ENT_QUOTES),"review")==0 ||strcmp(htmlspecialchars_decode($r1['answered'],ENT_QUOTES),"answered")==0)&& strcmp(htmlspecialchars_decode($r1['stdanswer'],ENT_QUOTES),"optionb")==0 ){echo "已标记";} ?> <?php echo htmlspecialchars_decode($r['optionb'],ENT_QUOTES); ?></label></td></tr>
                  <tr><td >3. <input type="radio" name="answer" id="answerc" value="optionc" </input><label for="answerc"><?php if((strcmp(htmlspecialchars_decode($r1['answered'],ENT_QUOTES),"review")==0 ||strcmp(htmlspecialchars_decode($r1['answered'],ENT_QUOTES),"answered")==0)&& strcmp(htmlspecialchars_decode($r1['stdanswer'],ENT_QUOTES),"optionc")==0 ){echo "已标记";} ?> <?php echo htmlspecialchars_decode($r['optionc'],ENT_QUOTES); ?></label></td></tr>
                  <tr><td >4. <input type="radio" name="answer" id="answerd" value="optiond"</input><label for="answerd"> <?php if((strcmp(htmlspecialchars_decode($r1['answered'],ENT_QUOTES),"review")==0 ||strcmp(htmlspecialchars_decode($r1['answered'],ENT_QUOTES),"answered")==0)&& strcmp(htmlspecialchars_decode($r1['stdanswer'],ENT_QUOTES),"optiond")==0 ){echo "已标记";} ?> <?php echo htmlspecialchars_decode($r['optiond'],ENT_QUOTES); ?></label></td></tr>
                  <tr><td>&nbsp;</td></tr>
                  <tr>
                      <th ><h4><input type="submit" name="<?php if($final==true){ echo "viewsummary" ;}else{ echo "next";} ?>" value="<?php if($final==true){ echo "查看总结" ;}else{ echo "下一个";} ?>" class="subbtn"/></h4></th>
                      <th ><h4><input type="submit" name="previous" value="上一个" class="subbtn"/></h4></th>
                      <th ><h4><input type="submit" name="summary" value="总结" class="subbtn" /></h4></th>
                  </tr>
                  
              </table>
              

          </div>
          <?php
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

