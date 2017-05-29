<?php
@session_start();
@include '../init.php';
define('UPLOAD_PATH_ACTI', dirname(dirname(__FILE__))."/upload/activity/");

$img_input = 'cover';
$type = $_FILES[$img_input]["type"];
$size = $_FILES[$img_input]["size"];
$error = $_FILES[$img_input]["error"];
$tmp = $_FILES[$img_input]["tmp_name"];
$ori_name = $_FILES[$img_input]["name"];

if (!isset($_POST['aid'])) {
	$arr = array('status'=>'服务器未收到直播id');
}else{
	$aid = $_POST['aid'];
	// 删除直播原封图
	$rst = $dbh->query("SELECT cover FROM activity WHERE activity_id = '$aid'");
	$row = $rst->fetch();
	$arr['准备删除封图'] = 'true';
    if(!empty($row[0])){
		// $arr['选择封图和直播文件'] = $row;
		$arr['查到封图'] = 'true';
		$img_name = UPLOAD_PATH_ACTI.$row['cover'];
		if (is_file($img_name)) {
			$arr['在封图路径中找到图片'] = 'true';
			if (!unlink($img_name)) {
				$arr['del_limg'] = '删除直播封图失败';
			}else{
				$arr['del_limg'] = '删除直播封图成功';
	    	}
		}
	}
	$nameArray = explode('.', $ori_name);
	$ext = array_pop($nameArray);
	$time = date("ymdHis");
	$covername = 'cover_'.$time.mt_rand(10,99).'.'.$ext;
	if (move_uploaded_file($tmp, UPLOAD_PATH_ACTI.$covername)) {
		$rst = $dbh->query("UPDATE activity SET cover = '$covername' WHERE activity_id = '$aid'");
		echo "<script>location.replace(document.referrer);</script>";
	}
}
$str = json_encode($arr);
echo $str;

$dbh = NULL;
?>