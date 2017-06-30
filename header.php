 <div id="topControl" class="top topbannerbg shadow" style="position:fixed;top:0">
    <div style="width:100%;margin:0 auto;">
      
      
                        <?php
                        if (isset($_SESSION['stdname'])) {
                        ?>
   <div style="float:left;color:#fff;margin-left:10px;margin-right:40px; margin-top:5px;font-size:20px;font-weight:bold;" onclick="javascript:void(0)">
        <img  src="images/logo.gif" width="50" height="50"><span style="margin-left:8px;"></span></div>              
        <a id="exam" href="stdtest.php" target="_self" class="current">参加新测试</a> 
        <a id="question" href="http://www.zcth.cn/question" target="_self">试题库</a>
        <a id="user" href="resumetest.php" target="_self">恢复测试</a>
        <a id="upgrade" href="viewresult.php" target="_self">察看结果</a> 

                  <?php
                        }
                            
                        ?>
        <a id="upgrade" href="index.php" target="_self">&nbsp;Online Examination System</a> 
        <div style="float:right;"><a  id="imguser" href="editprofile.php?edit=edit'}" target="_self">个人</a> </div>
        
         

        
    </div>
</div>

