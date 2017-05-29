<?php
@session_start();
@include '../init.php';
define('UPLOAD_PATH', dirname(dirname(__FILE__))."/upload/activity/");

$uid = $_SESSION['uid'];
$bro = $_SESSION['uname'];
$tit = $_POST['tit']!=''?$_POST['tit']:"标题";
isset($_POST['abs'])?$_POST['abs']:"";
$abs = nl2br($_POST['abs']);
$tid = $_POST['typ']!=''?$_POST['typ']:1;
$org = $_POST['org']!=''?$_POST['org']:"主办方";
$pla = $_POST['pla']!=''?$_POST['pla']:"地点";
$y = $_POST['yea'];
$m = $_POST['mon'];
$d = $_POST['day'];
$h = $_POST['hou'];
$min = $_POST['min'];
$dat = "{$y}-{$m}-{$d}";
$st = "{$h}-{$min}";
$stime =  date('Y-m-d H:i:s',mktime($h,$min,0,$m,$d,$y));
$etime =  date('Y-m-d H:i:s',mktime($h,$min,0,$m,$d+1,$y));
// $sti = $dat.' '.$st.':00';
// $stime=date("Y-m-d H:i:s",strtotime($sti)?strtotime($sti):strtotime("now"));
$img_input = 'cover';
$type = $_FILES[$img_input]["type"];
$size = $_FILES[$img_input]["size"];
$error = $_FILES[$img_input]["error"];
$tmp = $_FILES[$img_input]["tmp_name"];
$ori_name = $_FILES[$img_input]["name"];
if (!empty($tmp)) {
	if ($size>0&&$type == "image/gif"||$type == "image/jpeg"||$type == "image/pjpeg"||$type == "image/png"&&$size<(20*1024*1024)) {
		if ($error > 0) {
			echo "错误：".$error."<br>";
		}else{
			$nameArray = explode('.', $ori_name);
			$ext = array_pop($nameArray);
			$time = date("ymdHis");
			$covername = 'cover_'.$time.mt_rand(10,99).'.'.$ext;
			// $covername = $n.'_'.$time.'.'.$ext;
			$img = imagecreatefromstring(file_get_contents($tmp));
			//判断图片上传前是否需要旋转 Android拍照orientation属性都为1
			$exif = exif_read_data($tmp);
			// 确定图片目录
			// $folderpath = "upload/activity"
			if (!empty($exif['Orientation'])) {
				// echo '<br>'.$exif['Orientation'].'<br>';
				switch ($exif['Orientation']) {
					case 8:
						$img = imagerotate($img, 90, 0);
						$ok = imagejpeg($img,UPLOAD_PATH.$covername);
						imagedestroy($img);
						break;
					case 6:
						$img = imagerotate($img, -90, 0);
						$ok = imagejpeg($img,UPLOAD_PATH.$covername);
						imagedestroy($img);
						break;
					case 3:
						$img = imagerotate($img, 180, 0);
						$ok = imagejpeg($img,UPLOAD_PATH.$covername);
						imagedestroy($img);
						break;
					default:
						$ok = move_uploaded_file($tmp, UPLOAD_PATH.$covername);
						break;
				}
			}else{
				$ok = move_uploaded_file($tmp, UPLOAD_PATH.$covername);
			}
			// echo $img.'<br>';
			// var_dump($img).'<br>';

			// list($tmp,$ext) = explode('/', $type);
			// mt_rand().'_'.time().'.'.$ext;
			// move_uploaded_file($img, UPLOAD_PATH.$covername
			if (!$ok) {
				imagedestroy($img);
				echo '图片上传失败<br>';
				echo "<a href=javascript:window.history.go(-1)>返回</a>";
				exit(); // 下面的操作将不会进行;
			// if (!($_FILES['imgInput']['type']=='image/gif'||$_FILES['imgInput']['type']=='image/jpeg')) {
			// }
			}else{
				imagedestroy($img);
	// 设置存储路径
				$sql = "INSERT INTO activity (user_id,title,abstract,acti_type_id,cover,organizer,place,start_time,end_time) VALUES ('$uid','$tit','$abs','$tid','$covername','$org','$pla','$stime','$etime')";
				$rst = $dbh->query($sql);
				// var_dump($dbh->errorInfo());
				if ($rst->rowCount()>0) {
					$aid = $dbh->lastInsertId();
	echo "<script>alert('活动直播创建成功！');window.location.href='../activity/activity.php?aid=".$aid."';</script>";
					$sql = "INSERT INTO acti_log (activity_id,user_id,detail) VALUES ('$aid','$uid','创建活动直播成功')";
					$dbh->query($sql);
				}else{
					$sql = "INSERT INTO acti_log (activity_id,user_id,detail) VALUES ('$aid','$uid','创建活动直播失败')";
					$dbh->query($sql);
					echo "<script>alert('很不幸，直播创建失败');window.location = 'index.php';</script>";
					// echo "未知错误<br>";
					// echo "<a href=javascript:window.history.go(-1)>返回</a>";
					exit(); // 下面的操作将不会进行;
				}
			}
		}
	}else{
		echo "请选择正确的图片格式";
	}
}else{
	echo "请选择一张图片作为封图";
}


$dbh = NULL;
?>