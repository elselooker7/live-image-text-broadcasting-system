<!DOCTYPE html>
<html lang="zh-CN">

<!-- 连接数据库 -->
<?php
    @session_start();
    @include '../init.php';
    function isLogged(){
        return (isset($_SESSION['uid'])&&$_SESSION['uid']!="")?true:false;
    }
    $usr = isset($_SESSION['uname'])?$_SESSION['uname']:'游客';
    $uid = isset($_SESSION['uid'])?$_SESSION['uid']:0;
    $type = isset($_GET['type'])?$_GET['type']:'';
    $id = isset($_GET['id'])?$_GET['id']:0;
        //头像 评论者 内容 时间 支持数
    function fnShowReply($comid,$uimg,$usr,$to_usr,$say,$tim,$agrN){
        global $dbh;?>
          <div class="wrap2" data-comid="<?php echo $comid?>">
              <table>
                <tbody>
                  <tr class="r1">
                      <td><div class="imgWrap"><img src="<?php echo $uimg?>" alt=""></div></td>
                      <td class="commenter"><div><span class="usr"><?php echo $usr?></span><i class="icon-angle-right"></i><span><?php echo $to_usr?></span></div><div class="time"><?php echo $tim?></div></td>
                      <td class="agreeN"><?php echo $agrN?></td>
                      <td><i class="icon-thumb_up agree"></i></td>
                  </tr>
                  <tr class="r2">
                    <td></td>
                    <td class="say" colspan="2"><?php echo $say?></td>
                  </tr>
                </tbody>
              </table>
            </div>
          <?php
            $to_usr = $usr;
            $sql = "SELECT comment.comment_id AS comid,user.headimg AS uimg,user.uname AS usr,comment.say AS say,comment.add_time AS tim FROM comment LEFT JOIN user ON comment.user_id=user.user_id WHERE to_comment_id='$comid' ORDER BY comment.add_time DESC";
            $rst = $dbh->query($sql);
            $row = $rst->fetch();
            while (!empty($row[0])) {
                $comid = $row['comid'];
                $uimg = '../upload/user/'.$row['uimg'];
                $usr = $row['usr'];
                $say = $row['say'];
                $tim = strtotime($row['tim']);
                $sql_agr = "SELECT count(*) FROM com_agree WHERE comment_id='$comid'";
                $rst_agr = $dbh->query($sql_agr);
                // var_dump($dbh->errorInfo());
                $row_agr = $rst_agr->fetch();
                if (empty($row_agr)) {
                  $agrN = 0;
                }else{$agrN = $row_agr[0];}
                // n-j|m-d
                if (date('Y',$tim)==date('Y')) {
                  // echo (time()-$tim);
                  if ((time()-$tim)<3600) {
                    $min = date('i',(time()-$tim));
                    if($min==0){
                        $tim = "刚刚";
                    }else{
                    if ($min<10) {
                          $min = preg_replace('/^0+/', '', $min);
                        }
                        $tim = $min.'分钟前';
                    }
                  }else{
                    $tim = date('n月j日 H:i',$tim);
                  }
                }else{
                  $tim = date('Y年n月j日',$tim);
                }
                fnShowReply($comid,$uimg,$usr,$to_usr,$say,$tim,$agrN);
                $row = $rst->fetch();
            }
           ?>
<?php }
    function fnShowCom($comid,$uimg,$usr,$say,$tim,$agrN){
        global $dbh;?>
        <div id="com<?php echo $comid?> " class="wrap2" data-comid="<?php echo $comid?>">
        <table>
            <tbody>
              <tr class="r1">
                  <td><div class="imgWrap"><img src="<?php echo $uimg?>" alt=""></div></td>
                  <td class="commenter"><div class="usr"><?php echo $usr?></div><div class="time"><?php echo $tim?></div></td>
                  <td class="agreeN"><?php echo $agrN?></td>
                  <td><i class="icon-thumb_up agree"></i></td>
              </tr>
              <tr class="r2">
                <td></td>
                <!-- <div class="pop_list" id="reply_more">
                    <ul>
                        <li>赞</li><li>回复</li><li>举报</li>
                    </ul>
                </div> -->
                <td class="say" colspan="2"><?php echo $say?></td>
              </tr>
            </tbody>
        </table>
        <div class="wp_reply">
          <?php
            $to_usr = $usr;
            $sql = "SELECT comment.comment_id AS comid,user.headimg AS uimg,user.uname AS usr,comment.say AS say,comment.add_time AS tim FROM comment LEFT JOIN user ON comment.user_id=user.user_id WHERE to_comment_id='$comid' ORDER BY comment.add_time DESC";
            $rst = $dbh->query($sql);
            $row = $rst->fetch();
            while (!empty($row)) {
                $comid = $row['comid'];
                $uimg = '../upload/user/'.$row['uimg'];
                $usr = $row['usr'];
                $say = $row['say'];
                $tim = strtotime($row['tim']);
                $sql_agr = "SELECT count(*) FROM com_agree WHERE comment_id='$comid'";
                $rst_agr = $dbh->query($sql_agr);
                // var_dump($dbh->errorInfo());
                $row_agr = $rst_agr->fetch();
                if (empty($row_agr)) {
                  $agrN = 0;
                }else{$agrN = $row_agr[0];}
                // n-j|m-d
                if (date('Y',$tim)==date('Y')) {
                  // echo (time()-$tim);
                  if ((time()-$tim)<3600) {
                    $min = date('i',(time()-$tim));
                    if($min==0){
                        $tim = "刚刚";
                    }else{
                    if ($min<10) {
                          $min = preg_replace('/^0+/', '', $min);
                        }
                        $tim = $min.'分钟前';
                    }
                  }else{
                    $tim = date('n月j日 H:i',$tim);
                  }
                }else{
                  $tim = date('Y年n月j日',$tim);
                }
                fnShowReply($comid,$uimg,$usr,$to_usr,$say,$tim,$agrN);
                $row = $rst->fetch();
            }
           ?>
           </div>
        </div>
<?php }
    function fnListCont($cid,$tim,$dat,$tex,$imgN,$img,$likN,$comN){
?>
        <div class="cont-body" id="cont<?php echo $cid?>">
            <input value="<?php echo $cid ?>" type="hidden">
            <div class="time-location">
                <div><i class="icon-clock2"></i></div>
                <div><?php echo $tim ?><span class="dat"><?php echo $dat ?></span></div>
                <div>
                    <a class="icon-more_horiz more"></a>
                    <div id="modifyCont" class="pop_list">
                      <ul>
                        <li><a>举报</a></li>
                      </ul>
                    </div>
                </div>
            </div>
            <div class="col-xs-11 guts">
                <p><?php echo $tex ?></p>
                <?php
                    switch ($imgN) {
                        case 1:?>
                            <div class="wrap_contImg wp1">
                                <span class="imgWrap"><img src="<?php echo $img[0]?>"></span>
                            </div>
                        <?php break;
                        case 2:?>
                            <div class="wrap_contImg wp2">
                                <span class="imgWrap"><img src="<?php echo $img[0]?>"></span>
                                <span class="imgWrap"><img src="<?php echo $img[1]?>"></span>
                            </div>
                        <?php break;
                        case 3:?>
                            <div class="wrap_contImg wp3">
                                <span class="imgWrap"><img src="<?php echo $img[0]?>"></span>
                                <span class="imgWrap"><img src="<?php echo $img[1]?>"></span>
                                <span class="imgWrap"><img src="<?php echo $img[2]?>"></span>
                            </div>
                        <?php break;
                        case 4:?>
                            <div class="wrap_contImg wp2">
                                <div>
                                    <span class="imgWrap"><img src="<?php echo $img[0]?>"></span>
                                    <span class="imgWrap"><img src="<?php echo $img[1]?>"></span>
                                </div>
                                <div>
                                    <span class="imgWrap"><img src="<?php echo $img[2]?>"></span>
                                    <span class="imgWrap"><img src="<?php echo $img[3]?>"></span>
                                </div>
                            </div>
                        <?php break;
                    }
                 ?>
                <div class="interact-icons">
                    <div>
                        <a class="icon-heart4 like"></a><sup><?php echo $likN ?></sup></div>
                    <div>
                        <a class="icon-bubbles comment"></a><sup><?php echo $comN ?></sup></div>
                </div>
                <div class="addCom">
                  <table>
                  <tr>
                    <td><input type="textarea" autofocus="autofocus" placeholder="添加评论"></td>
                    <td><a class="btn submitCom">确定</a></td>
                  </tr>
                  </table>
                </div>
            </div>
        </div>
<?php
    }
    function fnShowActi($tit,$head,$bro,$tim){
        $head = '../upload/user/'.$head;
        $t = strtotime($tim);
        if (date('Y',$t)==date('Y')) {
            $tim = date('n月j日',$t);
        }else{
            $tim = date('Y年n月j日',$t);
        }
?>
        <!-- <div class="wp_acti">
            <div class="tit"><?php echo $tit?></div>
            <div class="row">
                <div class="col-xs-1">
                    <div class="imgWrap"><img src="<?php echo $head?>"></div>
                </div>
                <div class="col-xs-3"><?php echo $bro?></div>
                <div class="col-xs-7"><?php echo $tim?></div>
            </div>
        </div> -->
<?php        
    }
