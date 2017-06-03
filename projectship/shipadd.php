<?php

include("logincheck.php");//检查是否登录
include("header.php");//首部分离
$roles = $_COOKIE['roles'];
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
<p style="font-size:17px;font-weight:bold">&nbsp;添加无人船：</p>
<br/>
<br/>
<form action="shipact.php" method='get' style="height:100px">
巡航船名称：<input type="text" name="shipName"/>
联系人：<select name="person">
        <?php
        include ("config.php");
        $conn = new mysqli($db_host, $db_username, $db_password, $db_name);
        if ($conn->connect_error) {
            die("连接失败: " . $conn->connect_error);
        }
        $sql = "select username from user";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            // 输出每行数据
            while($row = $result->fetch_assoc()) {
                $username = $row['username'];
                echo "<option value=\"$username\">$username</option>";
            }

        } else {
            echo "error";
            //echo "false";
        }
        ?>
    </select>
联系电话：<input type="text" name="telephone"/>

<input type="hidden" name='way' value="insert"/>

<input type="submit" value="提交"/>

</form>
 </section>
</body>
</html>
