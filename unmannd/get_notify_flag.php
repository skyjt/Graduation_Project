<?php
/**
 * Created by PhpStorm.
 * User: lzxle
 * Date: 2017/5/13
 * Time: 23:53
 */
include ("config.php");
$username = $_GET["username"];
$conn = new mysqli($db_host, $db_username, $db_password, $db_name);
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}
$sql = "SELECT telephone,notify FROM user WHERE username = '$username'";
$result = $conn->query($sql);
$value = array();
if ($result->num_rows > 0) {
    // 输出每行数据
    while($row = $result->fetch_assoc()) {
        $id = $row["id"];
        $notify = $row["notify"];
        $telephone = $row["telephone"];

        $value[] =array("id"=>$id,"notify"=>$notify,"tel"=>$telephone);
    }
    echo json_encode($value);
} else {
    echo "false";
}
$conn->close();