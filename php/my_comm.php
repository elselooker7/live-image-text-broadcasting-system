<?php
@session_start();
@include '../init.php';

$uid = $_SESSION['uid'];
$sql = "SELECT comment_id AS comid,content_id,activity_id,to_comment_id,say,add_time AS tim FROM comment WHERE user_id = '$uid'";
$rst = $dbh->query($sql);
$arr = $rst->fetchAll();
foreach ($arr as $k => $v) {
	$t = strtotime($arr[$k]['tim']);
	$y = date('Y',$t);
	if ($y == date('Y')) {
		$arr[$k]['tim'] = date('n月j日',$t);
	}else{
		$arr[$k]['tim'] = date('Y年n月j日',$t);
	}
	if ($arr[$k]['content_id']!=0) {
		$target = 'content';
		$tid = $arr[$k]['content_id'];
	}elseif ($arr[$k]['activity_id']!=0&&$arr[$k]['content_id']==0) {
		$target = 'activity';
		$tid = $arr[$k]['activity_id'];
	}elseif ($arr[$k]['to_comment_id']!=0) {
		$target = 'comment';
		$tid = $arr[$k]['to_comment_id'];
	}
	$arr[$k]['tar'] = 'acti';
	switch ($target) {
		case 'content':
			$sql_sel = "SELECT content.content_id AS cid,content.description AS des,content.activity_id AS aid,cont_img.img_name AS img FROM content, cont_img WHERE content.content_id = cont_img.content_id AND content.content_id = $tid ";
			$rst_sel = $dbh->query($sql_sel);
			// var_dump($dbh->errorInfo());
			$row = $rst_sel->fetch();
			$arr[$k]['cid'] = $row['cid'];
			$arr[$k]['des'] = $row['des'];
			$arr[$k]['img'] = 'upload/content/'.$row['img'];
			$aid = $row['aid'];
			$sql_sel = "SELECT title FROM activity WHERE activity_id = $aid";
			$rst_sel = $dbh->query($sql_sel);
			$row = $rst_sel->fetch();
			$arr[$k]['tit'] = $row[0];
			$arr[$k]['tar'] = 'cont';
			break;
		case 'activity':
			$sql_sel = "SELECT activity_id AS aid,title,abstract,cover AS pag FROM activity WHERE activity_id = $tid";
			$rst_sel = $dbh->query($sql_sel);
			$row = $rst_sel->fetch();
			$arr[$k]['aid'] = $row[0];
			$arr[$k]['tit'] = $row[1];
			$arr[$k]['des'] = $row[2];
			$arr[$k]['img'] = 'upload/activity/'.$row[3];
			$arr[$k]['pag'] = 'activity/activity.php?aid='.$row['aid'];
			$arr[$k]['tar'] = 'acti';
			break;
		case 'comment':
			$sql_sel = "SELECT comment_id AS comid,say,user.headimg AS img,user.uname AS tit FROM comment,user WHERE comment.user_id=user.user_id AND comment.comment_id = $tid";
			$rst_sel = $dbh->query($sql_sel);
			$row = $rst_sel->fetch();
			$arr[$k]['comid'] = $row['comid'];
			$arr[$k]['tit'] = $row['tit'];
			$arr[$k]['img'] = 'upload/user/'.$row['img'];
			$arr[$k]['des'] = $row['say'];
			$arr[$k]['tar'] = 'com';
			break;
		default:
			# code...
			break;
	}
}
$str = json_encode($arr);
echo $str;

$dbh = NULL;
?>