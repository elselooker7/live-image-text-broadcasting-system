<?php 
@session_start();
@include '../init.php';

$logname = $_POST['logname'];
$password =  $_POST['password'];

$sql = "SELECT user_id,type,logname,uname,password FROM user WHERE logname='$logname'";
$rst = $dbh->query($sql);
$row = $rst->fetch();
//写出数据记录
if(!empty($row)){
	if($row['password']!=$password){
		// $arr['status'] = $password.'密码错误'.$row['password'];
		$arr['status'] = '密码错误';
	}else{
		$arr['status'] = 200;
		$uid = $row['user_id'];
		$time = date('Y-m-d H:i:s',time());
		$_SESSION['uid'] = $uid;
		$_SESSION['logname'] = $logname;
		$_SESSION['uname'] = $row['uname'];
		$_SESSION['utype'] = $row['type'];
		$arr['type'] = $_SESSION['utype'];
		$sql = "INSERT INTO user_log (user_id,detail) VALUES ('$uid','用户登录')";
		$dbh->query($sql);
		$sql = "UPDATE user SET lastlogin_time = '$time' WHERE user_id = '$uid'";
		$dbh->query($sql);
	}
}else{
		$arr['status'] = '用户不存在';
}
$str = json_encode($arr);
echo $str;

$dbh = NULL;
?>