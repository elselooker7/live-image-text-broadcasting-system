<?php
@session_start();
@include '../init.php';
define('UPLOAD_PATH', dirname(dirname(__FILE__))."/upload/user/");

$uid = $_SESSION['uid'];
$imgs = $_FILES['img'];
$uname = $_SESSION['uname'];
// echo '<pre />';
// var_dump($imgs);
// $i = count($imgs["name"])-1;

// 步骤：1.删除旧头像；2.获取最近上传的图；3.移动文件；4.存储图片名到数据库
// 删除图文内容中的图
$del_img = "SELECT headimg FROM user WHERE user_id = '$uid'";
$rst_di = $dbh->query($del_img);
$row_di = $rst_di->fetch();
if(!empty($row_di[0])){
	$imgSrc = UPLOAD_PATH.$row_di['headimg'];
	if (is_file($imgSrc)) {
		if (!unlink($imgSrc)) {
			$arr['del_cimg'] = '删除旧头像失败';
		}else{
			$arr['del_cimg'] = '删除旧头像成功';
    	}
	}
}
// 从获取的图中选择最新的
if ($imgs["name"][1] == "") {
	$i = 0;
	$type = $imgs["type"][$i];
	$size = $imgs["size"][$i];
	$error = $imgs["error"][$i];
	$tmp = $imgs["tmp_name"][$i];
	$ori_name = $imgs["name"][$i];
}else{
	$i = 1;
	$type = $imgs["type"][$i];
	$size = $imgs["size"][$i];
	$error = $imgs["error"][$i];
	$tmp = $imgs["tmp_name"][$i];
	$ori_name = $imgs["name"][$i];
}
if (!empty($tmp)) {
	if ($size>0&&$type == "image/gif"||$type == "image/jpeg"||$type == "image/pjpeg"||$type == "image/png"&&$size<(2*1024*1024)) {
		if ($error > 0) {
			echo "错误：".$error."<br>";
		}else{
			$ext = end(explode('.', $ori_name));
			$headImgName = 'user_'.date("ymdHis").'.'.$ext;
			// 压缩图片
			$dst_w = 500;
			$dst_h = 500;
			$img = imagecreatefromstring(file_get_contents($tmp));
			$w = imagesx($img);
			$h = imagesy($img);
			$new = imagecreatetruecolor($dst_w,$dst_h);
			// imagecopyresampled($new, $img, 0, 0, 0, 0, $dst_w, $dst_h, $w, $h);
			imagejpeg($img,UPLOAD_PATH.$headImgName,75);
			imagedestroy($img);

			// move_uploaded_file($tmp, UPLOAD_PATH.$headImgName);
			$sql = "UPDATE user SET headimg = '$headImgName' WHERE user_id = '$uid'";
			$count = $dbh->exec($sql);
			if ($count>0) {
				echo "<script>alert('头像修改成功！');window.history.go(-1);</script>";
				$sql = "INSERT INTO user_log (user_id,detail) VALUES ('$uid','修改头像')";
				$dbh->query($sql);
			}
		}
	}else{
		echo "<script>alert('图片过大或格式不正确');</script>";
	}
}
else {
	echo "<script>alert('头像未修改！');</script>";
}
$dbh = NULL;
?>