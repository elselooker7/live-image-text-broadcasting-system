<?php
@session_start();
@include '../init.php';

if (!isset($_POST['aid'])) {
	$arr = array('status'=>'服务器未收到直播id');
}else{
	$uid = $_SESSION['uid'];
	$usr = $_SESSION['uname'];
	$aid = $_POST['aid'];
	$tit = $_POST['tit'];
	$abs = $_POST['abs'];
	$tid = $_POST['tid'];
	// echo $_POST['tid'];
	// $dat = $_POST['dat'];
	// filterEmoji($newCap);
	// 判断是否改变，再update

	// 可以修改活动封图、标题、概要、种类、活动地点
	$sql = "UPDATE activity SET title = '$tit',abstract = '$abs',acti_type_id = '$tid' WHERE activity_id = '$aid'";
	$rst = $dbh->query($sql);
	// var_dump($dbh->errorInfo());
	if ($rst->rowCount()>0) {
		$sql = "SELECT title,abstract,type FROM activity JOIN acti_type ON activity.acti_type_id=acti_type.type_id WHERE activity_id = '$aid'";
		$rst = $dbh->query($sql);
		$row = $rst->fetch();
		$updTit = $row[0];
		$updAbs = $row[1];
		$updTyp = $row[2];
		$arr = array('status'=>200,'updTit'=>$updTit,'updAbs'=>$updAbs,'updTyp'=>$updTyp,'detail'=>'修改成功');
		$sql = "INSERT INTO log (content,user) VALUES ('修改直播主题($aid)','$usr')";
		$dbh->query($sql);
	}
	else {
		$arr = array('status'=>'活动信息未更新。');
	}
}
$str = json_encode($arr);
echo $str;

$dbh = NULL;
?>