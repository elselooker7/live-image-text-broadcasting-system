<?php
@session_start();
@include '../init.php';

$uid = $_SESSION['uid'];
switch (isset($_POST['type'])?$_POST['type']:'') {
	case 'uname':
		$new_uname = $_POST['uname'];
		$arr['status'] =  $new_uname;
		$sql = "UPDATE user SET uname = '$new_uname' WHERE user_id = '$uid'";
		$rst = $dbh->query($sql);
		if ($rst->rowCount()>0) {
			$sql = "SELECT uname FROM user WHERE user_id = '$uid'";
			$rst = $dbh->query($sql);
			$row = $rst->fetch();
			$uname = $row[0];
			$_SESSION['uname'] = $uname;
			$arr = array('status'=>200,'type'=>'uname','new'=>$uname,'detail'=>'修改成功');
			$sql = "INSERT INTO user_log (user_id,detail) VALUES ('$uid','修改名字')";
			$dbh->query($sql);
		}
		else {
			$arr = array('status'=>'名字未修改');
		}
		break;
	case 'school':
		$new_school = $_POST['school'];
		$arr['status'] =  $new_school;
		$row = $dbh->query("SELECT school_id FROM school WHERE school_name = '$new_school'")->fetch();
		if (empty($row[0])) {
			$dbh->query("INSERT INTO school (school_name) VALUES ('$new_school')");
			$schid = $dbh->lastInsertId();
		}else $schid = $row[0];
		$sql = "UPDATE user SET school_id = '$schid' WHERE user_id = '$uid'";
		$rst = $dbh->query($sql);
		if ($rst->rowCount()>0) {
			$sql = "SELECT school_name FROM school JOIN user ON school.school_id=user.school_id WHERE user_id = '$uid'";
			$rst = $dbh->query($sql);
			$row = $rst->fetch();
			$school = $row[0];
			$arr = array('status'=>200,'type'=>'school','new'=>$school,'detail'=>'修改成功');
			$sql = "INSERT INTO user_log (user_id,detail) VALUES ('$uid','修改学校')";
			$dbh->query($sql);
		}
		else {
			$arr = array('status'=>'未修改学校');
		}
		break;
	case 'intro':
		$new_intro = $_POST['intro'];
		$arr['status'] =  $new_intro;
		$sql = "UPDATE user SET introduction = '$new_intro' WHERE user_id = '$uid'";
		$rst = $dbh->query($sql);
		if ($rst->rowCount()>0) {
			$sql = "SELECT introduction FROM user WHERE user_id = '$uid'";
			$rst = $dbh->query($sql);
			$row = $rst->fetch();
			$intro = $row[0];
			$arr = array('status'=>200,'type'=>'intro','new'=>$intro,'detail'=>'修改成功');
			$sql = "INSERT INTO user_log (user_id,detail) VALUES ('$uid','修改简介')";
			$dbh->query($sql);
		}
		else {
			$arr = array('status'=>'未修改简介');
		}
		break;
	default:
		# code...
		break;
}

$str = json_encode($arr);
echo $str;

$dbh = NULL;
?>