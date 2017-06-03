<?php
include "config.php";
$shipId=$_GET['shipId'];
$time1=$_GET['shijian1'];
$time2=$_GET['shijian2'];
$shijian1=$time1;
$shijian2=$time2;
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);
if($conn->connect_error) {
    die("连接失败：".$conn->connect_error);
}
$sql = "select * from locate where (datetime between '$shijian1' and '$shijian2') and ship_id=$shipId order by datetime ;";
$res=$conn->query($sql);

$jsonresponse = array();
while($result=$res->fetch_array()){
    $jsonresponse[] = $result;
}
$jsonresponse = json_encode($jsonresponse);
echo $jsonresponse;

?>

