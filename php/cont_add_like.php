<?php
@session_start();
@include '../init.php';

if (isset($_POST['cid'])) {
	$cid = $_POST['cid'];
	$uid = $_SESSION['uid'];
	$sql = "SELECT count(*) FROM content WHERE content_id='$cid'";
	$rst = $dbh->query($sql);
	$row = $rst->fetch();
	if (empty($row[0])) {
		$arr = array('status'=>'未找到图文内容');
	}else {
		$sql = "INSERT INTO cont_like (user_id,content_id) VALUES ('$uid','$cid')";
		$count = $dbh->exec($sql);
		if ($count>0) {
			// 添加喜欢到数据库成功
			$sql = "SELECT COUNT(*) FROM cont_like WHERE content_id='$cid'";
			$rst = $dbh->query($sql);
			$row = $rst->fetch();
			$likN = $row[0];
			$arr = array('status'=>200,'likN'=>$likN);
			$sql = "INSERT INTO cont_log (content_id,user_id,detail) VALUES ('$cid','$uid','添加喜欢成功')";
			$dbh->query($sql);
		}else{
			$arr = array('status'=>'添加喜欢失败');
		}
	}
}else{
	$arr = array('status'=>'未收到图文id');
}
$str = json_encode($arr);
echo $str;

$dbh = NULL;
?>