<?php
include("logincheck.php");//检查是否登录
include("header.php");//首部分离
include("conn.php");
$id=$_GET['id'];

$sql="select * from ship_info where id=$id";
$res=mysql_query($sql);
$arr=mysql_fetch_array($res);
?>

<script>
//左侧的对应栏目按钮高亮显示
$(function(){
    $("#leftmenu>ul>li>dl>dd a:eq(11)").attr("class","active");
	$("#leftmenu>ul>li>dl>dt:eq(4)").siblings().show();
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
<p style="font-size:17px;font-weight:bold">&nbsp;修改循环船信息：</p>
<br/>
<br/>
<form action="shipact.php" method="get">
巡航船名称：<input type="text" name="shipName" value=<?php echo $arr['ship_name'] ?> >
联系人：<input type="text" name="person" value=<?php echo $arr['person'] ?> >
联系电话：<input type="text" name="telephone" value=<?php echo $arr['tel'] ?> >
<input type="hidden" name="way" value="update" ></input>
<input type="hidden" name="id" value=<?php echo $id ?> ></input>

<input type="submit" value="提交"/>

</form>
 </section>
</body>
</html>
