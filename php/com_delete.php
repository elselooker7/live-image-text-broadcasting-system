<?php
@session_start();
@include '../init.php';

if (!isset($_POST['comid'])) {
	$arr['status']=200;
}else{
	// $comid = $_POST['comid'];
	// $uid = $_SESSION['uid'];
	// $bro = $_SESSION['uname'];
	// $rst = $dbh->query("SELECT comment_id FROM comment WHERE to_comment_id='$comid'");
	// while (!empty($row = $rst->fetch())) {
	// 	$rid = $row[0];
	// 	$dbh->exec("DELETE FROM comment WHERE to_comment_id=$rid)");
	// 	$rst2 = $dbh->query("SELECT comment_id FROM comment WHERE to_comment_id='$rid'");
	// 	if (!empty($row2 = $rst2->fetch())) {
	// 		# code...
	// 	}
	// }
	// $rst = $dbh->exec("DELETE FROM comment WHERE comment_id = $comid");
	// // var_dump($dbh->errorInfo());
	// $rst = $dbh->exec("DELETE FROM comment WHERE to_comment_id=$comid)");
	// // var_dump($dbh->errorInfo());
	// $sql = "INSERT INTO com_log (comment_id,user_id,detail) VALUES ('$comid','$uid','删除评论')";
	// $dbh->query($sql);
	$arr['status']=200;
}
$str = json_encode($arr);
echo $str;

$dbh = NULL;
?>