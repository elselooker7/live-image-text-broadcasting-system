<?php
@session_start();
$page = $_POST['page'];

$_SESSION['page'] = $page;
$arr['page'] = $page;

$str = json_encode($arr);
echo $str;
?>