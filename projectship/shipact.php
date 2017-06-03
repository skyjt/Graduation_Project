<?php

/* 处理修改 和 添加数据*/
include('conn.php');

header("Content-type:text/html;charset=utf-8");

$id=@$_GET['id'];
$way=$_GET["way"];
$shipId = $_GET['shipId'];
$shipName=$_GET['shipName'];
$person=$_GET['person'];
$telephone=$_GET["telephone"];

if($way=='update'){

	$sql="update ship_info set ship_name='$shipName',person='$person',tel='$telephone' where id=$id;";
	$res=mysql_query($sql);
	$count=mysql_affected_rows();

	if($count==1){
		echo '修改成功';
	}else{
		echo '修改失败';
	}
} else if($way=='insert'){
   $sql="insert into ship_info(ship_name,person,tel) values('$shipName','$person','$telephone')";
   	$res=mysql_query($sql);
	$count=mysql_affected_rows();

	if($count==1){
		echo '添加成功';
	}else{
		echo '添加失败'.$res;
	}
}

?>