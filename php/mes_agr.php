<?php
@session_start();
@include '../init.php';

$uid = $_SESSION['uid'];
// 回复评论，comment表中to_comment_id
$rst = $dbh->query("SELECT user.headimg AS img,com_agree.add_time AS tim,comment.say AS said,user.uname AS nam FROM com_agree JOIN comment ON com_agree.comment_id=comment.comment_id JOIN user ON com_agree.user_id=user.user_id WHERE com_agree.comment_id IN (SELECT comment_id FROM comment WHERE user_id='$uid')");
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
}
$str = json_encode($arr);
echo $str;

$dbh = NULL;
?>