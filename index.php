<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>图文直播&middot;在校园</title>
	<link rel="icon" href="image/icon.png" type="image/x-icon"/>
  <meta name="Keywords" content="图文直播,校园活动,直播">  
  <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
  <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="css/index.css">
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <link rel="stylesheet" type="text/css" href="css/main.css">
	<script src="js/public.js"></script>
</head>
<body>
<!-- 连接数据库 -->
<?php
  @session_start();
  @include 'init.php';
  // include 'fn.php'
function isLogged(){
  // echo "<script>alert('判断是否登录');</script>";
  // echo "<script>console.log('userId设置情况为：".isset($_SESSION['uid'])."');</script>";
  if (isset($_SESSION['uid'])&&$_SESSION['uid']!="") {
	return true;
  }else return false;
}
function isPublisher(){
  if (isset($_SESSION['utype'])&&$_SESSION['utype']=="2") {
	return true;
  }else return false;
}
	if (isLogged()) {
		$uid = $_SESSION['uid'];
	}else $uid = '';
  // 用户登录并注册session
  // if (isset($_POST['logout'])) {
  //   $_SESSION['uid']="";
  // }
?>
<input type="hidden" id="usr" value="<?php echo $uid ?>">
<input type="hidden" id="pag" value="<?php echo isset($_SESSION['page'])?$_SESSION['page']:0 ?>">
<!-- 顶部菜单 -->
<header>
	<div id="nav_main">
		<span class="icon-search search"></span><span class="icon-home logo"></span><span class="icon-user-circle-o user"></span>
		<div class="imgWrap logo_center"><img src="image/logo-w9.png" alt=""></div>
	</div>
	<table class="nav_me nav" id="fix_nav_uinfo">
		<tr>
			<?php if (isPublisher()) {?><td><a class="pub"><i class="icon-pictures"></i></a></td><?php } ?>
			<td><a class="act"><i class="icon-face"></i></a></td>
			<td><a class="mes"><i class="icon-envelope-open-o"></i></a></td>
			<td><a class="about_me"><i class="icon-user-o"></i></a></td>
		</tr>
	</table>
	<div class="sec_message nav" id="fix_nav_mes">
	    <table class="nav_me">
	      <tr>
	        <td><a class="nav_com on">评论</a></td>
	        <td><a class="nav_agr">点赞</a></td>
	        <td><a class="nav_sys">系统消息</a></td>
	      </tr>
	    </table>
	</div>
	<div class="wp_uinfo nav" id="fix_nav_lab" data-name="">
		<div class="wp_head">
			<label></label><span class="count"><span>20</span><i class="icon-angle-up"></i></span>
		</div>
	</div>
	<!-- <div>这里测试</div> -->
</header>

<!-- 个人主页 -->
<?php 
$utype;
if (isLogged()): 
  $uid = $_SESSION['uid'];
  $utype = $_SESSION['utype'];
  $logname = $_SESSION['logname'];
  $uname = $_SESSION['uname'];
  $sql = "SELECT headimg FROM user WHERE user_id='$uid'";
  $rst = $dbh->query($sql);
  $row = $rst->fetch();
  $headimg = 'upload/user/'.$row[0];
