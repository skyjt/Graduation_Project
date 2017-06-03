<?php
/**
 * Created by PhpStorm.
 * User: lzxle
 * Date: 2017/5/9
 * Time: 20:14
 */
include ("config.php");
include ("Warning_notify.php");
$shipid = $_GET["id"];
$lat = $_GET["lat"];
$lon = $_GET["lon"];
$temp = $_GET["temp"];
$oxy = $_GET["oxy"];
$ph = $_GET["ph"];
$nowtime = time();
$warning_msg="";

$warning_flag = false;
    if(($temp > 30 || $temp < 0) ){
        $warning_msg = $warning_msg."温度";
        $warning_flag = true;
        //print ("温度预警");
    }
    if(($oxy > 40 || $oxy < 5)){
        if($warning_flag == true){
            $warning_msg = $warning_msg.",";
        }
        $warning_msg = $warning_msg."溶氧量";
        $warning_flag = true;
        //print ("溶氧量预警");
    }
    if(($ph > 8.5 || $ph < 6.5)){
        if($warning_flag == true){
            $warning_msg = $warning_msg.",";
        }
        $warning_msg = $warning_msg."酸碱度";
        $warning_flag = true;
        //print ("酸碱度预警");
    }
    if($warning_flag == true){
        //print ($warning_msg);
       // sendsms($shipid,$warning_msg."预警");
    }

$conn = new mysqli($db_host, $db_username, $db_password, $db_name);
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}
$sql = "INSERT INTO locate (ship_id, lat, lon, temp, oxy, ph) VALUES ($shipid, $lat, $lon, $temp,$oxy,$ph)";
if ($conn->query($sql) === TRUE) {
    echo "success";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>