<?php
@session_start();
@include '../init.php';

$uid = $_SESSION['uid'];
$sql = "SELECT activity_id AS aid,title,abstract,cover,create_time FROM activity WHERE user_id = '$uid'";
$rst = $dbh->query($sql);
$arr = $rst->fetchAll();
foreach ($arr as $k => $v) {
	// var_dump($arr[$k]);
	$t = strtotime($arr[$k]['create_time']);
	$y = date('Y',$t);
	if ($y == date('Y')) {
		$arr[$k]['ctime'] = date('n月j日',$t);
	}else{
		$arr[$k]['ctime'] = date('Y年n月j日',$t);
	}
	$arr[$k]['csrc'] = 'upload/activity/'.$arr[$k]['cover'];
	$arr[$k]['page'] = 'activity/activity.php?aid='.$arr[$k]['aid'];
}
$str = json_encode($arr);
echo $str;

$dbh = NULL;
?>