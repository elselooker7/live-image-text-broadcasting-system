<?php 
// header("Content-Type: text/html; charset=utf-8");

$dsn="mysql:dbname=db_lit;host=localhost";
$db_user='root';
$db_pass='';
try{
$dbh=new PDO($dsn,$db_user,$db_pass);
}catch(PDOException $e){
echo '数据库连接失败'.$e->getMessage();
}
//设置字符编码
$dbh->exec("SET CHARACTER SET utf8");
date_default_timezone_set("Asia/Shanghai");
$dbh->exec("SET NAMES utf8");

?>