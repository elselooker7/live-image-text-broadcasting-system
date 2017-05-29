<?php
@session_start();
@include '../init.php';

if (isset($_POST['cid'])) {
	$cid = $_POST['cid'];
	$uid = $_SESSION['uid'];
	$sql = "SELECT count(*) FROM cont_like WHERE content_id='$cid' AND user_id='$uid'";
	$rst = $dbh->query($sql);
	$row = $rst->fetch();
	if (empty($row[0])) {
		$arr = array('status'=>200);
	}else {
		$sql = "DELETE FROM cont_like WHERE content_id='$cid' AND user_id='$uid'";
		// $sql = "UPDATE cont_like SET do_like = 0 WHERE content_id = '$cid'";
		// $sql = "DELETE FROM likecont WHERE fromUserId = '$uid'";
		// $rst = $dbh->query($sql);
		$count = $dbh->exec($sql);
		if ($count>0) {
			// 添加喜欢到数据库成功
			$sql = "SELECT COUNT(*) FROM cont_like WHERE content_id='$cid'";
			$rst = $dbh->query($sql);
			$row = $rst->fetch();
			$likN = $row[0];
			$arr = array('status'=>200,'likN'=>$likN);
			$sql = "INSERT INTO cont_log (content_id,user_id,detail) VALUES ('$cid','$uid','取消喜欢成功')";
			$dbh->query($sql);
		}else{
			$arr = array('status'=>'删除喜欢失败');
		}
	}
}else{
	$arr = array('status'=>'未收到图文id');
}
$str = json_encode($arr);
echo $str;

$dbh = NULL;
?>