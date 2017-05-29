<!DOCTYPE html>
<html lang="zh-CN">

<!-- 连接数据库 -->
<?php
    @session_start();
    @include '../init.php';
function isLogged(){
    return (isset($_SESSION['uid'])&&$_SESSION['uid']!="")?true:false;
}
function canEdit(){
    global $uid,$fromUId;
    if (isLogged()) {
        return ($uid == $fromUId)?true:false;
    }
}
function isClosed(){
    global $state;
    if ($state==1) {
        return true;
    }
}
    $aid = isset($_GET['aid'])?$_GET['aid']:0;
    $sql = "SELECT * FROM activity WHERE activity_id='$aid'";
    $rst = $dbh->query($sql);
    if (is_bool($rst)) {
        echo '未找到要观看的直播';
    }else{
        $row = $rst->fetch();
        $cov = "../upload/activity/".$row['cover'];
        $tit = $row['title'];
        $abs = $row['abstract'];
        $tid = $row['acti_type_id'];
        $sql_typ = "SELECT type FROM acti_type WHERE type_id='$tid'";
        $row_typ = $dbh->query($sql_typ)->fetch();
        $typ = $row_typ[0];
        $org = $row['organizer'];
        $pla = $row['place'];
        $fromUId = $row['user_id'];
        // $focusN = $row['focusNum'];
        $focusN = 0;
          $st = strtotime($row['start_time']);
          if (date('Y',$st)==date('Y',strtotime('now'))) {
            $dat = date('n月j日',$st);
          }else{
            $dat = date('Y年n月j日',$st);
          }
          if ($row['end_time']!=null) {
            $et = strtotime($row['end_time']);
            $now = strtotime('now');
            if ($now<$st) {
                $state = 2;
            }else if ($now>$et) {
                $state = 1;
            }else{
                $state = 0;
            }
          }
        $sql = "SELECT headimg,uname FROM USER WHERE user_id='$fromUId'";
        $rst = $dbh->query($sql);
        if (!is_bool($rst)) {
            $row = $rst->fetch();
            $uimg = "../upload/user/".$row['headimg'];
            $bro = $row['uname'];
        }
?>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $tit?></title>
    <meta name="Keywords" content="图文直播,校园活动,直播">  
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
    <link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../css/index.css">
    <link rel="stylesheet" type="text/css" href="../css/activity.css">
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <script src="../js/public.js"></script>
</head>
<body>
<?php
$uid = '';
if (isLogged()) {
    $uid = $_SESSION['uid'];
    $uname = $_SESSION['uname'];
}else{?>
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
<?php }
?>
<input type="hidden" id="usr" value="<?php echo $uid ?>">
<!-- 定义图文、评论显示函数 -->
    <?php function fnListCom($comid,$commenter,$say){?>
                                    <div class="reply-detail">
                                        <input value="<?php echo $comid ?>" type="hidden">
                                        <span class="commenter"><?php echo $commenter ?></span> <span class="conmment"><?php echo $say ?></span>
                                    </div>
    <?php    } ?>
    <?php function fnListCont($cid,$tim,$dat,$tex,$imgN,$img,$likN,$comN){
        global $dbh;?>
                        <div class="cont-body">
                            <input value="<?php echo $cid ?>" type="hidden">
                            <div class="time-location">
                                <div><i class="icon-clock2"></i></div>
                                <div><?php echo $tim ?><span class="dat"><?php echo $dat ?></span></div>
                                <div>
                                    <a class="icon-more_horiz more"></a>
                                    <div id="modifyCont" class="pop_list">
                                      <ul>
                                      <?php if (canEdit()) {?>
                                        <li><a>修改</a></li>
                                        <li><a>删除</a></li>
                                      <?php } ?>
                                        <li><a>举报</a></li>
                                      </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="report_wrap">
                                <a class="close icon-cross"></a>
                                <textarea class="reason" rows="2" placeholder="请输入举报原因"></textarea>
                                <div class="prompt"></div>
                                <a class="btn submitReport">举报</a>
                            </div>
                            <div class="guts">
                                <p><?php echo $tex ?></p>
                                <?php
                                    switch ($imgN) {
                                        case 1:?>
                                            <div class="wrap_contImg wp1">
                                                <span class="imgWrap"><img class="lazy" data-original="<?php echo $img[0]?>"></span>
                                            </div>
                                        <?php break;
                                        case 2:?>
                                            <div class="wrap_contImg wp2">
                                                <span class="imgWrap"><img class="lazy" data-original="<?php echo $img[0]?>"></span>
                                                <span class="imgWrap"><img class="lazy" data-original="<?php echo $img[1]?>"></span>
                                            </div>
                                        <?php break;
                                        case 3:?>
                                            <div class="wrap_contImg wp3">
                                                <span class="imgWrap"><img class="lazy" data-original="<?php echo $img[0]?>"></span>
                                                <span class="imgWrap"><img class="lazy" data-original="<?php echo $img[1]?>"></span>
                                                <span class="imgWrap"><img class="lazy" data-original="<?php echo $img[2]?>"></span>
                                            </div>
                                        <?php break;
                                        case 4:?>
                                            <div class="wrap_contImg wp2">
                                                <div>
                                                    <span class="imgWrap"><img class="lazy" data-original="<?php echo $img[0]?>"></span>
                                                    <span class="imgWrap"><img class="lazy" data-original="<?php echo $img[1]?>"></span>
                                                    <span class="imgWrap"><img class="lazy" data-original="<?php echo $img[2]?>"></span>
                                                    <span class="imgWrap"><img class="lazy" data-original="<?php echo $img[3]?>"></span>
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
                                <div class="interaction-details">
        <?php // 获取并列出最新5条评论
            $sql_com = "SELECT comment_id,user_id,say,add_time FROM comment WHERE content_id='$cid' ORDER BY comment_id DESC LIMIT 0,3";
            $rst_com = $dbh->query($sql_com);
            $comN = 0;
            if (!is_bool($rst_com)) {
                $row_com = $rst_com->fetch();
                while (!empty($row_com)) {
                    $comN++;
                    $commenterid = $row_com['user_id'];
                    $comid = $row_com['comment_id'];
                    $say = $row_com['say'];
                    $sql_ucom = "SELECT uname FROM user WHERE user_id='$commenterid'";
                    $rst_ucom = $dbh->query($sql_ucom);
                    if (!is_bool($rst_ucom)) {
                        $row_ucom = $rst_ucom->fetch();
                        $commenter = $row_ucom[0];
                    }
                    fnListCom($comid,$commenter,$say);
                    $row_com = $rst_com->fetch();
                }
                if ($comN>0) {?>
                <a href="comment.php?type=content&id=<?php echo $cid?>" class="reply-detail moreCom">查看更多评论</a>
                <?php }
            }
        ?>
                                </div>
                            </div>
                        </div>
    <?php    } ?>
<!-- 获取cont-head信息，展示出来 -->
    <div class="separate_page" id="activity" style="display: block;">
        <input type="hidden" value="<?php echo $aid?>">
        <div class="head">
            <!-- <i class="icon-navigate_before leave"></i> -->
            <a href="../index.php" class="icon-home logo"></a>
            <div class="right">
                <i class="icon-redo refresh"></i>
                <a href="comment.php?type=activity&id=<?php echo $aid?>"><i class="icon-bubble2"></i></a>
                <!--关注-->
                <?php if (canEdit($fromUId)) {?>
                <i class="icon-edit moreEdit"></i>
                <div class="edit_list">
                    <ul>
                        <li><a class="icon-bin delete"></a></li>
                        <?php if (isClosed()){?>
                            <li><a class="icon-calendar-plus-o start"></a></li>
                        <?php }else{?>
                            <li><a class="icon-calendar-times-o end"></a></li>
                        <?php }?>
                        <!--编辑直播-->
                        <?php if (!isClosed()){?>
                            <li><a class="icon-pencil edit"></a></li>
                            <li><a class="icon-add_a_photo add"></a></li>
                        <?php }?>
                    </ul>
                </div>
                <?php } ?>
            </div>
        </div>
        <div class="body">
            <div class="top" style="background-image: url('<?php echo $cov?>');">
                <div class="top-text"><?php echo $tit?></div>
                <?php 
                    $sql = "SELECT count(*) FROM acti_focus WHERE activity_id='$aid' AND user_id='$uid'";
                    $rst = $dbh->query($sql);
                    $row = $rst->fetch();
                    $on_focus = '';
                    $icon_class = 'icon-plus';
                    if (!empty($row[0])) {
                        $on_focus = 'on';
                        $icon_class = 'icon-checkmark on';
                    };
                    $row_count = $dbh->query("SELECT count(*) FROM acti_view WHERE activity_id='$aid'
                                UNION ALL
                                SELECT count(*) FROM acti_focus WHERE activity_id='$aid'
                                UNION ALL
                                SELECT count(*) FROM comment JOIN content ON comment.content_id=content.content_id WHERE content.activity_id='$aid' OR comment.activity_id='$aid'"
                            )->fetchAll();
                    // var_dump($dbh->errorInfo());
                    // $row_count = $dbh->query($sql_count)->fetchAll();
                    $vieN = $row_count[0][0];
                    $focN = $row_count[1][0];
                    $comN = $row_count[2][0];
                 ?>
                <div class="add_focus <?php echo $on_focus?>"><i class="<?php echo $icon_class; ?>"></i>关注</div>
            </div>
            <div class="content2">
                <div class="cont-head">
                <div>
                    <div>
                        直播君:<span class="imgWrap brohead"><img src="<?php echo $uimg?>" alt="发布者头像" style="width: 100%; height: auto;"></span>
                        <label class="author-name"><?php echo $bro?></label>
                    </div>
                    <div class="place"><i class="icon-location"></i><?php echo $pla?></div>
                </div>
                <div>
                    <div>主办方<span class="organizer"><?php echo $org?></span></div>
                    <div class="info">
                        <span class="date"><?php echo $dat?></span>
                    </div>
                </div>
                <div>
                    <div class="count">
                        <span><i class="icon-eye"></i><?php echo $vieN?></span>
                        <span><i class="icon-bookmarks"></i><?php echo $focN?></span>
                        <span><i class="icon-comments"></i><?php echo $comN?></span>
                    </div>
                    <div class="type"><?php echo $typ?></div>
                </div>
                    
                    
                    
                </div>
                <hr>
                <p class="abs b_l"><?php echo $abs?></p>
                <div id="update">
<?php //获取图文数据并显示
    $sql = "SELECT * FROM content WHERE activity_id='$aid' ORDER BY content_id DESC";
    $rst = $dbh->query($sql);
    $row = $rst->fetch();
    if (empty($row[0])) {?>
        <div class="no_cont <?php echo !isClosed()&&canEdit()?"add":""?>">该直播尚没有图文内容</div>
    <?php }else{
        while (!empty($row[0])) {
            $cid = $row['content_id'];
            $tim = date('H:i',strtotime($row['add_time']));
            $dat = date('n月j日',strtotime($row['add_time']));
            $tex = $row['description'];
            $sql_num = "SELECT count(*) FROM cont_like WHERE content_id='$cid' 
            UNION ALL SELECT count(*) FROM comment WHERE content_id='$cid'";
            $rst_num = $dbh->query($sql_num);
            $row_num = $rst_num->fetchAll();
            // var_dump($row_num);
            $likN = $row_num[0][0];
            $comN = $row_num[1][0];
            $imgN = 0;
            $img = [];
            $sql_img = "SELECT img_name FROM cont_img WHERE content_id='$cid'";
            $rst_img = $dbh->query($sql_img);
            $row_img = $rst_img->fetch();
            if (empty($row_img[0])) {?>
                <!-- <div class="no_cont">该内容找不到图</div> -->
            <?php }else{
                while (!empty($row_img)) {
                    array_push($img,"../upload/content/".$row_img[0]);
                    $imgN += 1;
                    $row_img = $rst_img->fetch();
                }
            }
            fnListCont($cid,$tim,$dat,$tex,$imgN,$img,$likN,$comN);
            $row = $rst->fetch();
        }
    }
?>
                </div>
            </div>
        </div>
        <!-- 编辑图文 -->
        <form action="../php/cont_add.php" method="post" enctype="multipart/form-data" class="pop_edit" id="addCont">
            <input name="aid" value="<?php echo $aid?>" type="hidden">
            <input name="img[]" id="inputContImg" accept="image/*" class="upload_input" type="file" multiple="multiple">
            <input name="img[]" id="addContImg1" accept="image/*" class="upload_input" type="file" multiple="multiple">
            <input name="img[]" id="addContImg2" accept="image/*" class="upload_input" type="file" multiple="multiple">
            <input name="img[]" id="addContImg3" accept="image/*" class="upload_input" type="file" multiple="multiple">
            <div class="wrap_addCont">
                <i class="icon-cross close"></i>
                <div class="wrap_img">
                    <div id="addCont_imgsWrap"></div><div class="imgWrap wp_add" id="addContImg"><i class="icon-add_a_photo"></i></div>
                </div>
                <div class="wrap_abs">
                    <textarea name="text" placeholder="正在发生什么" rows="3"></textarea>
                </div>
                <div>
                    <input type="submit" class="btn" value="确定">
                </div>
            </div>
        </form>
        <!-- 编辑直播 -->
        <div class="pop_edit" id="editActivity">
            <div>
                <i class="icon-cross close"></i>
                <div class="col_l imgWrap cov">
                </div>
                <div class="col_r">
                    <div>
                        <input type="text" name="tit" placeholder="直播标题">
                    </div>
                    <div class="wrap_typ">活动种类 
                        <select name="tid">
                        <?php
                            $rst = $dbh->query("SELECT * FROM acti_type");
                            $row = $rst->fetchAll();
                            foreach ($row as $k => $v) {?>
                            <?php if ($row[$k]['type_id']==$tid){?>
                                <option value="<?php echo $row[$k]['type_id']?>" selected="selected"><?php echo $row[$k]['type']?></option>
                            <?php }else{?>
                                <option value="<?php echo $row[$k]['type_id']?>"><?php echo $row[$k]['type']?></option>
                            <?php }
                            }
                        ?>
                        </select>
                    </div>
                    <!-- <div><input type="text" name="dat" placeholder="日期格式:YYYY-MM-DD"></div> -->
                </div>
                <!-- <div class="wrap_dat">
                    <input type="text" name="dat" placeholder="活动日期">
                </div> -->
                <div class="wrap_abs">
                    <textarea name="abs" placeholder="主题概要"></textarea>
                </div>
                <div>
                    <a class="btn btn_editActivity">确定</a>
                </div>
            </div>
            <form action="../php/acti_edit_cover.php" method="post" enctype="multipart/form-data">
                <input type="text" name="aid" value="">
                <input type="file" name="cover" value="" id="inputCover">
                <input type="submit" id="submitCover">
            </form>
        </div>
    </div>
<?php
    }
// 更新点击量
$sql = "INSERT INTO acti_view (activity_id,user_id) VALUES ('$aid','$uid')";
$dbh->query($sql);

$dbh = NULL;
?>
<!-- 加载中 -->
<div id="loading">
  <div class="imgWrap">
    <img src="../image/loading.gif" alt="加载中">
  </div>
</div>
<!-- 切换大图 -->
<div id="largeWrap">
    <div class="imgWrap" id="largeImg">
        <img src="images/5.jpg" alt="">
    </div>
    <div class="wrap_btn">
        <a class="icon-chevron-circle-left fl prev"></a>
        <a class="icon-chevron-circle-right fr next"></a>
    </div>
</div>
<script src="../js/jquery-3.1.1.min.js"></script>
<script src="../js/jquery.lazyload.min.js"></script>
<script src="../js/jquery.easing.1.3.js"></script>
<script src="../js/index.js"></script>
<script>
    $("img.lazy").lazyload({
        effect : "fadeIn"
    });
</script>
</body>
</html>