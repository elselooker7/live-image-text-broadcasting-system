<?php
@session_start();
@include '../init.php';

if (!isset($_POST['cid'])) {
	$arr = array('status'=>'php未收到图文id');
}else{
	$cid = $_POST['cid'];
	$newTex = $_POST['newTex'];
	$uid = $_SESSION['uid'];
	$sql = "UPDATE content SET description = '$newTex' WHERE content_id = '$cid'";
	$rst = $dbh->exec($sql);
	if ($rst<1) {
		$arr = array('status'=>200);
	}else{
		$sql = "SELECT description FROM CONTENT WHERE content_id = '$cid'";
		$rst = $dbh->query($sql);
		$row = $rst->fetch();
		$updTex = $row['description'];
		$arr = array('status'=>200,'updTex'=>$updTex,'detail'=>'图文修改成功');

		$sql = "INSERT INTO cont_log (content_id,user_id,detail) VALUES ('$cid','$uid','修改图文描述')";
		$dbh->query($sql);
	}
}
$str = json_encode($arr);
echo $str;

$dbh = NULL;
?>