<?php
/**
 * Created by PhpStorm.
 * User: lzxle
 * Date: 2017/4/18
 * Time: 15:59
 */
$id = $_GET["id"];
$sum = $_GET["sum"];
//$id = $_POST["id"];
function sql_select($sel,$from,$where){
    //sel 选择语句，from 数据表， where 条件

    $value = array();
    include ("config.php");
    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);
    if ($conn->connect_error) {
        die("连接失败: " . $conn->connect_error);
    }
    $sql = "SELECT $sel FROM $from WHERE $where";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // 输出每行数据
        while($row = $result->fetch_assoc()) {
            $id = $row["id"];
            $lat = $row["lat"];
            $lon = $row["lon"];
            $time = $row["datetime"];
            $value[] =array("id"=>$id,"lon"=>$lon,"lat"=>$lat,"time"=>$time);
        }
        return $value;
    } else {
        return "error";
        //echo "false";
    }

}

$res = sql_select("*","locate","ship_id=$id order by id DESC limit $sum");
echo json_encode($res);