<?php

include('conn.php');

$id=@$_GET['id'];
$way=$_GET["way"];

$userName=$_GET['userName'];
$password=$_GET['password'];
$roles=$_GET['roles'];
$telephone=$_GET["telephone"];

if($way=='update' && empty($password)){

	$sql="update user set username='$userName',roles='$roles',telephone='$telephone' where id=$id;";
	$res=mysql_query($sql);
	$count=mysql_affected_rows();

	if($count==1){
		echo '修改成功';
	}else{
		echo '修改失败';
	}
}else if($way =='update' && !empty($password)){
    
  	$sql="update user set username='$userName',password='$password',roles='$roles',telephone='$telephone' where id=$id;";
	$res=mysql_query($sql);
	$count=mysql_affected_rows();

	if($count==1){
		echo '修改成功';
	}else{
		echo '修改失败';
	}

} else if($way=='insert'){

    if(empty($password)){
	  echo '密码不能为空';
      exit;
	}
   $sql="insert into user(username,password,roles,telephone) values('$userName','$password','$roles','$telephone')";
   	$res=mysql_query($sql);
	$count=mysql_affected_rows();

	if($count==1){
		echo '添加成功';
        header("refresh:2;url=./useradd.php");
	}else{
		echo '添加失败';
	}
}

?>