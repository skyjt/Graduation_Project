<script src="js/jquery.js"></script>
<?php
if(@$_POST['way']=='login'){

	$username=$logname=@$_POST['username'];
	$password=@$_POST['password'];

	if(empty($username) || empty($password)){
	  echo '用户名，密码不能为空';
	  exit;
	}

	include("conn.php");
	$sql="select * from user where username='$username' and password='$password'";


	$res=mysql_query($sql);

	$result=mysql_fetch_array($res);
	$id=$result[0];
    $roles=$result[3];

    if(!empty($id)){
        include("header.php");//首部分离
        //登录成功，设置cookie
        setcookie('username',$username);
        setcookie('roles',$roles);

        echo "<script>
//左侧的对应栏目按钮高亮显示,并控制栏目的显示和隐藏
    $(function(){
    $('#leftmenu>ul>li>dl>dd a:eq(0)').attr('class','active');//高亮显示
	$('#leftmenu>ul>li>dl>dt:eq(0)').siblings().show();//栏目的显示隐藏
});
</script>";

        if($roles=="普通用户"){
            echo "<script>
			 $(function(){
			 $('#leftmenu>ul>li:eq(1)').hide();//隐藏用户管理功能
			 $('#leftmenu>ul>li:eq(4)').hide();//隐藏无人船管理功能
			});</script>";

        }else if($roles=="系统管理员"){
            //不做任何动作
        }else{
            echo "<script>
			 $(function(){
		   $('#leftmenu>ul').hide();//
			});</script>";
        }
    }
    else{
        echo "用户名或密码错误,三秒后自动跳转到登录界面";
        header("refresh:3;url=./login.php");
        exit;
    }

}else{

    include("logincheck.php");//检查是否登录

    include("header.php");//首部分离
    echo "<script>
//左侧的对应栏目按钮高亮显示,并控制栏目的显示和隐藏
        $(function(){
            $('#leftmenu>ul>li>dl>dd a:eq(0)').attr('class','active');//高亮显示
	        $('#leftmenu>ul>li>dl>dt:eq(0)').siblings().show();//栏目的显示隐藏
        });
    </script>";

    include("rootcheck.php");
}
?>


<section class="rt_wrap content mCustomScrollbar">
	   <br/>
       <p style="font-size:20px"><b>欢迎使用无人船自动巡航系统平台</b></p>
	   <br/>
  
 <div>
<p style="font-size:20px;" >
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;海洋安全是海洋新兴产业面临的难题之一，可搭载多种测量和探测设备的无人巡逻船成为有效进行水域环境和安全监测的重要技术手段之一。针对现有自动巡逻船技术的不足，本作品基于我国自主研发的北斗卫星系统设计了一套无人巡逻船系统方案并制作了原型系统。
<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;本系统使用北斗模块采集经纬度信息，指南针模块采集船头方位信息，计算出巡航点的角度差以及位置差，作为PID算法的三个输入值，无人船设置好巡航点后，一直处于不断计算，不断调整的过程中，最终实现无人船的自动巡航功能。
<br/>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;本系统通过北斗卫星定位模块和由无人船预留的传感器接口构建成的采集子网络获取用户所需要的数据，获取的数通过无线通信模块传输到主控中心。用户登录相关网页，可直接获取所需要的各项参数并在地图上显示无人船所在位置。系统经检验，能够有效可靠地反应位置，数据传输稳定。
<br/>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;本系统采用web+Android双端配合的方式达到实时监测的效果，从而使得用户可以在任何情况下及时获取无人巡逻船所传回的数据，做到及时有效地监控测试。
</p>

</div>


 </section>

</body>
</html>
