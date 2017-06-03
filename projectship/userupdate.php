<?php
include("logincheck.php");//检查是否登录
include("header.php");//首部分离
include('conn.php');

$id=$_GET['id'];

$sql="select * from user where id=$id";
$res=mysql_query($sql);
$arr=mysql_fetch_array($res);



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
<p style="font-size:17px;font-weight:bold">&nbsp;修改用户：</p>
<br/>
<br/>
<form action="useract.php" method="get">
用户名：<input type="text" name="userName" value=<?php echo $arr['username'] ?> >

<input type="password" name="password" style="display:none"/><!--避免表单自动填充-->

密码：<input type="password" name="password"/>
角色：<select name=roles>
        <option <?php if($arr['roles']=='系统管理员') {echo 'selected';}   ?> >系统管理员
        <option <?php if($arr['roles']=='普通用户') {echo 'selected';}  ?> >普通用户
        <option value=My_Favorite>其他
</select>
电话：<input type="text" name="telephone" value=<?php echo $arr['telephone'] ?> > </input>

<input type="hidden" name="way" value="update" ></input>
<input type="hidden" name="id" value=<?php echo $id ?> ></input>

<input type="submit" value="提交"/>

</form>
 </section>
</body>
</html>
