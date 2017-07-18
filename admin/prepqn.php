<?php
/* Procedure
 * ********************************************

 * ----------- *
 * PHP Section *
 * ----------- *

  Step 1: Perform Session Validation.

  Step 2: Event to Process...
  Case 1 : Logout - perform session cleanup.
  Case 2 : Manage Tests - redirect to Test Management Section.
  Case 3 : Delete - Delete the selected Question/s from Test.
  Case 4 : Edit - Update the Question.
  Case 5 : Add - Add new Question to the Test.

 * ------------ *
 * HTML Section *
 * ------------ *

  Step 3: Display the HTML Components for...
  Case 1: Add - Form to receive new Question.
  Case 2: Edit - Form to edit Existing Question.
  Case 3: Default Mode - Displays the Information of Existing Questions, If any.
 * ********************************************
 */

error_reporting(0);
session_start();
include_once '../oesdb.php';
/* * ************************ Step 1 ************************ */
if (!isset($_SESSION['admname']) || !isset($_SESSION['testqn'])) {
    $_GLOBALS['message'] = "会话超时.点击这里<a href=\"index.php\">重新登录</a>";
} else if (isset($_REQUEST['logout'])) {
    /*     * ************************ Step 2 - Case 1 ************************ */
    //Log out and redirect login page
    unset($_SESSION['admname']);
    header('Location: index.php');
} else if (isset($_REQUEST['managetests'])) {
    /*     * ************************ Step 2 - Case 2 ************************ */
    //redirect to Manage Tests Section

    header('Location: testmng.php');
} else if (isset($_REQUEST['delete'])) {
    /*     * ************************ Step 2 - Case 3 ************************ */
    //deleting the selected Questions
    unset($_REQUEST['delete']);
    $hasvar = false;
    $count = 1;
    foreach ($_REQUEST as $variable) {
        if (is_numeric($variable)) { //it is because, some session values are also passed with request
            $hasvar = true;

            if (!@executeQuery("delete from question where testid=" . $_SESSION['testqn'] . " and qnid=" . htmlspecialchars($variable)))
                $_GLOBALS['message'] = mysql_error();
        }
    }
    //reordering questions

    $result = executeQuery("select qnid from question where testid=" . $_SESSION['testqn'] . " order by qnid;");
    while ($r = mysql_fetch_array($result))
        if (!@executeQuery("update question set qnid=" . ($count++) . " where testid=" . $_SESSION['testqn'] . " and qnid=" . $r['qnid'] . ";"))
            $_GLOBALS['message'] = mysql_error();

    //
    if (!isset($_GLOBALS['message']) && $hasvar == true)
        $_GLOBALS['message'] = "已成功删除所选问题";
    else if (!$hasvar) {
        $_GLOBALS['message'] = "首先选择要删除的问题。";
    }
} else if (isset($_REQUEST['savem'])) {
    /*     * ************************ Step 2 - Case 4 ************************ */
    //updating the modified values
    // $_GLOBALS['message']=$newstd;
    if (strcmp($_REQUEST['correctans'], "<Choose the Correct Answer>") == 0 || empty($_REQUEST['question']) || empty($_REQUEST['optiona']) || empty($_REQUEST['optionb']) || empty($_REQUEST['optionc']) || empty($_REQUEST['optiond']) || empty($_REQUEST['marks'])) {
        $_GLOBALS['message'] = "某些必填字段为空";
    } else if (strcasecmp($_REQUEST['optiona'], $_REQUEST['optionb']) == 0 || strcasecmp($_REQUEST['optiona'], $_REQUEST['optionc']) == 0 || strcasecmp($_REQUEST['optiona'], $_REQUEST['optiond']) == 0 || strcasecmp($_REQUEST['optionb'], $_REQUEST['optionc']) == 0 || strcasecmp($_REQUEST['optionb'], $_REQUEST['optiond']) == 0 || strcasecmp($_REQUEST['optionc'], $_REQUEST['optiond']) == 0) {
        $_GLOBALS['message'] = "两个或多个选项表示相同的答案。再次验证";
    } else {
        $query = "update question set question='" . htmlspecialchars($_REQUEST['question'],ENT_QUOTES) . "',optiona='" . htmlspecialchars($_REQUEST['optiona'],ENT_QUOTES) . "',optionb='" . htmlspecialchars($_REQUEST['optionb'],ENT_QUOTES) . "',optionc='" . htmlspecialchars($_REQUEST['optionc'],ENT_QUOTES) . "',optiond='" . htmlspecialchars($_REQUEST['optiond'],ENT_QUOTES) . "',correctanswer='" . htmlspecialchars($_REQUEST['correctans'],ENT_QUOTES) . "',marks=" . htmlspecialchars($_REQUEST['marks'],ENT_QUOTES) . " where testid=" . $_SESSION['testqn'] . " and qnid=" . $_REQUEST['qnid'] . " ;";
        if (!@executeQuery($query))
            $_GLOBALS['message'] = mysql_error();
        else
            $_GLOBALS['message'] = "问题已成功更新。";
    }
    closedb();
}
else if (isset($_REQUEST['savea'])) {
    /*     * ************************ Step 2 - Case 5 ************************ */
    //Add the new Question
    $cancel = false;
    $result = executeQuery("select max(qnid) as qn from question where testid=" . $_SESSION['testqn'] . ";");
    $r = mysql_fetch_array($result);
    if (is_null($r['qn']))
        $newstd = 1;
    else
        $newstd=$r['qn'] + 1;

    $result = executeQuery("select count(*) as q from question where testid=" . $_SESSION['testqn'] . ";");
    $r2 = mysql_fetch_array($result);

    $result = executeQuery("select totalquestions from test where testid=" . $_SESSION['testqn'] . ";");
    $r1 = mysql_fetch_array($result);

    if (!is_null($r2['q']) && (int) htmlspecialchars_decode($r1['totalquestions'],ENT_QUOTES) == (int) $r2['q']) {
        $cancel = true;
        $_GLOBALS['message'] = "您已经创建了此测试的所有问题.<br /><b>帮助:</b> 如果您仍想添加一些问题, 请编辑测试设置 (选项: 总计问题)。";
    }
    else
        $cancel=false;

    $result = executeQuery("select * from question where testid=" . $_SESSION['testqn'] . " and question='" . htmlspecialchars($_REQUEST['question'],ENT_QUOTES) . "';");
    if (!$cancel && $r1 = mysql_fetch_array($result)) {
        $cancel = true;
        $_GLOBALS['message'] = "对不起, 您试图为相同的测试输入相同的问题";
    } else if (!$cancel)
        $cancel = false;
    // $_GLOBALS['message']=$newstd;
    if (strcmp($_REQUEST['correctans'], "<Choose the Correct Answer>") == 0 || empty($_REQUEST['question']) || empty($_REQUEST['optiona']) || empty($_REQUEST['optionb']) || empty($_REQUEST['optionc']) || empty($_REQUEST['optiond']) || empty($_REQUEST['marks'])) {
        $_GLOBALS['message'] = "某些必填字段为空";
    } else if (strcasecmp($_REQUEST['optiona'], $_REQUEST['optionb']) == 0 || strcasecmp($_REQUEST['optiona'], $_REQUEST['optionc']) == 0 || strcasecmp($_REQUEST['optiona'], $_REQUEST['optiond']) == 0 || strcasecmp($_REQUEST['optionb'], $_REQUEST['optionc']) == 0 || strcasecmp($_REQUEST['optionb'], $_REQUEST['optiond']) == 0 || strcasecmp($_REQUEST['optionc'], $_REQUEST['optiond']) == 0) {
        $_GLOBALS['message'] = "两个或多个选项表示相同的答案。再次验证";
    } else if (!$cancel) {
        $query = "insert into question values(" . $_SESSION['testqn'] . ",$newstd,'" . htmlspecialchars($_REQUEST['question'],ENT_QUOTES) . "','" . htmlspecialchars($_REQUEST['optiona'],ENT_QUOTES) . "','" . htmlspecialchars($_REQUEST['optionb'],ENT_QUOTES) . "','" . htmlspecialchars($_REQUEST['optionc'],ENT_QUOTES) . "','" . htmlspecialchars($_REQUEST['optiond'],ENT_QUOTES) . "','" . htmlspecialchars($_REQUEST['correctans'],ENT_QUOTES) . "'," . htmlspecialchars($_REQUEST['marks'],ENT_QUOTES) . ")";
        if (!@executeQuery($query))
            $_GLOBALS['message'] = mysql_error();
        else
            $_GLOBALS['message'] = "已成功创建新问题。";
    }
    closedb();
}
?>
<html>
    <head>
        <title>OES-管理问题</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <link rel="stylesheet" type="text/css" href="../oes.css"/>
        <script type="text/javascript" src="../tiny_mce/tiny_mce.js"></script>
        <script type="text/javascript" src="../validate.js" ></script>

        <!--TinyMCE Integration-->
      <!--  <script type="text/javascript">
            tinyMCE.init({
		// General options
		mode : "exact",
		elements : "question",
		theme : "advanced",
		skin : "o2k7",
		skin_variant : "silver",
		plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,inlinepopups,autosave",

		// Theme options
		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak,restoredraft",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		// Example content CSS (should be your site CSS)
		content_css : "../oes.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});

        </script>-->
    </head>
    <body>
<?php
if ($_GLOBALS['message']) {
    echo "<script type='text/javascript'>alert('".$_GLOBALS['message']."');</script>";
}
?>
        
        <?php require 'admheader.php' ?>
        
        <!-- Main -->
        <div id="main" class="wrapper style1">
          <div class="container">
            <header class="major">
              <h2>题目管理</h2>       
            </header>
          </div>
        </div>

            <form name="prepqn" action="prepqn.php" method="post">
                <div class="menubar">


                    <ul id="menu" class="actions ">
<?php
if (isset($_SESSION['admname']) && isset($_SESSION['testqn'])) {
    // Navigations
?>
                        <li><input type="submit" value="BACK" name="managetests" class="subbtn" title="Manage Tests"/></li>

        <?php
        //navigation for Add option
        if (isset($_REQUEST['add'])) {
        ?>
                            <li><input type="submit" value="取消" name="cancel" class="subbtn" title="Cancel"/></li>
                            <li><input type="submit" value="保存" name="savea" class="subbtn" onclick="validateqnform('prepqn')" title="Save the Changes"/></li>

<?php
        } else if (isset($_REQUEST['edit'])) { //navigation for Edit option
?>
                            <li><input type="submit" value="取消" name="cancel" class="subbtn" title="Cancel"/></li>
                            <li><input type="submit" value="保存" name="savem" class="subbtn" onclick="validateqnform('prepqn')" title="Save the changes"/></li>

                        <?php
                    } else {  //navigation for Default
                        ?>
                        <li><input type="submit" value="添加" name="add" class="button" title="Add"/></li>
                        
                        <li><input type="submit" value="删除" name="delete" class="button" title="Delete"/></li>
                        
                        <?php }
                } ?>
                    </ul>

                </div>

                <div class="page">
                        <?php
                        $result = executeQuery("select count(*) as q from question where testid=" . $_SESSION['testqn'] . ";");
                        $r1 = mysql_fetch_array($result);

                        $result = executeQuery("select totalquestions from test where testid=" . $_SESSION['testqn'] . ";");
                        $r2 = mysql_fetch_array($result);
                        if ((int) $r1['q'] == (int) htmlspecialchars_decode($r2['totalquestions'],ENT_QUOTES))
                            echo "<div class=\"pmsg\"> 测试名称: " . $_SESSION['testname'] . "<br/>状态: 所有问题都是为此测试创建的。</div>";
                        else
                            echo "<div class=\"pmsg\"> 测试名称: " . $_SESSION['testname'] . "<br/>状态: 仍然需要创建" . (htmlspecialchars_decode($r2['totalquestions'],ENT_QUOTES) - $r1['q']) . " 问题. 在这之后, 应试者就可以参加考试了。</div>";
                        ?>
                        <?php
                        if (isset($_SESSION['admname']) && isset($_SESSION['testqn'])) {

                            if (isset($_REQUEST['add'])) {
                                /*                                 * ************************ Step 3 - Case 1 ************************ */
                                //Form for the new Question
                        ?>
                                <table cellpadding="20" cellspacing="20" style="text-align:left;" >
                                    <tr>
                                        <td>问题</td>
                                        <td><textarea name="question" cols="40" rows="3"  ></textarea></td>
                                    </tr>
                                    <tr>
                                        <td>选项 A</td>
                                        <td><input type="text" name="optiona" value="" size="30"  /></td>
                                    </tr>
                                    <tr>
                                        <td>选项 B</td>
                                        <td><input type="text" name="optionb" value="" size="30"  /></td>
                                    </tr>

                                    <tr>
                                        <td>选项 C</td>
                                        <td><input type="text" name="optionc" value="" size="30"  /></td>
                                    </tr>
                                    <tr>
                                        <td>选项 D</td>
                                        <td><input type="text" name="optiond" value="" size="30"  /></td>
                                    </tr>
                                    <tr>
                                        <td>正确答案</td>
                                        <td>
                                            <select name="correctans">
                                                <option value="<Choose the Correct Answer>" selected>&lt;选择正确答案&gt;</option>
                                                <option value="optiona">选项 A</option>
                                                <option value="optionb">选项 B</option>
                                                <option value="optionc">选项 C</option>
                                                <option value="optiond">选项 D</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>分数</td>
                                        <td><input type="text" name="marks" value="1" size="30" onkeyup="isnum(this)" /></td>

                                    </tr>

                                </table>

<?php
                            } else if (isset($_REQUEST['edit'])) {
                                /*                                 * ************************ Step 3 - Case 2 ************************ */
                                // To allow Editing Existing Question.
                                $result = executeQuery("select * from question where testid=" . $_SESSION['testqn'] . " and qnid=" . $_REQUEST['edit'] . ";");
                                if (mysql_num_rows($result) == 0) {
                                    header('Location: prepqn.php');
                                } else if ($r = mysql_fetch_array($result)) {


                                    //editing components
?>
                                    <table cellpadding="20" cellspacing="20" style="text-align:left;margin-left:15em;" >
                                        <tr>
                                            <td>问题<input type="hidden" name="qnid" value="<?php echo $r['qnid']; ?>" /></td>
                                            <td><textarea name="question" cols="40" rows="3"  ><?php echo htmlspecialchars_decode($r['question'],ENT_QUOTES); ?></textarea></td>
                                        </tr>
                                        <tr>
                                            <td>选项 A</td>
                                            <td><input type="text" name="optiona" value="<?php echo htmlspecialchars_decode($r['optiona'],ENT_QUOTES); ?>" size="30"  /></td>
                                        </tr>
                                        <tr>
                                            <td>选项 B</td>
                                            <td><input type="text" name="optionb" value="<?php echo htmlspecialchars_decode($r['optionb'],ENT_QUOTES); ?>" size="30"  /></td>
                                        </tr>

                                        <tr>
                                            <td>选项 C</td>
                                            <td><input type="text" name="optionc" value="<?php echo htmlspecialchars_decode($r['optionc'],ENT_QUOTES); ?>" size="30"  /></td>
                                        </tr>
                                        <tr>
                                            <td>选项 D</td>
                                            <td><input type="text" name="optiond" value="<?php echo htmlspecialchars_decode($r['optiond'],ENT_QUOTES); ?>" size="30"  /></td>
                                        </tr>
                                        <tr>
                                            <td>正确答案</td>
                                            <td>
                                                <select name="correctans">
                                                    <option value="optiona" <?php if (strcmp(htmlspecialchars_decode($r['correctanswer'],ENT_QUOTES), "optiona") == 0)
                                        echo "selected"; ?>>选项 A</option>
                                                    <option value="optionb" <?php if (strcmp(htmlspecialchars_decode($r['correctanswer'],ENT_QUOTES), "optionb") == 0)
                                        echo "selected"; ?>>选项 B</option>
                                                    <option value="optionc" <?php if (strcmp(htmlspecialchars_decode($r['correctanswer'],ENT_QUOTES), "optionc") == 0)
                                        echo "selected"; ?>>选项 C</option>
                                                    <option value="optiond" <?php if (strcmp(htmlspecialchars_decode($r['correctanswer'],ENT_QUOTES), "optiond") == 0)
                                        echo "selected"; ?>>选项 D</option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>分数</td>
                                            <td><input type="text" name="marks" value="<?php echo htmlspecialchars_decode($r['marks'],ENT_QUOTES); ?>" size="30" onkeyup="isnum(this)" /></td>

                                        </tr>

                                    </table>
<?php
                                    closedb();
                                }
                            }

                            else {

                                /*                                 * ************************ Step 3 - Case 3 ************************ */
                                // Defualt Mode: Displays the Existing Question/s, If any.
                                $result = executeQuery("select * from question where testid=" . $_SESSION['testqn'] . " order by qnid;");
                                if (mysql_num_rows($result) == 0) {
                                    echo "<h3 style=\"color:#0000cc;text-align:center;\">No Questions Yet..!</h3>";
                                } else {
                                    $i = 0;
?>
                                    <table cellpadding="30" cellspacing="10" class="datatable">
                                        <tr>
                                            <th>&nbsp;</th>
                                            <th>题目号</th>
                                            <th>题目</th>
                                            <th>正确答案</th>
                                            <th>分数</th>
                                            <th>编辑</th>
                                        </tr>
                    <?php
                                    while ($r = mysql_fetch_array($result)) {
                                        $i = $i + 1;
                                        if ($i % 2 == 0)
                                            echo "<tr class=\"alt\">";
                                        else
                                            echo "<tr>";
                                        echo "<td style=\"text-align:center;\"><input type=\"checkbox\" name=\"d$i\" id=\"d$i\" value=\"" . $r['qnid'] . "\" /><label for=\"d$i\"></label></td><td> " . $i
                                        . "</td><td>" . htmlspecialchars_decode($r['question'],ENT_QUOTES) . "</td><td>" . htmlspecialchars_decode($r[htmlspecialchars_decode($r['correctanswer'],ENT_QUOTES)],ENT_QUOTES) . "</td><td>" . htmlspecialchars_decode($r['marks'],ENT_QUOTES) . "</td>"
                                        . "<td class=\"tddata\"><a title=\"Edit " . $r['qnid'] . "\"href=\"prepqn.php?edit=" . $r['qnid'] . "\"><img src=\"../images/edit.png\" height=\"30\" width=\"40\" alt=\"Edit\" /></a>"
                                        . "</td></tr>";
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
            <div id="footer">
                <p style="font-size:70%;color:#ffffff;"> Developed By-<b>翻江倒海</b></p>
            </div>
        </div>
    </body>
</html>
