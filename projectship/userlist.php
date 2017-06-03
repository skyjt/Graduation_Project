<?php
include("logincheck.php");//检查是否登录
include("header.php");//首部分离
include("conn.php");
$roles = $_COOKIE['roles'];
if($roles=="普通用户") {
    echo "<script>
			 $(function(){
			 $('#leftmenu>ul>li:eq(1)').hide();//隐藏用户管理功能
			 $('#leftmenu>ul>li:eq(4)').hide();//隐藏无人船管理功能
			});</script>";
}

$sql="select * from user";
$res=mysql_query($sql);

?>

<script>
//左侧的对应栏目按钮高亮显示
$(function(){
    $("#leftmenu>ul>li>dl>dd a:eq(1)").attr("class","active");
	$("#leftmenu>ul>li>dl>dt:eq(1)").siblings().show();
});
</script>

<?
include("rootcheck.php");
?>

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
<p style="font-size:17px;font-weight:bold">&nbsp;用户列表：</p>
<br/>
<table border="1" style="width:800px;border-spacing:10px" ; >


	<thead>
		<tr>
			<th valign="middle">ID</th>
			<th valign="middle">用户名</th>
			<th valign="middle">角色</th>
			<th valign="middle">联系电话</th>
            <th valign="middle">邮箱地址</th>
			<th valign="middle">修改</th>
			<th valign="middle">删除</th>
		</tr>
	</thead>


	<tbody>
	

<?php
while($arr=mysql_fetch_array($res)){
  echo "<tr> ";
  echo "<td valign='middle'>".$arr['id']."</td>";
  echo "<td valign='middle'>".$arr['username']."</td>";
  echo "<td valign='middle'>".$arr['roles']."</td>";
  echo "<td valign='middle'>".$arr['telephone']."</td>";
    echo "<td valign='middle'>".$arr['email']."</td>";
    echo "<td valign='middle'><a href='userupdate.php?id=".$arr['id']."'>修改</a></td>";
  echo "<td valign='middle'><a href='userdelete.php?id=".$arr['id']."'>删除</a></td>";
  echo "</tr>";

}

?>

	</tbody>

</table>

 </section>
</body>
</html>
