<?php
@session_start();
@include '../init.php';
define('UPLOAD_PATH', dirname(dirname(__FILE__))."/upload/content/");

if (!isset($_POST['cid'])) {
	$arr = array('status'=>'php未收到图文id');
}else{
	$cid = $_POST['cid'];
	$uid = $_SESSION['uid'];
	$bro = $_SESSION['uname'];
	$rst = $dbh->exec("DELETE FROM comment WHERE comment_id IN (SELECT comment_id FROM comment WHERE content_id='$cid')");
	$rst = $dbh->exec("DELETE FROM comment WHERE content_id = '$cid'");
	// echo '已删除该图文的评论';
	// 选择图片地址 删除图片
	// $sql = "SELECT img_name FROM content WHERE content_id = '$cid'";
	$sql = "SELECT img_name FROM cont_img WHERE content_id = '$cid'";
	$rst = $dbh->query($sql);
	$row = $rst->fetch();
    while(!empty($row)){
    	$img_name = UPLOAD_PATH.$row[0];
    	if (is_file($img_name)) {
    		if (!unlink($img_name)) {
				$arr['del_img'] = '错误';
    		}else{
				$arr['del_img'] = '成功';
	    		// echo "<br>删除 $img_name 图片成功";ajax不应该出现多个echo，否则会出现parseerror
	    	}
    	}
		$row = $rst->fetch();
    }
	$sql = "DELETE FROM content WHERE content_id = '$cid'";
	$arr['cid'] = $cid;
	$rst = $dbh->exec($sql);
	if ($rst>0) {
		$arr = array('status'=>200,'detail'=>'图文已删除');
		// reply最好也设inContentID,这样好删除
		$sql = "INSERT INTO cont_log (content_id,user_id,detail) VALUES ('$cid','$uid','删除图文')";
		$dbh->query($sql);
	}else{
		// echo "errorCode为： ".$pdo->errorCode(); 
		// var_dump($dbh->errorInfo());
		$arr['status'] = '图文未删除'.$dbh->errorInfo()[2];
	}
}
$str = json_encode($arr);
echo $str;

$dbh = NULL;
?>