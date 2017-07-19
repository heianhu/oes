<!-- Footer -->
				<footer id="footer">
					<ul class="icons">
					<div id=time ></div>

							
						
					</ul>
					<?php 
					 $content = file_get_contents('https://sslapi.hitokoto.cn/');
					print json_decode($content)->{'hitokoto'};
							?>	
					<ul class="copyright">

						<li>&copy; Untitled. All rights reserved.</li><li>Design: <a href="http://baidu.com">翻江倒海</a></li>
					</ul>
				</footer>



				<!-- Scripts -->
			<script src="../assets/js/jquery.min.js"></script>
			<script src="../assets/js/jquery.scrolly.min.js"></script>
			<script src="../assets/js/jquery.dropotron.min.js"></script>
			<script src="../assets/js/jquery.scrollex.min.js"></script>
			<script src="../assets/js/skel.min.js"></script>
			<script src="../assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="../assets/js/main.js"></script>
			<script>
				document.getElementById('time').innerHTML=new Date().toLocaleString()+' 星期'+'日一二三四五六'.charAt(new Date().getDay());setInterval("document.getElementById('time').innerHTML=new Date().toLocaleString()+' 星期'+'日一二三四五六'.charAt(new Date().getDay());",1000);
			</script> 