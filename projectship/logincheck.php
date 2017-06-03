<?
//用户是否登录判断，如果没有登录，则跳转到登录页面
if(@empty($_COOKIE['username'])){

echo "您没有登录,三秒后将自动跳转到登录界面";
header("refresh:3;url=./login.php");
  exit;

}
?>