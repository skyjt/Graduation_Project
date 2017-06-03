<?php
include("logincheck.php");//检查是否登录
include("header.php");//首部分离
$roles = $_COOKIE['roles'];
$user = $_COOKIE['username'];
if($roles=="普通用户") {
    echo "<script>
			 $(function(){
			 $('#leftmenu>ul>li:eq(1)').hide();//隐藏用户管理功能
			 $('#leftmenu>ul>li:eq(4)').hide();//隐藏无人船管理功能
			});</script>";
}
?>

<script>
//左侧的对应栏目按钮高亮显示
$(function(){
    $("#leftmenu>ul>li>dl>dd a:eq(4)").attr("class","active");
	$("#leftmenu>ul>li>dl>dt:eq(2)").siblings().show();
});
</script>

<style>
table th{
  display:table-cell;
  vertical-align:inherit;
  font-weight:bold;
  text-align:center;
  background:grey;
  color:white;
  font-size:18px;
}

table tr{
   text-align:center;
}


</style>

<section class="rt_wrap content mCustomScrollbar">
<br/>
<p style="font-size:17px;font-weight:bold">&nbsp;无人船实时状态列表：</p>
<br/>
<table border="1" style="min-width:700px;border-spacing:10px" ; >


	<thead>
		<tr>
			<th valign="middle">无人船ID</th>
            <th valign="middle">是否在线</th>
			<th valign="middle">温度</th>
			<th valign="middle">溶解氧</th>
            <th valign="middle">酸碱度</th>
			<th valign="middle">经度</th>
			<th valign="middle">纬度</th>
			<th valign="middle">温度状态</th>
			<th valign="middle">溶解氧状态</th>
            <th valign="middle">酸碱度状态</th>
		</tr>
	</thead>


	<tbody>


<?php
include("conn.php");
$user = $_COOKIE['username'];
$roles = $_COOKIE['roles'];
if ($roles == "系统管理员"){
    $sql="select * from ship_info order by id";
}
else{
    $sql="select * from ship_info WHERE person = '$user' order by id";

}
$time=time();
$shipId=array();

$res=mysql_query($sql);

while($arr=mysql_fetch_array($res)){

	$shipId[]=$arr['id'];
}

foreach($shipId as $id){
   
	$datetime1=date("20y-m-d H:i:s",$time-1000);//大写的H表示是24 小时制。一分钟之内可以查到数据，表示无人船在线。
	$datetime2=date("20y-m-d H:i:s",$time);
    if($roles=='系统管理员'){
        $sql = "select * from locate where ship_id=$id order by datetime desc limit 1;";
    }
    else{
        $sql = "select * from locate where ship_id=$id and person = '$user' order by datetime desc limit 1;";

    }
    $res=mysql_query($sql);
    $result=mysql_fetch_array($res);
    $restime = strtotime($result['datetime']);
	if($restime<time()-100)
	{
	  echo "<tr>";
      echo "<td valign='middle'>".$id."</td>";
	  echo "<td valign='middle'>离线</td>";
        echo "<td valign='middle'>".$result['temp']."</td>";
        echo "<td valign='middle'>".$result['oxy']."mg/l</td>";
        echo "<td valign='middle'>".$result['ph']."</td>";
        echo "<td valign='middle'>".$result['lon']."</td>";
        echo "<td valign='middle'>".$result['lat']."</td>";
        if($result['temp']<=30 && $result['temp']>=0  ){
            echo "<td valign='middle'>温度正常</td>";
        }else{
            echo "<td valign='middle' style='color:red'>温度异常</td>";
        }

        if($result['oxy']>=10 && $result['oxy']<=40){
            echo "<td valign='middle'>溶解氧正常</td>";
        }else{
            echo "<td valign='middle' style='color:red'>溶解氧异常</td>";
        }
        if($result['ph']<=8.5 && $result['ph']>=6.5){
            echo "<td valign='middle'>酸碱度正常</td>";
        }else{
            echo "<td valign='middle' style='color:red'>酸碱度异常</td>";
        }

      echo "</tr>";
	}else{
	  echo "<tr>";
      echo "<td valign='middle'>".$id."</td>";
	  echo "<td valign='middle'>在线</td>";
	  echo "<td valign='middle'>".$result['temp']."</td>";
	  echo "<td valign='middle'>".$result['oxy']."mg/l</td>";
	  echo "<td valign='middle'>".$result['ph']."</td>";
	  echo "<td valign='middle'>".$result['lon']."</td>";
	  echo "<td valign='middle'>".$result['lat']."</td>";
	  if($result['temp']<=30 && $result['temp']>=0  ){
		  echo "<td valign='middle'>温度正常</td>";
	  }else{
		  echo "<td valign='middle' style='color:red'>温度异常</td>";
	  }
      
	  if($result['oxy']>=10 && $result['oxy']<=40){
	     echo "<td valign='middle'>溶解氧正常</td>";
	  }else{
	     echo "<td valign='middle' style='color:red'>溶解氧异常</td>";
	  }
        if($result['ph']<=8.5 && $result['ph']>=6.5){
            echo "<td valign='middle'>酸碱度正常</td>";
        }else{
            echo "<td valign='middle' style='color:red'>酸碱度异常</td>";
        }

      echo "</tr>";
	}

}

?>




	</tbody>

</table>

 </section>
</body>
</html>