?>
  <section id="userInfo">
	<!-- 个人信息 -->
	  <div class="info">
		<a class="imgWrap headImg">
		  <img src="<?php echo $headimg ?>" alt="">
		</a>
		<span>
		  <div class="uname"><?php echo $uname?><span><i class="icon-user2"> </i><?php echo isPublisher()?'直播君':'观众'?></span></div>
		  <div class="usrname">登录名：<span><?php echo $logname?></span>
		  </div>
		</span>
		<table class="nav_me" id="nav_uinfo">
			<tr>
				<?php if (isPublisher()) {?><td><a class="pub">直播<i class="icon-pictures"></i></a></td><?php } ?>
				<td><a class="act">动态<i class="icon-face"></i></a></td>
				<td><a class="mes">消息<i class="icon-envelope-open-o"></i></a></td>
				<td><a class="about_me">关于我<i class="icon-user-o"></i></a></td>
			</tr>
		</table>
		<!-- <div class="nav_me">
			<ul>
				<li>发布</li><li>动态</li><li>消息</li><li><span class="icon-user-o"></span><span>关于我</span></li>
			</ul>
		</div> -->
	  </div>

		<!--修改头像-->
		<form id="changeHeadImg" action="php/user_edit_headImg.php" method="post" enctype="multipart/form-data">
                <input type="file" name="img[]" accept="image/*" value="" class="upload_input" id="changeHead">
			<!-- <input capture="camera" id="inputImg" name="img[]" accept="image/*" class="upload_input" type="file"> -->
			<input id="reInputImg" name="img[]" accept="image/*" class="upload_input" type="file">
			<div id="showImg">
				<i class="icon-cross close"></i>
				<div class="row1"><div class="imgWrap"></div></div>
				<div class="row2">
					<a class="btn reInput">重新上传</a>
					<input type="submit" class="btn confirm" value="确认">
				</div>
			</div>
		</form>
  <!-- 关于我 -->
  <!-- 身份：发布者；学校，个人简介，注册时间；修改密码，修改安全问题 -->
  <div class="sec sec_message">
    <table class="nav_me" id="nav_message">
      <tr>
        <td><a id="navCom" class="on">评论</a></td>
        <td><a id="navAgr">点赞</a></td>
        <td><a id="navSys">系统消息</a></td>
      </tr>
    </table>
    <!-- 收到的评论 -->
    <div id="com" class="sec">
      <!-- <div class="wp_message">
        <div class="who">
          <div class="imgWrap"><img src="image/default1.jpg" alt=""></div>
          <div><div class="name">哈哈你</div><span class="time">去年</span>评论了你</div>
          <a class="btn btn_reply">回复</a>
        </div>
        <div class="comment">直播"***"中的图文内容"***"因"***"被举报删除</div>
        <div class="say">提醒：系统提醒；评论我的；回复；关注；赞</div>
      </div> -->
    </div>
    <!-- 收到的点赞 -->
    <div id="agr" class="sec u_act">
      <!-- <div class="wp_message">
        <div class="who">
          <div class="imgWrap"><img src="image/default1.jpg" alt=""></div>
          <div><div class="name">哈哈你</div><span class="time">去年</span>赞了你</div>
        </div>
        <div class="say">提醒：系统提醒；评论我的；回复；关注；赞</div>
      </div> -->
    </div>
    <!-- 收到的系统消息 -->
    <div id="sys" class="sec u_act">
      <!-- <div class="wp_message">
        <div class="who">
          <div class="imgWrap"><img src="image/default1.jpg" alt=""></div>
          <div><div class="name">管理员</div><span class="time">去年</span>删了</div>
        </div>
        <div class="say">活动标题</div>
      </div> -->
    </div>
  </div>
	<div class="sec sec_uinfo">
			<?php
			$rst = $dbh->query("SELECT school.school_name AS school,user.introduction AS intro FROM user JOIN school ON user.school_id=school.school_id WHERE user.user_id = $uid");
			// var_dump($dbh->errorInfo());
			$row = $rst->fetch();
			?>
		  <i class="icon-edit" id="editInfo"></i>
				<div class="edit_list">
					<ul>
						<li><a class="change_headImg">头像</a>
						</li>
						<li><a class="change_name">名字</a></li>
						<li><a class="change_school">学校</a></li>
						<li><a class="change_intro">简介</a></li>
						<li><a class="change_psw">密码</a></li>
					</ul>
				</div>
		<div class="wp_uinfo">
			<label>所在学校</label>
			<p class="school"><?php echo $row['school']?></p>
		</div>
		<div class="wp_uinfo">
			<label>个人简介</label>
			<p class="intro"><?php echo $row['intro']?></p>
		</div>
	</div>
	<div class="sec sec_action">
		<div class="wp_uinfo">
			<?php
			$sql = "SELECT COUNT(*) FROM comment WHERE user_id = $uid";
			$rst = $dbh->query($sql);
			$num = $rst->fetchColumn();
			?>
			<div class="wp_head" id="toggle_comm">
				<label>我的评论</label><span class="count"><span><?php echo $num ?></span><i class="icon-angle-down"></i></span>
			</div>
			<div class="wp_list fold">
		      <!-- <div class="wp_message">
		        <div class="comment"><div>直播"***"中的图文内容"***"因"***"被举报删除</div><span class="time">去年</span></div>
		        <div class="who acti">
		          <div class="imgWrap"><img src="image/default1.jpg" alt=""></div>
		          <div><div class="name">活动：标题</div><div class="say">活动概述</div></div>
		        </div>
		      </div> -->
			</div>
	    </div>
		<div class="wp_uinfo">
			<?php
			$sql = "SELECT COUNT(*) FROM acti_focus WHERE user_id = $uid";
			$rst = $dbh->query($sql);
			// var_dump($dbh->errorInfo());
			$num = $rst->fetchColumn();
			?>
			<div class="wp_head" id="toggle_focu">
				<label>我的关注</label><span class="count"><span><?php echo $num ?></span><i class="icon-angle-down"></i></span>
			</div>
			<div class="wp_list fold">
				<!-- <div class="list_acti">
					<div><div class="imgWrap"><img src="image/default2.jpg" alt=""></div></div>
					<a class="tit_abs"><div>活动直播标题活动直播标题活动直播标题活动直播标题</div><div>概述概述概述概述概述概述概述</div></a>
					<div class="tim"><div>2月9日</div><i class="icon-bin del_acti"></i></div>
				</div> -->
			</div>
		</div>
		<div class="wp_uinfo">
			<?php
			$sql = "SELECT COUNT(*) FROM cont_like WHERE user_id = $uid";
			$rst = $dbh->query($sql);
			$num = $rst->fetchColumn();
			// var_dump($dbh->errorInfo());
			?>
			<div class="wp_head" id="toggle_like">
				<label>我的喜欢</label><span class="count"><span><?php echo $num ?></span><i class="icon-angle-down"></i></span>
			</div>
			<div class="wp_list fold">
		      <!-- <div class="wp_message like">
		        <div class="who acti">
		          <div class="imgWrap"><img src="upload/user/default1.jpg" alt=""></div>
		          <div><div class="name">活动：标题</div><div class="say">活动概述</div></div>
		          <span class="time">去年</span>
		        </div>
		      </div> -->
			</div>
	    </div>
	</div>
	<?php if (isPublisher()) {?>
		<div class="sec sec_publish">
			<?php
			$sql = "SELECT COUNT(*) FROM activity WHERE user_id = $uid";
			$rst = $dbh->query($sql);
			$num = $rst->fetchColumn();  //如果第一列count(*)的值大于0，表示有结果。
			?>
			<div class="wp_uinfo">
				<div class="wp_head" id="toggle_acti">
					<label>我的直播</label><span class="count"><span><?php echo $num ?></span><i class="icon-angle-up"></i></span>
				</div>
				<div class="wp_list unfold">
					<!-- <div class="list_acti">
						<div><div class="imgWrap"><img src="image/default2.jpg" alt=""></div></div>
						<a class="tit_abs"><div>活动直播标题活动直播标题活动直播标题活动直播标题</div><div>概述概述概述概述概述概述概述</div></a>
						<div class="tim"><div>2月9日</div><i class="icon-bin del_acti"></i></div>
					</div> -->
				</div>
			</div>
		</div>
	<?php } ?>
		<div class="action"></div>
		<div class="message"></div>
		<div class="about_me"></div>
	  <div><a class="btn btn_logout" id="logout">登出</a></div>
  </section>
	<!-- 编辑个人信息 -->
	<div class="pop_edit edit_user" id="editUser">
		<div class="edit_sec edit_uname">
			<i class="icon-cross close"></i>
			<input type="text" name="uname" value="" autofocus="autofocus">
			<div><a class="btn submitName">确认</a></div>
		</div>
		<div class="edit_sec edit_school">
			<i class="icon-cross close"></i>
			<input type="text" name="school" value="" autofocus="autofocus">
			<div><a class="btn submitSchool">确认</a></div>
		</div>
		<div class="edit_sec edit_intro">
			<i class="icon-cross close"></i>
			<textarea name="intro" rows="3" autofocus="autofocus"></textarea>
			<div><a class="btn submitIntro">确认</a></div>
		</div>
	</div>
