<?php
@session_start();
@include '../init.php';

$uid = $_SESSION['uid'];
$sql = "SELECT cont_like.add_time AS tim,content.content_id AS cid,count(distinct content.content_id),content.description AS des,cont_img.img_name AS img,activity.activity_id AS aid,activity.title AS tit FROM cont_like JOIN content ON cont_like.content_id=content.content_id JOIN cont_img ON cont_img.content_id=cont_like.content_id JOIN activity ON activity.activity_id=content.activity_id WHERE cont_like.user_id = '$uid' GROUP BY content.content_id";
// $sql = "SELECT cont_like.content_id AS cid,cont_like.add_time AS tim,description AS des,cont_img.img_name FROM cont_like,content,cont_img WHERE cont_like.content_id=content.content_id AND cont_img.content_id=content.content_id AND user_id = '$uid'";
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
	$arr[$k]['img'] = 'upload/content/'.$arr[$k]['img'];
	$arr[$k]['pag'] = 'activity/activity.php?aid='.$arr[$k]['aid'];
}
$str = json_encode($arr);
echo $str;

$dbh = NULL;
?>