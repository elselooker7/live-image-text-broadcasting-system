<?php
@session_start();
@include '../init.php';

$uid = $_SESSION['uid'];
// 回复评论，comment表中to_comment_id
$rst = $dbh->query("SELECT comment.comment_id AS comid,comment.to_comment_id AS to_cid,comment.add_time AS tim,comment.say AS com,user.uname AS nam,user.headimg AS img FROM comment JOIN user ON comment.user_id=user.user_id WHERE to_comment_id IN (SELECT comment_id FROM comment WHERE user_id='$uid')");
// $sql = "SELECT user.uname AS nam,comment.add_time AS tim,comment.say AS say,activity.page_name AS pag,acti_focus.add_time AS tim FROM acti_focus JOIN activity ON comment.comment_id=comment.to_comment_id WHERE acti_focus.user_id = '$uid'";
// $rst = $dbh->query($sql);
// var_dump($dbh->errorInfo());
$arr = $rst->fetchAll();
// var_dump($arr);
foreach ($arr as $k => $v) {
	// var_dump($arr[$k]);
	$t = strtotime($arr[$k]['tim']);
	// $y = date('Y',$t);
	$dt = strtotime('now')-$t;
	if ($dt==0) {
		$tim = '现在';
	}else if ($dt<60) {
		$tim = $dt.'秒前';
	}else if ($dt<3600) {
		$tim = floor($dt/60).'分钟前';
	}else if ($dt<86400) {
		$tim = floor($dt/3600).'小时前';
	}else if ($dt<172800) {
		$tim = '昨天';
	}else if ($dt<259200) {
		$tim = '前天';
	}else if (date('Y',$t)==date('Y')) {
		$tim = date('n月j日',$t);
	}else{
		$tim = date('Y年n月j日',$t);
	}
	$arr[$k]['tim'] = $tim;
	$arr[$k]['img'] = 'upload/user/'.$arr[$k]['img'];
	// echo $arr[$k]['to_cid'];
	$comid = $arr[$k]['to_cid'];
	// $dbh->query("SELECT say FROM comment WHERE comment_id =$comid");
	// var_dump($dbh->errorInfo());
	$arr[$k]['say'] = $dbh->query("SELECT say FROM comment WHERE comment_id =$comid")->fetchColumn();
}
$str = json_encode($arr);
echo $str;

$dbh = NULL;
?>