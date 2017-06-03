<?php
/**
 * Created by PhpStorm.
 * User: lzxle
 * Date: 2017/4/18
 * Time: 15:28
 */
//$shipname = $_POST["ship_name"];
//$password = $_POST["password"];
$shipid = $_GET["ship_id"];
function sql_select($sel,$from,$ids) {
    //sel 选择语句，from 数据表， where 条件
    $value = array();
    include("config.php");
    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);
    if ($conn->connect_error) {
        die("连接失败: " . $conn->connect_error);
    }
    $sql = "SELECT $sel FROM $from WHERE ship_id=$ids order by id DESC LIMIT 1 ";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // 输出每行数据
        while ($row = $result->fetch_assoc()) {
            $id = $row["id"];
            //$shipname = $row["ship_name"];
            $lat = $row["lat"];
            $lon = $row["lon"];

            $time = $row["datetime"];
            $temp = $row["temp"];
            $oxy = $row["oxy"];
            $ph = $row["ph"];
            $value[] = array("id" => $id, "lat" => $lat, 'lon' => $lon, 'time' => $time,  'temp' => $temp, 'oxy' => $oxy, 'ph' => $ph);

        }
        return $value;
    } else {
        return "error";
        //echo "false";
    }

}

$res = sql_select("*","locate",$shipid);
echo json_encode($res);
//if($res=="error"){
//    echo "false";
//}else if($res = $password){
//    echo "right";
//}