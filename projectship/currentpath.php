<?php
include("logincheck.php");//检查是否登录
include("header.php");//首部分离

$roles = $_COOKIE['roles'];
$user = $_COOKIE['username'];
$role_flag = 1;
if($roles=="普通用户") {
    $role_flag = 0;
    echo "<script>
			 $(function(){
			 $('#leftmenu>ul>li:eq(1)').hide();//隐藏用户管理功能
			 $('#leftmenu>ul>li:eq(4)').hide();//隐藏无人船管理功能
			});</script>";
}
$url = "receive.php?user=$user&roles=$role_flag";
?>



<script>
//左侧的对应栏目按钮高亮显示
$(function(){
    $("#leftmenu>ul>li>dl>dd a:eq(3)").attr("class","active");
	$("#leftmenu>ul>li>dl>dt:eq(2)").siblings().show();
});
</script>

<section class="rt_wrap content mCustomScrollbar">
<!--这是地图的展示页面-->
	 <div id="allmap1" style="width: 100%;min-height:600px;overflow:hidden;margin:0;"></div>

</section>
</body>
</html>

<script type="text/javascript">
    var lat,lon,shipid,time,shipname;
    var count = 0;

    var map = new AMap.Map('allmap1', {
        resizeEnable: true,
        center: [120.9051228600,31.9753480300],
        zoom: 13
    });


    setInterval(function () {
        $.ajax({
            type:"GET",
            url:"<?php echo $url;?>",
            dataType:'json',
            success:function (res) {
                map.clearMap();
                $.each(res,function(i,n){
                    shipid = n["id"];
                    shipname = n["shipname"];
                    lat = n["lat"];
                    lon = n["lon"];
                    time = n["time"];
                    setMarker(shipname,lon,lat,time);
                });
            },
            error: function () {
                alert("程序异常");
            }
        })},5000);

    var getGps = {
        type:"GET",
        url:"<?php echo $url;?>",
        dataType:'json',
        success:function (res) {
            $.each(res,function(i,n){
                shipid = n["id"];
                shipname = n["shipname"];
                lat = n["lat"];
                lon = n["lon"];
                time = n["time"];
                setMarker(shipname,lon,lat,time);
            });
        },
        error: function () {
            alert("程序异常");
        }
    };
    $.ajax(getGps);

    function setMarker(shipid,lon,lat,time) {
        AMap.convertFrom(lon+","+lat,"gps",
            function (status,result) {
                if (status === "complete" && result.info === 'ok') {
                    console.log(result.locations[0]);
                    addMarker(shipid,result.locations[0],time);

                }
        })
    }
    function addMarker(shipid,point,time) {
        var date = new Date(time.replace(/-/g, '/'));
        var timeconvert = date.getTime();
        var timestamp = new Date().getTime();
        var state;
        if (timestamp - timeconvert >= 100000) {
            state = "离线";
        } else {
            state = "在线";
        }
        var marker = new AMap.Marker({
            icon: "http://webapi.amap.com/theme/v1.3/markers/n/mark_b.png",
            position: [point["J"],point["N"]]
        });
        marker.setMap(map);
        // 设置鼠标划过点标记显示的文字提示
        var output = point["lng"]+","+point["lat"];
        marker.setTitle(output);

        // 设置label标签
        marker.setLabel({//label默认蓝框白底左上角显示，样式className为：amap-marker-label
            offset: new AMap.Pixel(20, 20),//修改label相对于maker的位置
            content: shipid + "，状态：" + state
        });
    }
    setTimeout("map.setFitView()",1000);
</script>



