<?php
@session_start();
@include '../init.php';

if (isset($_POST['on_id'])) {
	if (isset($_SESSION['uid'])) {
		$uid = $_SESSION['uid'];
	}else {
		$usr = '游客';
		$uid = 0;
	}
	$reason = $_POST['reason'];
	$on_type = $_POST['on_type'];//content或comment
	$on_id = $_POST['on_id'];
	$sql = "SELECT count(*) FROM $on_type WHERE ".$on_type."_id='$on_id'";
	$rst = $dbh->query($sql);
	$row = $rst->fetch();
	if (empty($row[0])) {
		$arr['status']='该内容已删除';
	}else{
		$sql_add = "INSERT INTO report (user_id,reason,on_type,on_id) VALUES ('$uid','$reason','$on_type','$on_id')";
		$rst_add = $dbh->exec($sql_add);
		if ($rst_add>0) {
			$arr = array('status'=>200);
			$sql = "INSERT INTO user_log (user_id,detail) VALUES ('$uid','举报$on_type(on_id)')";
			$dbh->query($sql);
		}else{
			$arr = array('status'=>'举报失败');
		}
	}
}else{
	$arr = array('status'=>'未收到举报内容id');
}
$str = json_encode($arr);
echo $str;

$dbh = NULL;
?>