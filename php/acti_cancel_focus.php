<?php
@session_start();
@include '../init.php';

if (isset($_POST['aid'])) {
	$aid = $_POST['aid'];
	$uid = $_SESSION['uid'];
	$sql = "SELECT count(*) FROM acti_focus WHERE activity_id='$aid' AND user_id='$uid'";
	$rst = $dbh->query($sql);
	$row = $rst->fetch();
	if (empty($row[0])) {
		$arr = array('status'=>200);
	}else{
		$sql = "DELETE FROM acti_focus WHERE activity_id='$aid' AND user_id='$uid'";
		$count = $dbh->exec($sql);
		if ($count>0) {
			// 添加关注到数据库成功
			$sql = "SELECT COUNT(*) FROM acti_focus WHERE activity_id='$aid'";
			$rst = $dbh->query($sql);
			$row = $rst->fetch();
			if (empty($row[0])) {
				$focN = 0;
			}else{
				$focN = $row[0];
			}
			$arr = array('status'=>200,'focN'=>$focN);
			$sql = "INSERT INTO acti_log (activity_id,user_id,detail) VALUES ('$aid','$uid',,'取消关注')";
			$dbh->query($sql);
		}
	}
}else{
	$arr = array('status'=>'未收到活动id');
}
$str = json_encode($arr);
echo $str;

$dbh = NULL;
?>