<?php else: ?>
<!-- 登录注册弹出框-->
<div class="reg_log" id="sep_reg_log">
    <i class="icon-cross close"></i>
    <div class="inner">
      <div>
        <div class="wrap_input">
          <input type="text" name="uname" autofocus="autofocus" autocapitalize="off" placeholder="用户名">
        </div>
        <div class="wrap_input">
          <input type="password" name="pwd" autocapitalize="off" placeholder="密码">
        </div>
        <div class="prompt log_p"></div>
        <div class="wrap_btn">
          <a class="btn active log">登录</a><a class="btn reg_trigger">注册</a>
        </div>
      </div>
      <div>
        <div class="wrap_input">
          <input type="text" name="rname" placeholder="用户名">
        </div>
        <div class="wrap_input">
          <input type="password" name="rpwd" placeholder="密码">
        </div>
        <div class="wrap_input choose_utype">
	        我要当：
	        <select name="rtype" id="">
	        	<option value="1">观众</option>
	        	<option value="2">直播君</option>
	        </select>
          <!-- <div><input type="radio" name="rtype" class="rtype" value="1" checked="checked"> 观众</div>
          <div><input type="radio" name="rtype" class="rtype" value="2"> 直播君</div> -->
        </div>
        <div class="wrap_input">
          <input type="text" name="school" placeholder="学校">
        </div>
        <div class="wrap_input">
          <input type="text" name="s_q" placeholder="安全问题">
        </div>
        <div class="wrap_input">
          <input type="text" name="s_a" placeholder="安全问题答案(用于修改密码)">
        </div>
        <div class="prompt reg_p"></div>
        <div class="wrap_btn">
          <a class="btn log_trigger">登录</a><a class="btn active reg">注册</a>
        </div>
      </div>
    </div>
