<?php 
@session_start();
@include '../init.php';

$logname = $_POST['logname'];
$password =  $_POST['password'];
$utype = $_POST['utype'];
$school = $_POST['school'];
$s_q =  $_POST['s_q'];
$s_a = $_POST['s_a'];

$sql = "SELECT user_id FROM user WHERE logname='$logname'";
$rst = $dbh->query($sql);
$row = $rst->fetch();
if (!empty($row[0])) {
	$arr['status'] = $logname.'用户名已存在'.$row[0];
}else{
	$rst = $dbh->query("SELECT school_id FROM school WHERE school_name='$school'");
	if (!empty($row=$rst->fetch())) {
		$schid = $row[0];
	}else{
		$sql_sch = "INSERT INTO school (school_name) VALUES ('$school')";
		$dbh->exec($sql_sch);
		$schid = $dbh->lastInsertId();
	}
	$arr['schid'] = $schid;
	$head = 'default'.mt_rand(1,4).'.jpg';
	$sql_sq = "INSERT INTO security_question (question) VALUES ('$s_q')";
	$dbh->exec($sql_sq);
	$sqid = $dbh->lastInsertId();
	$arr['sqid'] = $sqid;
	$lt =  date('Y-m-d H:i:s',time());
	$sql = "INSERT INTO user (logname,uname,password,type,headimg,school_id,security_question_id,security_answer,lastlogin_time) VALUES ('$logname','$logname','$password','$utype','$head','$schid','$sqid','$s_a','$lt')";
	$rst = $dbh->exec($sql);
	// 判断影响行数，验证是否插入新用户
	if ($rst>0) {
		$uid = $dbh->lastInsertId();
		$arr['status'] = 200;
		$_SESSION['uid'] = $uid;
		$_SESSION['logname'] = $logname;
		$_SESSION['uname'] = $logname;
		$_SESSION['utype'] = $utype;
		$arr['type'] = $_SESSION['utype'];
		$sql = "INSERT INTO user_log (user_id,detail) VALUES ('$uid','用户注册')";
		$dbh->query($sql);
	}else{
		$arr['status'] = '注册失败';
	}
}
$str = json_encode($arr);
echo $str;

$dbh = NULL;
?>