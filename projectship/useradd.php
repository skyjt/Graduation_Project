<?php
include("logincheck.php");//检查是否登录
include("header.php");//首部分离
?>

<script>
//左侧的对应栏目按钮高亮显示,
$(function(){
    $("#leftmenu>ul>li>dl>dd a:eq(2)").attr("class","active");
	$("#leftmenu>ul>li>dl>dt:eq(1)").siblings().show();
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
<p style="font-size:17px;font-weight:bold">&nbsp;添加用户：</p>
<br/>
<br/>
<form action="useract.php" method='get' style="height:100px">
用户名：<input type="text" name="userName"/>
密码：<input type="password" name="password"/>
角色：<select name='roles'>
        <option value='系统管理员'>系统管理员
        <option selected value='普通用户'>普通用户
        <option value='其他'>其他
</select>
电话：<input type="text" name="telephone"/>
<input type="hidden" name='way' value="insert"/>

<input type="submit" value="提交"/>


</form>
 </section>
</body>
</html>
