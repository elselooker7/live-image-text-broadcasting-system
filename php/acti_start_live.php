<?php
@session_start();
@include '../init.php';

if (!isset($_POST['aid'])) {
	$arr = array('status'=>'服务器未收到直播id');
}else{
	$uid = $_SESSION['uid'];
	$usr = $_SESSION['uname'];
	$aid = $_POST['aid'];
	$st =  date('Y-m-d H:i:s',time());
	$et =  date('Y-m-d H:i:s',strtotime("+1 day"));
	// 可以修改活动封图、标题、概要、种类、活动地点
	$sql = "UPDATE activity SET start_time = '$st',end_time = '$et' WHERE activity_id = '$aid'";
	$rst = $dbh->query($sql);
	// var_dump($dbh->errorInfo());
	if ($rst->rowCount()>0) {
		$arr = array('status'=>200,'detail'=>'开启成功');
		$sql = "INSERT INTO log (content,user) VALUES ('开启活动直播($aid)','$usr')";
		$dbh->query($sql);
	}
	else {
		$arr = array('status'=>'开启直播失败');
	}
}
$str = json_encode($arr);
echo $str;

$dbh = NULL;
?>