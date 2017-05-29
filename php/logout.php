<?php 
@session_start();
@include '../init.php';

$uid = $_SESSION['uid'];
$sql = "INSERT INTO user_log (user_id,detail) VALUES ('$uid','退出登录')";
$dbh->query($sql);
unset($_SESSION['uid']);
unset($_SESSION['logname']);
unset($_SESSION['uname']);
unset($_SESSION['utype']);
unset($_SESSION['page']);

$arr['status'] = '注销成功';

$dbh = NULL;
session_destroy();
$str = json_encode($arr);
echo $str;
?>