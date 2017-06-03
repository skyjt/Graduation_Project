<?php

include('conn.php');

$id=$_GET['id'];
$sql="delete from user where id=$id";
mysql_query($sql);

$count=mysql_affected_rows();
if($count==1){
  echo '删除成功';
    header("refresh:2;url=./userlist.php");
}else{
  echo '删除失败';
}


?>