</div>
<?php endif ?>

<!-- 推荐页面 -->
<section id="main">
  <?php
	// 创建显示列表函数
	function fnListLive($id,$cover,$state,$title,$abstract,$place,$start_time,$wrapType){
	  $cover = 'upload/activity/'.$cover;
	  switch ($wrapType) {
		case 'wrap11':
		  ?>
			<a href="activity/activity.php?aid=<?php echo $id?>" class="wrap11">
			  <input type="hidden" value="<?php echo $id?>">
			  <div class="imgWrap">
				<!-- <img src="upload/5.jpg"> -->
				<img src="<?php echo $cover?>"><?php 
			  if($state == 1){echo '<span class="onAir">正在直播</span>';
			  }else if ($state == 0) {
			  	echo '<span class="onAir">即将直播</span>';
			  }
			  ?>
			  </div>
			  <div class="title"><?php echo $title?></div>
			  <div class="abstract">
			  <?php echo $abstract?></div>
			  <div class="timeSpace">
				<span class="time"><i class="icon-clock"></i>
				<?php echo $start_time?></span>
				<span class="space">
				<?php echo $place?></span>
			  </div>
			</a>
		  <?php
		  break;
		case 'wrap21':
		  ?>
			<a href="activity/activity.php?aid=<?php echo $id?>" class="wrap21">
			  <input type="hidden" value="<?php echo $id?>">
			  <div class="imgWrap">
				<img src="<?php echo $cover?>">
					<?php 
				  if($state == 1){echo '<span class="onAir">正在直播</span>';
				  }else if ($state == 0) {
				  	echo '<span class="onAir">即将直播</span>';
				  }?>
			  </div>
			  <div class="wrapR">
				<div class="wrap">
				  <div class="title"><?php echo $title?></div>
				  <div class="timeSpace">
					<span class="space"><?php echo $place?></span>
					<span class="time"><i class="icon-clock"></i><?php echo $start_time?></span>
				  </div>
				</div>
			  </div>
			</a>
		  <?php
		  break;
		case 'wrap12':
		  ?>
			<div class="wrap12">
			  <input type="hidden" value="<?php echo $id?>">
			  <div class="imgWrap">
				<img src="<?php echo $cover?>" alt="">
			  </div>
			  <div class="wrapR">
				<div class="wrap">
				<?php 
			  if($state == 1){echo '<div class="onAir">正在直播</div>';
			  }else if ($state == 0) {
			  	echo '<div class="onAir">即将直播</div>';
			  }?>
				  <div class="title"><?php echo $title?></div>
				  <div class="timeSpace">
					<span class="time"><i class="icon-clock"></i><?php echo $start_time?></span>
					<span class="space"><?php echo $place?></span>
				  </div>
				</div>
			  </div>
			</div>
		  <?php
		  break;
	  }
	}
	$sql = "SELECT COUNT(*) as total FROM activity";
	$result = $dbh->query($sql);
	$row = $result->fetch();
	if(!empty($row)){
	  $total = $row['total'];
	}else{
	  echo 'LIVE数据表总数获取失败。';
	}
	$sql = "SELECT * FROM activity ORDER BY activity_id DESC";
	$rst = $dbh->query($sql);
	$row = $rst->fetch();
	//写出数据记录
	if(!empty($row)){
	  $no = 1;
	  $id = $row['activity_id'];
	  $title = $row['title'];
	  $abstract = $row['abstract'];
	  // date('H:i',strtotime($row['creTime']))
	  // echo date('Y',strtotime('now'));
	  $place = $row['place'];
	  $cover = $row['cover'];
	  $st = strtotime($row['start_time']);
	  if (date('Y',$st)==date('Y',strtotime('now'))) {
		$start_time = date('n月j日 H:i',$st);
	  }else{
		$start_time = date('Y年n月j日',$st);
	  }
	  	$now = strtotime('now');
	  	if ($now<$st) {
	  		$state = 0;
	  	}else if ($row['end_time']!=null) {
			$et = strtotime($row['end_time']);
			if ($now>$et) {
				$state = 2;
			}else{
				$state = 1;
			}
	  	}else{
	  		$state = 2;
	  	}
		fnListLive($id,$cover,$state,$title,$abstract,$place,$start_time,'wrap11');
	}else{
	  echo '没有活动。';
	}

  $verN = 12;
  $row = $rst->fetch();
  while (!empty($row)&&$no<=$verN) {
	$no++;
	  $id = $row['activity_id'];
	  $title = $row['title'];
	  $abstract = $row['abstract'];
	  $place = $row['place'];
	  $cover = $row['cover'];
	  $st = strtotime($row['start_time']);
	  if (date('Y',$st)==date('Y',strtotime('now'))) {
		$start_time = date('n月j日 H:i',$st);
	  }else{
		$start_time = date('Y年n月j日',$st);
	  }
	  	$now = strtotime('now');
	  	if ($now<$st) {
	  		$state = 0;
	  	}else if ($row['end_time']!=null) {
			$et = strtotime($row['end_time']);
			if ($now>$et) {
				$state = 2;
			}else{
				$state = 1;
			}
	  	}else{
	  		$state = 2;
	  	}
	if ($no<=3) {
		// 前3个用wrap21
	  fnListLive($id,$cover,$state,$title,$abstract,$place,$start_time,'wrap21');
	}
	else{
	  // 生成随机数选择显示样式
	  if(mt_rand(1,4)<4){
		  // 选择wrap21
		fnListLive($id,$cover,$state,$title,$abstract,$place,$start_time,'wrap21');
	  }else{
		  // 选择wrap11
		  fnListLive($id,$cover,$state,$title,$abstract,$place,$start_time,'wrap11');
		}
	}
	$row = $rst->fetch();
  }
  if (!empty($row)) {
	$no++;
	  $id = $row['activity_id'];
	  $title = $row['title'];
	  $abstract = $row['abstract'];
	  $place = $row['place'];
	  $cover = $row['cover'];
	  $st = strtotime($row['start_time']);
	  if (date('Y',$st)==date('Y',strtotime('now'))) {
		$start_time = date('n月j日 H:i',$st);
	  }else{
		$start_time = date('Y年n月j日',$st);
	  }
	  	$now = strtotime('now');
	  	if ($now<$st) {
	  		$state = 0;
	  	}else if ($row['end_time']!=null) {
			$et = strtotime($row['end_time']);
			if ($now>$et) {
				$state = 2;
			}else{
				$state = 1;
			}
	  	}else{
	  		$state = 2;
	  	}
	$horW = ($total-$verN-1)*140;

	  // 选择wrap12横向显示?>
	<div class="horWrap">
	  <div class="horScroll" style="width: <?php echo $horW;?>px;">

	  <div class="wrap12">
		<input type="hidden" value="<?php echo $id?>">
		<div class="imgWrap">
		  <img src="<?php echo $cover?>" alt="">
		</div>
		<div class="wrapR">
		  <div class="wrap">
		  <?php if(!is_null($state)&&$state){echo '<div class="onAir">On</div>';}?>
			<div class="title"><?php echo $title?></div>
			<div class="timeSpace">
			  <span class="time"><i class="icon-clock"></i><?php echo $start_time?></span>
			  <span class="space"><?php echo $place?></span>
			</div>
		  </div>
		</div>
	  </div>
	<?php
	  $row = $rst->fetch();
	  while (!empty($row)) {
		$no++;
	  $id = $row['activity_id'];
	  $title = $row['title'];
	  $abstract = $row['abstract'];
	  $place = $row['place'];
	  $cover = $row['cover'];
	  $st = strtotime($row['start_time']);
	  if (date('Y',$st)==date('Y',strtotime('now'))) {
		$start_time = date('n月j日 H:i',$st);
	  }else{
		$start_time = date('Y年n月j日',$st);
	  }
	  	$now = strtotime('now');
	  	if ($now<$st) {
	  		$state = 0;
	  	}else if ($row['end_time']!=null) {
			$et = strtotime($row['end_time']);
			if ($now>$et) {
				$state = 2;
			}else{
				$state = 1;
			}
	  	}else{
	  		$state = 2;
	  	}
		?>
		<div class="wrap12">
		  <input type="hidden" value="<?php echo $id?>">
		  <div class="imgWrap">
			<img src="<?php echo $cover?>" alt="">
		  </div>
		  <div class="wrapR">
			<div class="wrap">
			<?php if(!$state){echo '<div class="onAir">On</div>';}?>
			  <div class="title"><?php echo $title?></div>
			  <!-- <div class="abstract">与学生面对面，讲述制作动画的那些事 与学</div> -->
			  <div class="timeSpace">
				<span class="time"><i class="icon-clock"></i><?php echo $start_time?></span>
				<span class="space"><?php echo $place?></span>
			  </div>
			</div>
		  </div>
		</div>
		<?php

	  $row = $rst->fetch();
	  }

	  ?>
	  </div>
	</div>
  <?php
  }
