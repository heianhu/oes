  <head>
        
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
        <link rel="stylesheet" href="../assets/css/main.css" />
        <!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
        <!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->



    </head>

        


         <header id="header">
                    <h1 id="logo"><a href="../index.php">网络测试系统</a></h1>

                     <?php
                        if (isset($_SESSION['admname'])) {?>

                    <nav id="nav">
                        <ul>
                            <li><a id="exam" href="submng.php" target="_self" class="current">管理科目</a></li>
                            <li><a id="upgrade" href="testmng.php" target="_self">管理测试</a></li>
                            <li><a id="question" href="rsltmng.php" target="_self">管理测试结果</a> </li>
                            <!-- <li><a href="admwelcome.php" class="button special">登出</a></li> -->
                            <li>
                            <form name="admwelcome" action="admwelcome.php" method="post" >
                   
                        <?php if(isset($_SESSION['admname'])){ ?>
                        <li><input type="submit" class="button special small" value="登出" name="logout"  title="Log Out"/></li>
                        <?php } ?>
                    
                </form>
                </li>
 <!-- <li><input type="submit" value="登出" name="logout" class="subbtn" title="Log Out"/></li> -->

                        </ul>
                    </nav>
                </header>
                <?php
                }?>

  