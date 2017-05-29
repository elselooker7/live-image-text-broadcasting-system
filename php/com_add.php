<?php
@session_start();
@include '../init.php';

if (isset($_POST['cid'])) {
	$usr = isset($_SESSION['uname'])?$_SESSION['uname']:'游客';
	$uid = isset($_SESSION['uid'])?$_SESSION['uid']:0;
	
	// $aid = $_POST['aid'];
	$cid = $_POST['cid'];
	$say = $_POST['say'];
	$sql = "SELECT count(*) FROM content WHERE content_id='$cid'";
	$rst = $dbh->query($sql);
	$row = $rst->fetch();
	if (empty($row[0])) {
		$arr = array('status'=>'未找到图文内容');
	}else{
		// $oldComN = $row['commentNum']; //oldComN = original comment number
		// 向comment表中添加评论
		$sql_add = "INSERT INTO comment (user_id,content_id,say) VALUES ('$uid','$cid','$say')";
		$rst_add = $dbh->query($sql_add);
		if ($rst_add->rowCount()>0) {
			$comid = $dbh->lastInsertId();
			$sql = "SELECT * FROM comment WHERE comment_id='$comid'";
			$rst = $dbh->query($sql);
			$row = $rst->fetch();
			// $comid = $row['comment_id'];
			$tim = $row['add_time'];
			//评论插入comment表成功，在content表更新评论数量
			$sql = "SELECT COUNT(*) FROM comment WHERE content_id='$cid'";
			$rst = $dbh->query($sql);
			$row = $rst->fetch();
			$comN = $row[0];
			$arr = array('status'=>200,'comid'=>$comid,'comN'=>$comN,'usr'=>$usr,'say'=>$say);
			$sql = "INSERT INTO com_log (comment_id,user_id,detail) VALUES ('$comid','$uid','添加评论成功')";
			$dbh->query($sql);
		}else{
			$arr = array('status'=>'评论插入数据库失败');
		}
	}
}else{
	$arr = array('status'=>'未收到图文id');
}
$str = json_encode($arr);
echo $str;

$dbh = NULL;
?>