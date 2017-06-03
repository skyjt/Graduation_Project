<?php
/**
 * Created by PhpStorm.
 * User: lzxle
 * Date: 2017/4/18
 * Time: 15:28
 */
//$shipname = $_POST["ship_name"];
//$password = $_POST["password"];
$username = $_GET['username'];

function sql_select($sel,$from,$username){
    //sel 选择语句，from 数据表， where 条件
    $value = array();
    include ("config.php");
    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);
    if ($conn->connect_error) {
        die("连接失败: " . $conn->connect_error);
    }
    if($username=="admin"){
        $sql = "SELECT $sel FROM $from";
    }
    else{
        $sql = "SELECT $sel FROM $from WHERE person = '$username'";
    }
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // 输出每行数据
        while($row = $result->fetch_assoc()) {
            $id = $row["id"];
            $shipname = $row["ship_name"];
            $lat = $row["ship_lastlat"];
            $lon = $row["ship_lastlon"];
            $time = $row["ship_lasttime"];
            $value[] =array("id"=>$id,"shipname"=>$shipname,"lat"=>$lat,'lon'=>$lon,'time'=>$time);

        }
        return $value;
    } else {
        return "error";
        //echo "false";
    }

}

$res = sql_select("*","ship_info",$username);
echo json_encode($res);
//if($res=="error"){
//    echo "false";
//}else if($res = $password){
//    echo "right";
//}