?>
  <div class="bottom"></div>
</section>
<!-- 发布直播 -->
<?php
  if(isPublisher()){
  	$rst = $dbh->query("SELECT * FROM acti_type");
  	$row = $rst->fetchAll();
  ?>
	<div id="publish_trigger"><span class="icon-images"></span><br>创建直播</div>
  <section class="fill_activity" id="fillActivity">
	<div class="head">
	  <span class="icon-undo2 back-l" id="leaveEdit"></span>创建直播
	  <!-- <button type="submit" class="text-r" id="publish-decide">发布</button> -->
	  <!-- <button type="submit" class="text-r" id="btn-preview">预览</button> -->
	</div>
	<div class="body">
	  <form action="php/acti_create.php" method="post" enctype="multipart/form-data" id="createActivity" class="subject-edit">
		<input type="hidden" class="create-prompt" value="0">
		<div class="subject-intro">
		  <input type="text" name="tit" placeholder="直播标题">
		</div>
		<div class="subject-intro">
			<select name="typ" >
				<option value="">种类</option>
			<?php
				foreach ($row as $k => $v) {?>
					<option value="<?php echo $row[$k]['type_id']?>"><?php echo $row[$k]['type']?></option>
				<?php
				}
			?>
			</select>
		    <span class="icon-location"></span>
		    <input type="text" name="pla" placeholder="活动地点">
		</div>
		<!-- 封面与概述 -->
		<div id="cover">
		  <div class="subject-intro">
			<div id="coverWrap" class="cover_preview_wrap">
				<!-- <img src="images/4.jpg" style="width: 100%;" alt=""> -->
			  <div class="img_preview_wrap">
				<span class="icon-plus"></span>
				<!-- <img src="images/4.jpg" alt=""> -->
				<input type="file" name="cover" id="inputCover" accept="image/*" class="upload_input">
			  </div>
			</div>
			  <input type="text" name="org" placeholder="主办方">
		  </div>
		</div>
		<div class="subject-intro i2">
			<textarea name="abs" cols="30" rows="10" placeholder="主题概述"></textarea>
		</div>
		<div class="subject-intro iset">
		<!-- <input placeholder="Date" class="textbox-n" type="text" onfocus="(this.type='date')"  id="date"> -->
		  <!-- 活动时间：17年 月 日 开始时间：  结束直播 -->
		  活动日期：
		  <select name="yea">
		  <?php 
		  for ($i=2015; $i <= date("Y",time())+1; $i++) {
		  if ($i==date("Y",time())){?>
		  	<option value="<?php echo $i?>" selected="selected"><?php echo $i?></option>
		  <?php }else{?>
		  	<option value="<?php echo $i?>"><?php echo $i?></option>
		  <?php }
		  }
		   ?>
		  </select>年
		  <select name="mon">
		  <?php 
		  for ($i=1; $i <= 12; $i++) {
		  if ($i==date("n",time())){?>
		  	<option value="<?php echo $i?>" selected="selected"><?php echo $i?></option>
		  <?php }else{?>
		  	<option value="<?php echo $i?>"><?php echo $i?></option>
		  <?php }
		  }
		   ?>
		  </select>月
		  <select name="day">
		  <?php 
		  for ($i=1; $i <= 31; $i++) {
		  if ($i==date("j",time())){?>
		  	<option value="<?php echo $i?>" selected="selected"><?php echo $i?></option>
		  <?php }else{?>
		  	<option value="<?php echo $i?>"><?php echo $i?></option>
		  <?php }
		  }
		   ?>
		  </select>日
		  <br>
		  开始时间：
		  <select name="hou">
		  <?php 
		  for ($i=0; $i <= 24; $i++) {
		  if ($i==date("G",time())){?>
		  	<option value="<?php echo $i?>" selected="selected"><?php echo $i?></option>
		  <?php }else{?>
		  	<option value="<?php echo $i?>"><?php echo $i?></option>
		  <?php }
		  }
		   ?>
		  </select>:
		  <select name="min">
		  <?php 
		  for ($i=0; $i <= 59; $i++) {
		  if ($i==date("i",time())){?>
		  	<option value="<?php echo $i?>" selected="selected"><?php echo $i?></option>
		  <?php }else{?>
		  	<option value="<?php echo $i?>"><?php echo $i?></option>
		  <?php }
		  }
		   ?>
		  </select>
		</div>
		<input class="btn" type="submit" value="创建直播" onclick="fnResetAfterSubmit()">

		<iframe id="id_iframe" name="id_iframe" style="width: 100%;height: 100px;display: none;"></iframe>
	  </form>
		<div class="bottom"></div>
	</div>
  </section>
<?php
  }