?>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>评论</title>
    <meta name="Keywords" content="图文直播,校园活动,直播">  
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../css/index.css">
    <link rel="stylesheet" type="text/css" href="../css/activity.css">
    <link rel="stylesheet" type="text/css" href="../css/comment.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
</head>
<body>
<?php
    if ($id==0) {
        echo "<script>alert('未找到评论');window.history.go(-1);</script>";
    }else{
        if (!isLogged()) {
        ?>
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
        <div class="wrap_input">
          <input type="text" name="school" placeholder="学校">
        </div>
        <div class="wrap_input">
          <input type="text" name="s_q" placeholder="安全问题">
        </div>
        <div class="wrap_input">
          <input type="text" name="s_a" placeholder="安全问题答案(用于修改密码)">
        </div>
        <div class="wrap_input choose_utype">
          <div><input type="radio" name="rtype" class="rtype" value="1" checked="checked"> 观众</div>
          <div><input type="radio" name="rtype" class="rtype" value="2"> 直播君</div>
        </div>
        <div class="prompt reg_p"></div>
        <div class="wrap_btn">
          <a class="btn log_trigger">登录</a><a class="btn active reg">注册</a>
        </div>
      </div>
    </div>
</div>
                <?php 
            }?>

<input type="hidden" id="uid" value="<?php echo $uid?>">
<input type="hidden" id="type" value="<?php echo $type?>">
<input type="hidden" id="id" value="<?php echo $id?>">
<!-- 评论页 -->
    <div class="separate_page" id="commentPage" style="display: block;">
        <!-- 图文Id -->
        <div class="head">
            <i class="icon-navigate_before back"></i>
            评论
            <div class="right">
                <i class="icon-redo refresh"></i>
            </div>
        </div>
        <div class="body">
    <?php
    switch ($type) {
        case 'content':
            $cid = $id;
            $rst = $dbh->query("SELECT * FROM content WHERE content_id='$cid'");
            $row = $rst->fetch();
            $tim = date('H:i',strtotime($row['add_time']));
            $dat = date('n月j日',strtotime($row['add_time']));
            $tex = $row['description'];
            $rst_num = $dbh->query("SELECT count(*) FROM cont_like WHERE content_id='$cid' 
            UNION ALL SELECT count(*) FROM comment WHERE content_id='$cid'");
            $row_num = $rst_num->fetchAll();
            // var_dump($row_num);
            $likN = $row_num[0][0];
            $comN = $row_num[1][0];
            $imgN = 0;
            $img = [];
            $rst_img = $dbh->query("SELECT img_name FROM cont_img WHERE content_id='$cid'");
            $row_img = $rst_img->fetch();
            while (!empty($row_img)) {
                array_push($img,"../upload/content/".$row_img[0]);
                $imgN += 1;
                $row_img = $rst_img->fetch();
            }
            fnListCont($cid,$tim,$dat,$tex,$imgN,$img,$likN,$comN);
            ?>
            <div class="wp_reply">
            <?php
            $sql = "SELECT comment.comment_id AS comid,user.headimg AS uimg,user.uname AS usr,comment.say AS say,comment.add_time AS tim FROM comment LEFT JOIN user ON comment.user_id=user.user_id WHERE content_id='$cid' ORDER BY comment.add_time DESC";
            $rst = $dbh->query($sql);
            $row = $rst->fetch();
            if (empty($row)) {
                echo $cid;
                var_dump($row);
                echo '<div class="no_com">该图文尚没有评论</div>';
            }else{
                while (!empty($row)) {
                    $comid = $row['comid'];
                    $uimg = '../upload/user/'.$row['uimg'];
                    $usr = $row['usr'];
                    $say = $row['say'];
                    $tim = strtotime($row['tim']);
                    $sql_agr = "SELECT count(*) FROM com_agree WHERE comment_id='$comid'";
                    $rst_agr = $dbh->query($sql_agr);
                    // var_dump($dbh->errorInfo());
                    $row_agr = $rst_agr->fetch();
                    if (empty($row_agr)) {
                      $agrN = 0;
                    }else{$agrN = $row_agr[0];}
                    // n-j|m-d
                    if (date('Y',$tim)==date('Y')) {
                      // echo (time()-$tim);
                      if ((time()-$tim)<3600) {
                        $min = date('i',(time()-$tim));

                        if($min==0){
                            $tim = "刚刚";
                        }else{
                        if ($min<10) {
                              $min = preg_replace('/^0+/', '', $min);
                            }
                            $tim = $min.'分钟前';
                        }
                      }else{
                        $tim = date('n月j日 H:i',$tim);
                      }
                    }else{
                      $tim = date('Y年n月j日',$tim);
                    }
                    fnShowCom($comid,$uimg,$usr,$say,$tim,$agrN);
                    $row = $rst->fetch();
                }
            }
            ?></div><?php
            break;
        case 'activity':
            $aid = $id;
            // $sql = "SELECT comment.comment_id AS comid,user.headimg AS uimg,user.uname AS usr,comment.say AS say,comment.add_time AS tim FROM comment JOIN content ON comment.content_id=content.content_id JOIN user ON comment.user_id=user.user_id WHERE content.activity_id=$aid OR comment.activity_id=$aid ORDER BY comment.add_time DESC";
            $sql = "SELECT content_id,comment.comment_id AS comid,user.headimg AS uimg,user.uname AS usr,comment.say AS say,comment.add_time AS tim FROM comment JOIN user ON comment.user_id=user.user_id WHERE content_id IN (SELECT content_id FROM content WHERE activity_id=$aid) ORDER BY comment.add_time DESC";
            $rst = $dbh->query($sql);
            $row = $rst->fetch();
            // $row = $rst->fetch();
            if (empty($row)) {
                echo '<div class="no_com">该活动尚没有评论</div>';
            }else{
                while (!empty($row)) {
                    $comid = $row['comid'];
                    $uimg = '../upload/user/'.$row['uimg'];
                    $usr = $row['usr'];
                    $say = $row['say'];
                    $tim = strtotime($row['tim']);
                    $sql_agr = "SELECT count(*) FROM com_agree WHERE comment_id='$comid'";
                    $rst_agr = $dbh->query($sql_agr);
                    // var_dump($dbh->errorInfo());
                    $row_agr = $rst_agr->fetch();
                    if (empty($row_agr)) {
                      $agrN = 0;
                    }else{$agrN = $row_agr[0];}
                    // n-j|m-d
                    if (date('Y',$tim)==date('Y')) {
                      // echo (time()-$tim);
                      if ((time()-$tim)<3600) {
                        $min = date('i',(time()-$tim));
                        if($min==0){
                            $tim = "刚刚";
                        }else{
                        if ($min<10) {
                              $min = preg_replace('/^0+/', '', $min);
                            }
                            $tim = $min.'分钟前';
                        }
                      }else{
                        $tim = date('n月j日 H:i',$tim);
                      }
                    }else{
                      $tim = date('Y年n月j日',$tim);
                    }
                    fnShowCom($comid,$uimg,$usr,$say,$tim,$agrN);
                    $row = $rst->fetch();
                }
            }
            break;
        case 'comment':
            $comid = $id;
            $rst = $dbh->query("SELECT comment.comment_id AS comid,user.headimg AS uimg,user.uname AS usr,comment.say AS say,comment.add_time AS tim FROM comment LEFT JOIN user ON comment.user_id=user.user_id WHERE comment_id='$comid'");
            $row = $rst->fetch();
            $comid = $row['comid'];
            $uimg = '../upload/user/'.$row['uimg'];
            $usr = $row['usr'];
            $say = $row['say'];
            $tim = strtotime($row['tim']);
            $sql_agr = "SELECT count(*) FROM com_agree WHERE comment_id='$comid'";
            $rst_agr = $dbh->query($sql_agr);
            // var_dump($dbh->errorInfo());
            $row_agr = $rst_agr->fetch();
            if (empty($row_agr)) {
              $agrN = 0;
            }else{$agrN = $row_agr[0];}
            // n-j|m-d
            if (date('Y',$tim)==date('Y')) {
              // echo (time()-$tim);
              if ((time()-$tim)<3600) {
                $min = date('i',(time()-$tim));
                if($min==0){
                    $tim = "刚刚";
                }else{
                if ($min<10) {
                      $min = preg_replace('/^0+/', '', $min);
                    }
                    $tim = $min.'分钟前';
                }
              }else{
                $tim = date('n月j日 H:i',$tim);
              }
            }else{
              $tim = date('Y年n月j日',$tim);
            }
            fnShowCom($comid,$uimg,$usr,$say,$tim,$agrN);
            ?>
            <?php
            break;
        
        default:
            # code...
            break;
    }
        
            ?>
        </div>
    </div>
    <!-- 选择回复或评论 -->
    <div class="pop_list" id="reply_more">
        <ul>
            <li class="agree">赞</li>
            <li class="reply">回复</li>
            <li class="report">举报</li>
            <li class="delete">删除</li>
        </ul>
    </div>
    <!-- 举报 -->
    <div class="report_wrap" id="wpReport">
        <a class="close icon-cross"></a>
        <textarea class="reason" rows="3" placeholder="请输入举报原因"></textarea>
        <div class="prompt"></div>
        <a class="btn submitReport">举报</a>
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
                            <img src="../image/loading.gif" alt="加载中">
                          </div>
                        </div>

<?php
    }
$dbh = NULL;
?>
<script src="../js/jquery-3.1.1.min.js"></script>
<script src="../js/jquery.easing.1.3.js"></script>
<script src="../js/comment.js"></script>
</body>
</html>