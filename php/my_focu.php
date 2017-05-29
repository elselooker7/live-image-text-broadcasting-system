<?php
@session_start();
@include '../init.php';

$uid = $_SESSION['uid'];
$sql = "SELECT activity.activity_id AS aid,activity.title AS tit,activity.abstract AS des,activity.cover AS img,acti_focus.add_time AS tim FROM acti_focus JOIN activity ON acti_focus.activity_id=activity.activity_id WHERE acti_focus.user_id = '$uid'";
$rst = $dbh->query($sql);
// var_dump($dbh->errorInfo());
$arr = $rst->fetchAll();
foreach ($arr as $k => $v) {
	// var_dump($arr[$k]);
	$t = strtotime($arr[$k]['tim']);
	$y = date('Y',$t);
	if ($y == date('Y')) {
		$arr[$k]['tim'] = date('n月j日',$t);
	}else{
		$arr[$k]['tim'] = date('Y年n月j日',$t);
	}
	$arr[$k]['img'] = 'upload/activity/'.$arr[$k]['img'];
	$arr[$k]['pag'] = 'activity/activity.php?aid='.$arr[$k]['aid'];
}
$str = json_encode($arr);
echo $str;

$dbh = NULL;
?>