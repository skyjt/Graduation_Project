<?php
/**
 * Created by PhpStorm.
 * User: lzxle
 * Date: 2017/5/13
 * Time: 23:53
 */
$tel = $_GET["tel"];
$username = $_GET["username"];
$notify = $_GET["notify"];

function updateTel($tel,$username){
    include ("config.php");
    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);
    if ($conn->connect_error) {
        die("连接失败: " . $conn->connect_error);
    }
    $sql = "UPDATE user SET telephone = '$tel' WHERE username = '$username'";
    $result = $conn->query($sql);
    if ($conn->query($sql) === TRUE) {
        return "success";
    } else {
        return "Error: " . $sql . "<br>" . $conn->error;
    }
    $conn->close();
}

function updateNotify($notify,$username){
    include ("config.php");
    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);
    if ($conn->connect_error) {
        die("连接失败: " . $conn->connect_error);
    }
    $sql = "UPDATE user SET notify = '$notify' WHERE username = '$username'";
    $result = $conn->query($sql);
    if ($conn->query($sql) === TRUE) {
        return "success";
    } else {
        return "Error: " . $sql . "<br>" . $conn->error;
    }
    $conn->close();
}
if ($notify != null){
    echo updateNotify($notify,$username);
}
if ($tel != null){
    echo updateTel($tel,$username);

}
