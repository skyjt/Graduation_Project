<?php
/**
 * Created by PhpStorm.
 * User: lzxle
 * Date: 2017/4/13
 * Time: 17:20
 */
$username = $_POST["username"];
$password = $_POST["password"];

/**
 * @param $sel
 * @param $from
 * @param $where
 * @return string
 */
function sql_select($sel, $from, $where){
    //sel 选择语句，from 数据表， where 条件
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
            $checkpass = $row["password"];
            return $checkpass;
        }
    } else {
        return "error";
    }

}

$res = sql_select("password","user","username = '$username'");
//echo trim($res);
if($res=="error"){
    echo "false";
}else if($res == $password){
    echo "true";
}