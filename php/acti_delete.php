<?php
@session_start();
@include '../init.php';
define('UPLOAD_PATH_ACTI', dirname(dirname(__FILE__))."/upload/activity/");
define('UPLOAD_PATH_CONT', dirname(dirname(__FILE__))."/upload/content/");
define('PATH_ACTI', dirname(dirname(__FILE__))."/activity/");

if (!isset($_POST['aid'])) {
	$arr = array('status'=>'1php未收到直播id');
}else{
	$aid = $_POST['aid'];
	$uid = $_SESSION['uid'];
	$bro = $_SESSION['uname'];
	// 思路：先删除内容图片、封图图片、活动页php，再数据库删除activity，系统自动级联删除content和comment。
	// 删除图文content表中的数据
	$sql = "SELECT content_id FROM content WHERE activity_id = '$aid'";
	$rst = $dbh->query($sql);
	$arr['in'] = '成功连上php';
	// 删除评论comment表中的数据
	while ($row = $rst->fetch()) {
		$cid = $row[0];
		// 手动删除评论
		$rst_com = $dbh->query("SELECT comment_id FROM comment WHERE content_id = '$cid'");
		// 删除回复
		while ($row_com = $rst_com->fetch()) {
			$comid = $row[0];
			$rst_dr = $dbh->exec("DELETE FROM comment WHERE to_comment_id = '$comid'");
			if ($rst_dr>0) {
				$arr['删除回复成功'] = true;
			}else $arr['没有回复需要删除'] = true;
		}
		$del_com = "DELETE FROM comment WHERE content_id = '$cid'";
		$rst_dcom = $dbh->exec($del_com);
		if ($rst_dcom>0) {
			$arr['删除评论成功'] = true;
		}else $arr['没有评论需要删除'] = true;
		// 删除图文内容中的图
		$rst_di = $dbh->query("SELECT img_name FROM cont_img WHERE content_id = '$cid'");
		$row_di = $rst_di->fetch();
	    while(!empty($row_di)){
	    	$img_name = UPLOAD_PATH_CONT.$row_di['img_name'];
	    	if (is_file($img_name)) {
	    		if (!unlink($img_name)) {
					$arr['del_cimg'] = '删除内容图片失败，原因'.$dbh->errorInfo()[2];
					exit();
	    		}else{
	    			$arr['del_cimg'] = '删除内容图片成功';
		    	}
	    	}
			$row_di = $rst_di->fetch();
	    }
	}
	// 删除图文
	$rst_dc = $dbh->exec("DELETE FROM content WHERE activity_id = '$aid'");
	if ($rst_dc>0) {
		$arr['删除图文成功'] = true;
	}else $arr['没有图文需要删除'] = true;

	// 删除直播的封图和直播也
	$rst = $dbh->query("SELECT cover FROM activity WHERE activity_id = '$aid'");
	// var_dump($dbh->errorInfo());
	$row = $rst->fetch();
	$arr['准备删除封图'] = 'true';
    if(!empty($row[0])){
		$arr['查到封图'] = 'true';
    	$img_name = UPLOAD_PATH_ACTI.$row['cover'];
		$arr['封图路径'] = $img_name;
    	if (is_file($img_name)) {
			$arr['在封图路径中找到图片'] = 'true';
    		if (!unlink($img_name)) {
				$arr['del_limg'] = '删除直播封图失败，原因'.$dbh->errorInfo()[2];
				exit();
    		}else{
    			$arr['del_limg'] = '删除直播封图成功';
	    	}
    	}
	    // 删除直播
	    $c = $dbh->query("SELECT COUNT(*) FROM activity WHERE activity_id = '$aid'")->fetchColumn();
	    if ($c>0) {
			$sql = "DELETE FROM activity WHERE activity_id = '$aid'";
			$rst = $dbh->exec($sql);
			$arr['status'] = 200;
			if ($rst>0) {
				$arr['status'] = 200;
			}else $arr['status'] = "活动未成功删除，原因".$dbh->errorInfo()[2];
	    }else $arr['status'] = 200;
    }
}
$str = json_encode($arr);
echo $str;

$dbh = NULL;
?>