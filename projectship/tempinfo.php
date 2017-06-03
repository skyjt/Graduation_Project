<?php
include("logincheck.php");//检查是否登录
include("header.php");//首部分离
$roles = $_COOKIE['roles'];
$username = $_COOKIE['username'];
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
    $("#leftmenu>ul>li>dl>dd a:eq(6)").attr("class","active");
	$("#leftmenu>ul>li>dl>dt:eq(3)").siblings().show();
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


 <h1>巡航船温度信息查询：</h1>

  <form action="tempinfo.php" method="get" id="chaxun">
 无人船ID<select name='shipId'>
          <?php
          include ("config.php");
          $conn = new mysqli($db_host, $db_username, $db_password, $db_name);
          if ($conn->connect_error) {
              die("连接失败: " . $conn->connect_error);
          }
          if("普通用户"==$roles){
              $sql = "select * from ship_info WHERE person = '$username'";
          }else{
              $sql = "select * from ship_info";
          }
          $result = $conn->query($sql);
          if ($result->num_rows > 0) {
              // 输出每行数据
              while($row = $result->fetch_assoc()) {
                  $shipId = $row['id'];
                  $shipName = $row['ship_name'];
                  echo "<option value=\"$shipId\">$shipName</option>";
              }

          } else {
              echo "error";
              //echo "false";
          }
          ?></select>
      从时间<input   name='shijian1' class="laydate-icon" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm'})"></input>
      到时间<input   name='shijian2' class="laydate-icon" onclick="laydate({istime: true, format: 'YYYY-MM-DD hh:mm'})"></input>
 <input type='submit' name='button' value='提交'/>
 </form>


<br/>
<div style="border-top:1px solid #19a97b;margin-bottom: 10%;overflow:auto">
<br/>
<p style="font-size:16px">查询结果:</p>




<br/>
<p style="font-size:15px;font-weight:bold" id="temp">最高温度：<span></span> &nbsp;  最低温度：<span></span> &nbsp; 平均温度：<span></span> </p>
<br/>
   <table border="1" style="min-width:500px;border-spacing:10px" ; >
	<thead>
		<tr>
			<th valign="middle">时间</th>
			<th valign="middle">温度</th>
			<th valign="middle">经度</th>
			<th valign="middle">纬度</th>
		</tr>
	</thead>

	<tbody>

	 <?php
	  include("conn.php");

      $button=@$_GET['button'];
     $roles = $_COOKIE['roles'];
     $user = $_COOKIE['username'];
	  if(!empty($button)){
		    $shipId=$_GET['shipId'];
		    $time1=$_GET['shijian1'];
		    $time2=$_GET['shijian2'];
		    $shijian1=str_replace('T',' ',$time1);
		    $shijian2=str_replace('T',' ',$time2);

          $sqlcheck = "select person from ship_info WHERE id = '$shipId'";
          $rescheck = mysql_query($sqlcheck);
          $response = mysql_fetch_array($rescheck);
          $answer = $response[0];
        if($answer == $user || $roles == '系统管理员') {

            $sql = "select * from locate where (datetime between '$shijian1' and '$shijian2') and ship_id=$shipId order by datetime desc ;";
            $res = mysql_query($sql);

            $wenduarr = array();

            while ($result = mysql_fetch_array($res)) {
                echo "<tr>";
                echo "<td valign='middle'>" . $result['datetime'] . "</td>";
                if ((double)$result['temp'] > 30 || (double)$result['temp'] < 0) {
                    echo "<td valign='middle' style='color: red'>" . $result['temp'] . "</td>";
                } else {
                    echo "<td valign='middle'>" . $result['temp'] . "</td>";
                }

                echo "<td valign='middle'>" . $result['lon'] . "</td>";
                echo "<td valign='middle'>" . $result['lat'] . "</td>";
                echo "</tr>";
                $wenduarr[] = $result['temp'];
            }
        }else{
            echo "<script>alert('错误，您账号下查无此船')</script>";

        }
	  }else{
	  
	    exit;
	  }
	?>
		




	</tbody>	
</table>
    <script>
        $(function(){
            //这段代码确保第二次提交表单的时候，表单里面还有数据。
            $("#chaxun select:eq(0)").val(<?php echo $shipId?>);
            $("#chaxun input:eq(0)").val(<?php echo "'$time1'"?>);
            $("#chaxun input:eq(1)").val(<?php echo "'$time2'"?>);

            //将最高温度，最低温度信息显示出来
            $("#temp span:eq(0)").text(<?php echo max($wenduarr); ?> );
            $("#temp span:eq(1)").text(<?php echo min($wenduarr); ?>);
            $("#temp span:eq(2)").text(<?php echo round(array_sum($wenduarr)/count($wenduarr),2); ?>);


        });
    </script>
</div>

 </section>
</body>
</html>
