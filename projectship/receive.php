<?php

//set_time_limit(0);
require('./conn.php');

$user = $_GET['user'];
$roles = $_GET['roles'];
function sql_select($sel,$from,$username,$role){
    //sel ѡ����䣬from ���ݱ� where ����
    $value = array();
    include ("config.php");
    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);
    if ($conn->connect_error) {
        die("����ʧ��: " . $conn->connect_error);
    }
    $sqlcheck = "SELECT roles FROM user WHERE username = '$username'";
    $checkres = $conn->query($sqlcheck);
    while ($row = $checkres->fetch_assoc()){
        $roles = $row['roles'];
    }

    //echo $roles;
    if($role == 1){
        $sql = "SELECT $sel FROM $from";
    } else{
        $sql = "SELECT $sel FROM $from WHERE person = '$username'";
    }
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // ���ÿ������
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
    }

}

$res = sql_select("*","ship_info",$user,$roles);
echo json_encode($res);



?>


