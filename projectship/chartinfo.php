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

<!--这里是引入图表插件higncharts-->
		<script src="http://cdn.hcharts.cn/highcharts/highcharts.js"></script>
        <script src="http://cdn.hcharts.cn/highcharts/modules/exporting.js"></script>

<script>
//左侧的对应栏目按钮高亮显示
$(function(){
    $("#leftmenu>ul>li>dl>dd a:eq(9)").attr("class","active");
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


 <h1>无人船温度、溶解氧图表显示：</h1>
 
  <form action="chartinfo.php" method="get" id="chaxun">
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
  <p>提示，鼠标左击横向拖动可以放大曲线图 &nbsp; ; 点击右上角小图标，可以打印图表</P>


  <br/>
  
  <?php
	  include("conn.php");
        $user = $_COOKIE['username'];
        $roles = $_COOKIE['roles'];
      $button=@$_GET['button'];
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
      $sql = "select * from locate where (datetime between '$shijian1' and '$shijian2') and ship_id=$shipId order by datetime desc ;";
      $res=mysql_query($sql);

      $wenrongjieyangarr=array();

      while($result=mysql_fetch_array($res)){

          $wenrongjieyangarr[]=array($result['datetime'],$result['temp'],$result['oxy']);
      }



      //拼接成js中的数组
      $wendu='[';
      $rongjieyang='[';
      $length=count($wenrongjieyangarr);

      for($i=0;$i<$length;$i++){
          $time=strtotime($wenrongjieyangarr[$i][0])*1000;
          if($i<$length-1){
              //拼接温度
              $str1="[".$time.",".$wenrongjieyangarr[$i][1]."],";
              $wendu.=$str1;

              //拼接溶解氧
              $str2="[".$time.",".$wenrongjieyangarr[$i][2]."],";
              $rongjieyang.=$str2;

          }elseif($i==$length-1){
              $str1="[".$time.",".$wenrongjieyangarr[$i][1]."]]";
              $wendu.=$str1;

              $str2="[".$time.",".$wenrongjieyangarr[$i][2]."]]";
              $rongjieyang.=$str2;
          }
      }
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





  <!--图表显示的容器-->

  <div id="container1" style="width: 800px; height: 400px; margin: 0 auto"></div>

  <br/>
  <br/>

  <div id="container2" style="width: 800px; height: 400px; margin: 0 auto"></div>

</div>

 <script>
 $(function() {

	          var wendu=eval(<?php echo $wendu; ?>);
			  var rongjieyang=eval(<?php echo $rongjieyang; ?>);
			  

               Highcharts.setOptions({
                    global: {
                      useUTC: false  //表示是使用本地时间而不是utc的标准时间。
                    }
                  });

	         
			  //温度图表代码
       
                  chart1 = new Highcharts.Chart({
                    chart: {
					  zoomType: 'x',
                      renderTo: 'container1',
                      defaultSeriesType: 'spline',  //表示是一条曲线，表示点与点之间是通过曲线连在一起的。
                      marginRight: 10
                    },
				    credits:{
                    enabled:false // 去掉右下角的版权信息显示
                    },
						
                    title: {
                      text: '无人船温度信息'
                    },
                    xAxis: {
                      title: {
                        text: '时间'
                      },
                      labels: { 
                         formatter: function() { 
                         return  Highcharts.dateFormat('%m-%d日 %H时', this.value); 
                         } 
					  },

                      type: 'datetime',                 //设置y轴是时间类型
                      //坐标间隔
                      //tickPixelInterval: 48 * 3600 * 1000    //不要自己设置坐标间隔，而是让这个插件根据你后面给的数据自动设置x轴时间坐标的显示形式
					  
                    },

                    yAxis: {
                      title: {
                        text: '温度'
					    },
						labels: {
						formatter:function(){
							return this.value+"°C";
					     }
						},
                      //指定y=3直线的样式,设置警戒线
                      plotLines: [
                        {
                          value: -10,
                          width: 2,
                          color: 'red',
						  label:{
                           text:'温度警戒线',     //标签的内容
                           align:'left',                //标签的水平位置，水平居左,默认是水平居中center
                           x:10                         //标签相对于被定位的位置水平偏移的像素，重新定位，水平居左10px
                          
						  }
                        }
                      ]
                    },
                    //鼠标放在某个点上时的提示信息
                    //dateFormat,numberFormat是highCharts的工具类
                    tooltip: {
                      formatter: function() {
                        return "时间"+
                                Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) + '<br/>' +"温度"+
                                Highcharts.numberFormat(this.y, 4)+"°C";
                      }
                    },
                    //曲线的示例说明，像地图上得图标说明一样,即图例
                    legend: {
                      enabled: true
                    },
                    //把曲线图导出成图片等格式
                    exporting: {
                      enabled: true
                    },
                    //放入数据
                    series: [
                      {
                        name: '历史温度',
                        data: wendu         //这里是通过数组的形式，即（x,y)的形式描点赋值的。x为时间戳（单位毫秒）[[1,2],[1,2]]
							                        						 
                      }
                    ]
                  });



              
  //温度图表代码
 

                  //声明报表对象

                  chart2 = new Highcharts.Chart({
                    chart: {
					  zoomType: 'x',
                      renderTo: 'container2',
                      defaultSeriesType: 'spline',  //表示是一条曲线，表示点与点之间是通过曲线连在一起的。
                      marginRight: 10
                    },
				    credits:{
                    enabled:false // 去掉右下角的版权信息显示
                    },
						
                    title: {
                      text: '无人船溶解氧信息'
                    },
                    xAxis: {
                      title: {
                        text: '时间'
                      },
                      labels: { 
                         formatter: function() { 
                         return  Highcharts.dateFormat('%m-%d日 %H时', this.value); 
                         } 
					  },

                      type: 'datetime',                 //设置y轴是时间类型
                      //坐标间隔
                      //tickPixelInterval: 48 * 3600 * 1000    //不要自己设置坐标间隔，而是让这个插件根据你后面给的数据自动设置x轴时间坐标的显示形式
					  
                    },

                    yAxis: {
                      title: {
                        text: '溶解氧'
					    },
						labels: {
						formatter:function(){
							return this.value+"mg/l";
					     }
						},
                      //指定y=3直线的样式,设置警戒线
                      plotLines: [
                        {
                          value: 40,
                          width: 2,
                          color: 'red',
						  label:{
                           text:'溶解氧警戒线',     //标签的内容
                           align:'left',                //标签的水平位置，水平居左,默认是水平居中center
                           x:10                         //标签相对于被定位的位置水平偏移的像素，重新定位，水平居左10px
                          
						  }
                        }
                      ]
                    },
                    //鼠标放在某个点上时的提示信息
                    //dateFormat,numberFormat是highCharts的工具类
                    tooltip: {
                      formatter: function() {
                        return "时间"+
                                Highcharts.dateFormat('%Y-%m-%d %H:%M:%S', this.x) + '<br/>' +"溶解氧"+
                                Highcharts.numberFormat(this.y, 4)+"°C";
                      }
                    },
                    //曲线的示例说明，像地图上得图标说明一样,即图例
                    legend: {
                      enabled: true
                    },
                    //把曲线图导出成图片等格式
                    exporting: {
                      enabled: true
                    },
                    //放入数据
                    series: [
                      {
                        name: '历史溶解氧',
                        data: rongjieyang
                      }
                    ]
                  });
                });

 </script>

 </section>
</body>
</html>
