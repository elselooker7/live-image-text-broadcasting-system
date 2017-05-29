<?php
@session_start();
@include '../init.php';

$usr = isset($_SESSION['uname'])?$_SESSION['uname']:'游客';
$uid = isset($_SESSION['uid'])?$_SESSION['uid']:0;
$to_typ = $_POST['to_typ'];
$to_id = $_POST['to_id'];
$say = $_POST['say'];
switch ($to_typ) {
	case 'comment':
		// $dbh->exec("SET FOREIGN_KEY_CHECKS=0");
		$c = $dbh->exec("INSERT INTO comment (user_id,to_comment_id,say) VALUES ('$uid','$to_id','$say')");
		// var_dump($dbh->errorInfo());
		if ($c>0) {
			$comid = $dbh->lastInsertId();
			$rst = $dbh->query("SELECT * FROM comment WHERE comment_id='$comid'");
			$row = $rst->fetch();
			$dt = strtotime('now')-strtotime($row['add_time']);
			if ($dt==0) {
				$tim = '刚刚';
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
			}else if (date('Y',strtotime($row['add_time']))==date('Y')) {
				$tim = date('n月j日',strtotime($row['add_time']));
			}else{
				$tim = date('Y年n月j日',strtotime($row['add_time']));
			}
			$row = $dbh->query("SELECT headimg FROM user WHERE user_id='$uid'")->fetch();
			$uimg = '../upload/user/'.$row[0];
			$arr = array('status'=>200,'comid'=>$comid,'tim'=>$tim,'usr'=>$usr,'say'=>$say,'uimg'=>$uimg);
			$sql = "INSERT INTO com_log (comment_id,user_id,detail) VALUES ('$comid','$uid','回复评论')";
			$dbh->query($sql);
		}else{
			$arr['status'] = '回复评论失败';
		}
		break;
	case 'content':
		$c = $dbh->exec("INSERT INTO comment (user_id,content_id,say) VALUES ('$uid','$to_id','$say')");
		// var_dump($dbh->errorInfo());
		if ($c>0) {
			$comid = $dbh->lastInsertId();
			$rst = $dbh->query("SELECT * FROM comment WHERE comment_id='$comid'");
			$row = $rst->fetch();
			$dt = strtotime('now')-strtotime($row['add_time']);
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
			}else if (date('Y',strtotime($row['add_time']))==date('Y')) {
				$tim = date('n月j日',strtotime($row['add_time']));
			}else{
				$tim = date('Y年n月j日',strtotime($row['add_time']));
			}
			//评论插入comment表成功，在content表更新评论数量
			$rst = $dbh->query("SELECT COUNT(*) FROM comment WHERE content_id='$cid'");
			$comN = $rst->fetchColumn();

			$row = $dbh->query("SELECT headimg FROM user WHERE user_id='$uid'")->fetch();
			$uimg = '../upload/user/'.$row[0];
			$arr = array('status'=>200,'comid'=>$comid,'comN'=>$comN,'usr'=>$usr,'say'=>$say,'tim'=>$tim,'uimg'=>$uimg);
			$sql = "INSERT INTO com_log (comment_id,user_id,detail) VALUES ('$comid','$uid','评论图文')";
			$dbh->query($sql);
		}else{
			$arr['status'] = '评论图文失败';
		}
		break;
	case 'activity':
		$c = $dbh->exec("INSERT INTO comment (user_id,activity_id,say) VALUES ('$uid','$to_id','$say')");
		if ($c>0) {
			$comid = $dbh->lastInsertId();
			$rst = $dbh->query("SELECT * FROM comment WHERE comment_id='$comid'");
			$row = $rst->fetch();
			$dt = strtotime('now')-strtotime($row['add_time']);
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
			}else if (date('Y',strtotime($row['add_time']))==date('Y')) {
				$tim = date('n月j日',strtotime($row['add_time']));
			}else{
				$tim = date('Y年n月j日',strtotime($row['add_time']));
			}
			//评论插入comment表成功，在content表更新评论数量
			$rst = $dbh->query("SELECT COUNT(*) FROM comment WHERE content_id='$cid'");
			$comN = $rst->fetchColumn();

			$row = $dbh->query("SELECT headimg FROM user WHERE user_id='$uid'")->fetch();
			$uimg = '../upload/user/'.$row[0];
			$arr = array('status'=>200,'comid'=>$comid,'comN'=>$comN,'usr'=>$usr,'say'=>$say,'tim'=>$tim,'uimg'=>$uimg);
			$sql = "INSERT INTO com_log (comment_id,user_id,detail) VALUES ('$comid','$uid','评论图文')";
			$dbh->query($sql);
		}else{
			$arr['status'] = '评论图文失败';
		}
		break;
	default:
		# code...
		break;
}
$str = json_encode($arr);
echo $str;

$dbh->exec("SET FOREIGN_KEY_CHECKS=1");
$dbh = NULL;
?>