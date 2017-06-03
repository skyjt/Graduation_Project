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
    $("#leftmenu>ul>li>dl>dd a:eq(5)").attr("class","active");
	$("#leftmenu>ul>li>dl>dt:eq(3)").siblings().show();
});
</script>

<section class="rt_wrap content mCustomScrollbar" >
      <h1>地图的历史轨迹查询</h1>
<form action='pathinfo.php' method='get' id='chaxun'>
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
	<input type='submit' name='button' value='提交'></input>
</form>

<br/>
<div style="border-top:1px solid #19a97b;height:700px">
<br/>
查询结果:
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
            if($answer == $user || $roles == '系统管理员'){

                $sql = "select * from locate where 
			(datetime between '$shijian1' and '$shijian2') and ship_id=$shipId order by datetime DESC";

                $res=mysql_query($sql);

                $pathinfo=array();
                while($result=mysql_fetch_array($res)){
                    $pathinfo[]=$result;
                }
                if($pathinfo==null){
                    echo "<script>alert('无数据')</script>";
                }
                $pathinfo=json_encode($pathinfo);

            }
            else{
                echo "<script>alert('错误，您账号下查无此船')</script>";
            }

	  }else{
	    exit;
	  }
	?>


<script>

  $(function(){

    //这段代码确保第二次提交表单的时候，表单里面还有数据。
	$("#chaxun select:eq(0)").val(<?php echo $shipId?>);
    $("#chaxun input:eq(0)").val(<?php echo "'$time1'"?>);
    $("#chaxun input:eq(1)").val(<?php echo "'$time2'"?>);
  });

</script>

<div id="allmap1" style="width:1300px;height:600px;overflow: hidden;margin:0;font-family:"微软雅黑";"></div>
</div>

    <script type="text/javascript">
        var lat,lon,shipid;
        var lineArr = [];
        var count = 0;
        var pathinfo=<?php  echo $pathinfo; ?>;
        var lnglatArr = [];
        for(var i = 0; i <pathinfo.length; i++){
            lnglatArr.push([pathinfo[i]['lon'],pathinfo[i]['lat']]);
        }

        var map = new AMap.Map('allmap1', {
            resizeEnable: true,
            center: [120.9051228600,31.9753480300],
            zoom: 13
        });




        function addMarker(point) {
            var marker = new AMap.Marker({
                icon: "http://webapi.amap.com/theme/v1.3/markers/n/mark_b.png",
                position: [point["J"],point["N"]]
            });
            marker.setMap(map);
            // 设置鼠标划过点标记显示的文字提示
            var output = point["lng"]+","+point["lat"];
            marker.setTitle(output);
        }
        function addLine() {
            var polyline = new AMap.Polyline({
                path: lineArr,          //设置线覆盖物路径
                strokeColor: "#3366FF", //线颜色
                strokeOpacity: 1,       //线透明度
                strokeWeight: 3,        //线宽
                strokeStyle: "solid",   //线样式
                strokeDasharray: [10, 5] //补充线样式
            });
            polyline.setMap(map);
            map.setFitView();
        }

        AMap.convertFrom(lnglatArr,"gps",function (status,result) {
            console.log(result.locations);
            for(var n = 0;n < result.locations.length; n++){
                lineArr.push([result.locations[n]["J"],result.locations[n]["N"]]);
                addMarker(result.locations[n]);
            }
            addLine();
        });



    </script>


 
</section>
</body>
</html>