$dbh = NULL;
?>
<div class="fix_side" id="sideMenu">
	<!-- <div class="left_blur"></div> -->
	<div class="head">
		<i class="icon-angle-up close"></i>
		<div class="wp_search"><form action=""><input type="search" id="input_kw" placeholder="搜索"></form>
		</div><div class="btn_search">搜索</div><span class="cancel">取消</span>
	</div>
	<div class="body">
		<div class="choose">
			<div class="list_menu type">
				<div class="head">活动种类</div>
				<div class="body">
					<div class="col-xs-4"><a>旅游</a></div>
					<div class="col-xs-4"><a>生活</a></div>
					<div class="col-xs-4"><a>艺术</a></div>
					<div class="col-xs-4"><a>实践</a></div>
					<div class="col-xs-4"><a>体育</a></div>
					<div class="col-xs-4"><a>科研</a></div>
					<div class="col-xs-4"><a>摄像</a></div>
					<div class="col-xs-4"><a>影视</a></div>
					<div class="col-xs-4"><a>人物</a></div>
					<div class="col-xs-4"><a>游戏</a></div>
				</div>
			</div>
			<div class="list_menu history">
				<div class="head">近期搜索</div>
				<div class="body">
					<div class="col-xs-4"><a>船舶</a></div>
					<div class="col-xs-4"><a>红海龟</a></div>
					<div class="col-xs-4"><a>红海龟 吉卜力</a></div>
					<div class="col-xs-4"><a>坂元裕二</a></div>
				</div>
			</div>
			<!-- <div class="list_menu">
				<div class="head">热门搜索</div>
				<div class="body">
					<div class="col-xs-4"><a>生活生活生活生活</a></div>
					<div class="col-xs-4"><a>艺术艺术艺术</a></div>
					<div class="col-xs-4"><a>体育</a></div>
					<div class="col-xs-4"><a>实践</a></div>
					<div class="col-xs-4"><a>科研</a></div>
				</div>
			</div> -->
		</div>
		<div class="result">
			<div class="list_result">
				<div class="head">活动</div>
				<div class="body" id="actiResult">
					<a>
						<div class="col-xs-2">
							<div class="imgWrap"><img src="upload/user/default3.jpg"></div>
						</div>
						<div class="col-xs-10">
							<div class="r1">活动标题</div>
							<div class="r2">概要</div>
						</div>
					</a>
					<a>
						<div class="col-xs-2">
							<div class="imgWrap"><img src="upload/user/default3.jpg"></div>
						</div>
						<div class="col-xs-10">
							<div class="r1">活动标题</div>
							<div class="r2">概要</div>
						</div>
					</a>
				</div>
			</div>
			<div class="list_result">
				<div class="head">图文</div>
				<div class="body" id="contResult">
					<a>
						<div class="col-xs-2">
							<div class="imgWrap"><img src="upload/user/default3.jpg"></div>
						</div>
						<div class="col-xs-10">
							<div class="r1">图文描述</div>
							<div class="r2">活动标题</div>
						</div>
					</a>
					<a>
						<div class="col-xs-2">
							<div class="imgWrap"><img src="upload/user/default3.jpg"></div>
						</div>
						<div class="col-xs-10">
							<div class="r1">图文描述</div>
							<div class="r2">活动标题</div>
						</div>
					</a>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- <div class="fix_side .blur-container">
<div class=".blur-box">
	<h2>你好</h2>
</div> -->
</div>
<!-- 评论 -->
<div class="report_wrap" id="wpComment">
    <a class="close icon-cross"></a>
    <textarea class="say" rows="3" placeholder="回复："></textarea>
    <div class="prompt"></div>
    <a class="btn submitComment">回复</a>
</div>
<!-- 加载中 -->
<div id="loading">
  <div class="imgWrap">
    <img src="image/loading.gif" alt="加载中">
  </div>
</div>
<script src="js/jquery-3.1.1.min.js"></script>
<script src="js/jquery.easing.1.3.js"></script>
<script src="js/index.js"></script>
<script src="js/main.js"></script>
</body>
</html>