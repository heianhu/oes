 
                       

   <head>
       
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
        <link rel="stylesheet" href="assets/css/main.css" />
        <!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
        <!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->

    </head>

       <!--  <img  src="images/logo.gif" width="50" height="50"><span style="margin-left:8px;"></span></div>              
        <a id="exam" href="stdtest.php" target="_self" class="current">参加新测试</a> 
        <a id="question" href="http://www.zcth.cn/question" target="_self">试题库</a>
        <a id="user" href="resumetest.php" target="_self">恢复测试</a>
        <a id="upgrade" href="viewresult.php" target="_self">察看结果</a>  -->

        <header id="header">
                    <h1 id="logo"><a href="index.php">网络测试系统</a></h1>
                     <?php
                        if (isset($_SESSION['stdname'])) {
                        ?>
                    <nav id="nav">
                        <ul>
                            <li><a href="stdtest.php">参加新测试</a></li>
                            
                            <li><a href="resumetest.php">恢复测试</a></li>
                            <li><a href="viewresult.php">察看结果</a></li>
                            <li><a href="editprofile.php?edit=edit'}" class="button special">个人</a>
                                 <ul >
                                 <form name="admwelcome" action="stdwelcome.php" method="post">    
                        <?php if(isset($_SESSION['stdname'])){ ?>
                        <li><input type="submit" class=" special small fit" value="登出" name="logout"  title="Log Out"/></li>
                        <?php } ?>
                </form>
                
                            </ul>
                        </ul>
                        
                

                  <?php
                        }
                            
                        ?>
                            </nav>
                </header>
        <!-- <div style="float:right;"><a  id="imguser" href="editprofile.php?edit=edit'}" target="_self">个人</a> </div> -->
        
        
        
    
</div>

