 <div id="topControl" class="top topbannerbg shadow" style="position:fixed;top:0">
    <div style="width:100%;margin:0 auto;">
      
                        <?php
                        if (isset($_SESSION['admname'])) {?>
   <div style="float:left;color:#fff;margin-left:10px;margin-right:40px; margin-top:5px;font-size:20px;font-weight:bold;" onclick="javascript:void(0)">
        <img  src="../images/logo.gif" width="50" height="50"><span style="margin-left:8px;"></span></div>              
        <a id="exam" href="submng.php" target="_self" class="current">管理科目</a> 
        <a id="question" href="rsltmng" target="_self">管理测试结果</a>
        <a id="user" href="testmng.php?forpq=true.php" target="_self">准备题目</a>
        <a id="upgrade" href="testmng.php" target="_self">管理测试</a> 

                        
        <div style="float:right;"><a  id="imguser" href="editprofile.php?edit=edit'}" target="_self">个人</a> </div>
        
         
                <?php
                }?>
        <a id="upgrade" href="index.php" target="_self">&nbsp;Online Examination System</a> 

        
    </div>
</div>
