<?php
/**
 * Created by PhpStorm.
 * User: lzxle
 * Date: 2017/4/10
 * Time: 14:59
 */
include "TopSdk.php";
date_default_timezone_set('Asia/Shanghai');
function findperson($num){
    include "config.php";
    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);
    if($conn->connect_error) {
        die("连接失败：" . $conn->connect_error);
    }
    $sql = "select tel,person from ship_info WHERE id = '$num'";
    $result = $conn->query($sql);
    if($result->num_rows >0) {
        while ($row = $result->fetch_assoc()) {
            $username = $row['person'];
            return $username;
        }
    }
}

function findtel($name){
    include "config.php";
    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);
    if($conn->connect_error) {
        die("连接失败：" . $conn->connect_error);
    }
    $sql = "SELECT telephone,notify FROM user WHERE username = '$name'";
    $result = $conn->query($sql);
    if($result->num_rows >0){
        while ($row = $result->fetch_assoc()) {
            $tel = $row["telephone"];
            $notify = (int)$row["notify"];
            if($notify==1){
                return $tel;
            }else{
                return "error";
            }
        }
    }else{
        return "error";
    }
    $conn->close();
}

function comparetime(){
    include "config.php";
    $conn = new mysqli($db_host, $db_username, $db_password, $db_name);
    if($conn->connect_error) {
        die("连接失败：" . $conn->connect_error);
    }
    $sql = "SELECT time FROM warning ORDER BY id DESC LIMIT 1";
    $result = $conn->query($sql);
    if($result->num_rows >0){
        while ($row = $result->fetch_assoc()) {
            $time = $row["time"];
            if(time()-strtotime($time)>=10){
                return "true";
            }
            else{
                return "error";``
            }
        }
    }else{
        return "error";
    }

    $conn->close();
}


function sendsms($num,$info){
    $name = findperson($num);
    $tel = findtel($name);
    $flag = comparetime();
    if($tel!=="error"&&$flag!=="error"){

        $appkey = "23820454";
        $secret = "6b019cd21fc7bbd6b77d6c595d4cc94a";
        $c = new TopClient;
        $c->appkey = $appkey;
        $c->secretKey = $secret;
        $req = new AlibabaAliqinFcSmsNumSendRequest;
        $req->setExtend("123456");
        $req->setSmsType("normal");
        $req->setSmsFreeSignName("Kenny毕设");
        $req->setSmsParam("{\"name\":\"$name\",\"num\":\"$num\",\"content\":\"$info\"}");
        $req->setRecNum(findtel($name));
        $req->setSmsTemplateCode("SMS_67165180");
        $resp = $c->execute($req);
        echo $resp;
        include "config.php";
        $conn = new mysqli($db_host, $db_username, $db_password, $db_name);
        if($conn->connect_error) {
            die("连接失败：" . $conn->connect_error);
        }
        $sql = "INSERT INTO warning (warning_msg,sms_send) VALUES ('$info',1)";
        $result = $conn->query($sql);
        $conn->close();
    }

    else {
        include "config.php";
        $conn = new mysqli($db_host, $db_username, $db_password, $db_name);
        if($conn->connect_error) {
            die("连接失败：" . $conn->connect_error);
        }
        $sql = "INSERT INTO warning (warning_msg,sms_send) VALUES ('$info',0)";
        $result = $conn->query($sql);
        $conn->close();
        echo "sms_error";
    }

}
