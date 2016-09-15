<?php  

#设置token
session_start();
$_SESSION['token'] = md5(microtime(true));
echo $_SESSION['token'];

