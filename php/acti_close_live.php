<?php
@session_start();
@include '../init.php';

if (!isset($_POST['aid'])) {
	$arr = array('status'=>'服务器未收到直播id');
}else{
	$uid = $_SESSION['uid'];
	$usr = $_SESSION['uname'];
	$aid = $_POST['aid'];
	$dat =  date('Y-m-d H:i:s',time());
	// 可以修改活动封图、标题、概要、种类、活动地点
	$sql = "UPDATE activity SET end_time = '$dat' WHERE activity_id = '$aid'";
	$rst = $dbh->query($sql);
	// var_dump($dbh->errorInfo());
	if ($rst->rowCount()>0) {
		$sql = "SELECT end_time FROM activity WHERE activity_id = '$aid'";
		$rst = $dbh->query($sql);
		$row = $rst->fetch();
		$updEt = $row[0];
		$arr = array('status'=>200,'updEt'=>$updEt,'detail'=>'关闭成功');
		$sql = "INSERT INTO log (content,user) VALUES ('关闭活动直播($aid)','$usr')";
		$dbh->query($sql);
	}
	else {
		$arr = array('status'=>'关闭直播失败');
	}
}
$str = json_encode($arr);
echo $str;

$dbh = NULL;
?>