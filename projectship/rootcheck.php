<?php

	if( $_COOKIE['roles']=='普通用户' ){

		   echo "<script>
			 $(function(){
			 $('#leftmenu>ul>li:eq(1)').hide();//隐藏用户管理功能
			 $('#leftmenu>ul>li:eq(4)').hide();//隐藏冷链物流车管理功能
			});</script>";
	}else if($_COOKIE['roles']=="系统管理员"){
                
			   echo "<script>
			 $(function(){
			 $('#leftmenu>ul>li:eq(1)').show();//显示用户管理功能
			 $('#leftmenu>ul>li:eq(4)').show();//显示冷链物流车管理功能
			});</script>";

	}else{
			echo "<script>
			 $(function(){
		     $('#leftmenu>ul').hide();
			});</script>";
	
	}

?>