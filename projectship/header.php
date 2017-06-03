<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<title>基于北斗卫星导航的无人巡逻船系统设计与实现</title>
<link rel="stylesheet" type="text/css" href="css/style.css" />
    <link rel="stylesheet" href="http://cache.amap.com/lbs/static/main1119.css"/>
    <link rel="stylesheet" type="text/css" href="laydate/need/laydate.css"/>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no, width=device-width">
    <script src="laydate/laydate.js"></script>
    <script src="js/jquery.js"></script>
    <script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
    <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=Nrv6Aexz3oDZvGGiGass5OVu"></script>
    <script type="text/javascript" src="http://webapi.amap.com/maps?v=1.3&key=87e6ca159274ac46afbea66b2649bf66"></script>
    <script type="text/javascript" src="http://cache.amap.com/lbs/static/addToolbar.js"></script>

</head>


<script>
$(function(){

  $("#leftmenu>ul>li>dl>dt").bind("click",function(){
    var $content=$(this).nextAll();
	if($content.is(":visible")){
	  $content.hide();
	}else{
	  $content.show();
	}
  })


});

</script>

<body>
<!--header-->
<header style="height:71px;">

<center><div style="font-size:40px;padding-top:15px;">基于北斗卫星导航的无人巡逻船系统设计与实现</div></center>

<span style="float:right;margin-right:60px;font-size:15px"><a style="color:blanchedalmond" href="logout.php">注销</a></span>
<span style="float:right;color:white">欢迎你，<?php echo @empty($logname)?$_COOKIE['username']:$logname;  
         //如果是index.php引用该页面 ，则为第一次登录，COOKIE中没有值，必须取用户填写的值 ?> !&nbsp;&nbsp;|&nbsp;&nbsp; </span>

</header>

<!--aside nav-->
<aside class="lt_aside_nav content mCustomScrollbar" id="leftmenu" style="border:1px solid #19a97b">
 <ul>

    <li>
   <dl >
    <dt style="cursor:default;">主页</dt>
    <!--当前链接则添加class:active-->
    <dd style="display:none"><a href="index.php">主页访问</a></dd>
   </dl>
  </li>

   <li>
   <dl>
    <dt style="cursor:default;">用户管理</dt>
    <dd style="display:none;"><a href="userlist.php">用户列表</a></dd>
	<dd style=display:none;"><a href="useradd.php">添加用户</a></dd>
   </dl>
  </li>


  <li>
   <dl>
    <dt style="cursor:default;">无人船实时信息显示</dt>
    <!--当前链接则添加class:active-->
    <dd style="display:none; "><a href="currentpath.php">当前位置信息</a></dd>
	<dd style="display:none; "><a href="currentstate.php">当前状态信息</a></dd>
   </dl>
  </li>
 

  <li>
   <dl>
    <dt style="cursor:default;">无人船历史信息查询</dt>
    <dd style="display:none; "><a href="pathinfo.php">无人船历史轨迹查询</a></dd>

	<dd style="display:none; "><a href="tempinfo.php">无人船温度信息查询</a></dd>
	<dd style="display:none; "><a href="huminfo.php">无人船溶解氧信息查询</a></dd>
       <dd style="display:none; "><a href="phinfo.php">无人船酸碱度信息查询</a></dd>

       <dd style="display:none; "><a href="chartinfo.php">无人船温度、溶解氧图表显示</a></dd>

   </dl>
  </li>
  


   <li>
   <dl>
    <dt style="cursor:default;">无人船管理</dt>
    <dd style="display:none; "><a href="shiplist.php">无人船列表</a></dd>
	<dd style="display:none; "><a href="shipadd.php">添加无人船</a></dd>
   </dl>
  </li>
     <li>
         <p class="btm_infor">© 版权所有</p>
     </li>

 </ul>
</aside>


