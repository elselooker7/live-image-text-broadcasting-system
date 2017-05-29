<?php
@session_start();
@include '../init.php';

if (isset($_POST['comid'])) {
	$comid = $_POST['comid'];
	$uid = $_SESSION['uid'];
	$sql = "SELECT count(*) FROM comment WHERE comment_id='$comid'";
	$rst = $dbh->query($sql);
	$row = $rst->fetch();
	if (empty($row[0])) {
		$arr = array('status'=>'未找到评论');
	}else {
		$sql = "INSERT INTO com_agree (user_id,comment_id) VALUES ('$uid','$comid')";
		$count = $dbh->exec($sql);
		if ($count>0) {
			$sql = "SELECT COUNT(*) FROM com_agree WHERE comment_id='$comid'";
			$rst = $dbh->query($sql);
			$row = $rst->fetch();
			$agrN = $row[0];
			$arr = array('status'=>200,'agrN'=>$agrN);
			$sql = "INSERT INTO com_log (comment_id,user_id,detail) VALUES ('$comid','$uid','为评论点赞')";
			$dbh->query($sql);
		}else{
			$arr = array('status'=>'为评论点赞失败');
		}
	}
}else{
	$arr = array('status'=>'未收到图文id');
}
$str = json_encode($arr);
echo $str;

$dbh = NULL;
?>