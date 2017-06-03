<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8"/>
<title>基于北斗卫星导航的无人巡逻船系统设计与实现</title>
<meta name="author" content="DeathGhost" />
<link rel="stylesheet" type="text/css" href="css/style.css" />
<style>
body{height:100%;background:url('./images/login.jpg');overflow:hidden;}
canvas{z-index:-1;position:absolute;}
</style>

</head>
<body>

<dl class="admin_login">
 <dt>
  <strong style='color:white'>无人巡逻船系统登录</strong>
 </dt>

<form action='index.php' method='post'>
 <dd class="user_icon">

 <input style="display:none" type="text" name='username' placeholder="账号" />
 <input style="display:none" type="password" name='password' placeholder="密码" />  <!--让浏览器自动填充的数据放到这里-->


  <input type="text" name='username' placeholder="账号" autocomplete="off" class="login_txtbx"/>
 </dd>
 <dd class="pwd_icon">
  <input type="password" name='password' placeholder="密码" autocomplete="off" class="login_txtbx"/>
 </dd>

 <input type='hidden' name='way' value='login'>

 <dd>
  <input type="submit" value="立即登陆" class="submit_btn"/>
 </dd>
 
</form>


</dl>
</body>
</html>
