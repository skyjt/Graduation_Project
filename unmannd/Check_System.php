<?php
/**
 * Created by PhpStorm.
 * User: lzxle
 * Date: 2017/5/15
 * Time: 12:58
 */
include "config.php";
include "Warning_notify.php";
$tempdata = 0;
$temptime = 0;

$conn = new mysqli($db_host, $db_username, $db_password, $db_name);
if($conn->connect_error) {
    die("连接失败：".$conn->connect_error);
}
$sql = "SELECT * FROM locate ORDER BY id DESC LIMIT 1";
while (true){
    $nowtime = time();
    $warning_msg = "";
    $warning_flag = false;
    $result = $conn->query($sql);
    if($result->num_rows >0){
        while ($row = $result->fetch_assoc()){
            $id = (int)$row["id"];
            $ship_id = $row["ship_id"];
            $temp = (double)$row["temp"];
            $oxy = (double)$row["oxy"];
            $ph = (double)$row["ph"];
            if ($tempdata != $id && $nowtime-$temptime>=3600){
                if(($temp > 30 || $temp < 0) ){
                    $tempdata = $id;
                    $warning_msg = $warning_msg."温度预警";
                    $warning_flag = true;
                    print ("温度预警");
                }
                if(($oxy > 40 || $oxy < 5)){
                    $tempdata = $id;
                    if($warning_flag == true){
                        $warning_msg = $warning_msg.",";
                    }
                    $warning_msg = $warning_msg."溶氧量预警";
                    $warning_flag = true;
                    //sendsms("admin",$ship_id,"溶氧量预警");
                    print ("溶氧量预警");
                }
                if(($ph > 8.5 || $ph < 6.5)){
                    $tempdata = $id;
                    if($warning_flag == true){
                        $warning_msg = $warning_msg.",";
                    }
                    $warning_msg = $warning_msg."酸碱度预警";
                    $warning_flag = true;
                    print ("酸碱度预警");
                }
                if($warning_flag == true){
                    sendsms("admin",$ship_id,$warning_msg);
                    $temptime = time();
                }
            }

        }
    }
    //print ("test");
    sleep(0.5);
}