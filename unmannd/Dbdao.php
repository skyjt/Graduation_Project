<?php
/**
 * Created by PhpStorm.
 * User: lzxle
 * Date: 2017/4/16
 * Time: 16:28
 */


class Dbdao{
    function sql_select($sel,$from,$where){
        //sel 选择语句，from 数据表， where 条件
        //$System_Config['db_host']="localhost";       //数据库地址
        //$System_Config['db_username']="root";       //数据库用户名
        //$System_Config['db_password']="lzx950221";  //密码
        //$System_Config['db_name']="unmanned";       //数据库名称
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
                echo "<br> id: ". $row["id"]. " - Name: ". $row["firstname"]. " " . $row["lastname"];
            }
        } else {
            echo "0 个结果";
        }
        $conn->close();
    }
}





