$(function(){
	function fnIsLogged () {
		// console.log($("#userInfo")[0]);
		// console.log($("#usr").val());
		return ($("#uid").val()==''||$("#uid").val()==0)?false:true;
	}
	function fnPopLog(){
		fnUnscroll($("body"));
		fnShow($("#sep_reg_log"),'fadeIn');
	}
// 首页图片自适应宽高
	$("#commentPage img").each(function() {
		// console.log($(this));
	    fnResizeImg($(this));
	});
//使图片cover显示
	var flagHeadImgResized = false;
	function fnResizeImg(obj) {
	    // alert("fnResizeImg");
	    var imgWrapW = 0,
	        imgWrapH = 0;
	    imgWrapW = obj.parent().width();
	    imgWrapH = obj.parent().height();
	    var wrapWHRatio = imgWrapW / imgWrapH;
	    console.log(obj);
	    console.log(obj.get(0));
	    var img = obj.get(0);
	    if (img.complete) {
	    	loadedImgResize(img,obj);
	    }
	    img.onload = function  () {
	    	loadedImgResize(img,obj);
	    }
	    function loadedImgResize (img,obj) {
		    var imgWHRatio = (img.width) / (img.height);
		    // alert('wrapWHRatio:'+wrapWHRatio+'imgWHRatio:'+imgWHRatio);
		    if (imgWHRatio >= wrapWHRatio) {
		        obj.css({
		            'height': '100%',
		            'width': 'auto'
		        });
		    } else {
		        obj.css({
		            'width': '100%',
		            'height': 'auto'
		        });
		    }
	    }
	}
// 登录注册
	$("#sep_reg_log").on('click', function(e) {
	  t = e.target;
	  $t = $(t);
	  $this = $(this);
	  if ($t.is('.close')) {fnRescroll();$this.fadeOut();}
	  else if ($t.is('.log')) {
	    $u = $this.find("input[name='uname']");
	    $p = $this.find("input[name='pwd']");
	    $prompt = $this.find('.log_p');
	    fnLog($u,$p,$prompt,$t);
	  }
	  else if ($t.is('.reg')) {
	    $u = $this.find("input[name='rname']");
	    $p = $this.find("input[name='rpwd']");
	    $type = $this.find("input[name='rtype']:checked");
	    $prompt = $this.find('.reg_p');
	    fnReg($u,$p,$prompt,$t,$type);
	  }
	  else if ($t.is('.reg_trigger')) {
	  	$t.closest('#sep_reg_log').animate({'left':'-100vw'});
	  	$t.parent().parent().animate({'opacity':0});
	  }
	  else if ($t.is('.log_trigger')) {
	  	$t.closest('#sep_reg_log').animate({'left':'0'});
	  	$t.parent().parent().prev().animate({'opacity':1});
	  }else if ($t.is('.reg_log')) {
	  	fnRescroll();
	  	$this.fadeOut();
	  }
	});
	var gloPath;
	function fnLog ($u,$p,$prompt,$this) {
	  console.log('登录');
	  if ($u.val().length<1||$p.val().length<1) {
	    $prompt.html("请输入用户名和密码");
	  }
	  else {
	    var uname = $u.val();
	    var pwd = $p.val();
	    var data = {
	        "logname":uname,
	        "password":pwd
	      };
	    $.ajax({
	      type: 'POST',
	      url: gloPath+'log.php',
	      dataType: 'json',
	      data: data,
	      timeout: 5000,
	      beforeSend: function() {
	            fnLoading(1);
	        $this.html("登录中...");
	      },
	      success: function(data){
	            fnLoading(0);
	        // alert(data);
	        if (data.status==200) {
	          user = uname;
	          window.location.reload();
	        }
	        else{
	          $prompt.html(data.status);
	          $(".pwd").focus().val('');
	          $this.html("登录");
	        }
	      },
	      error: function (hd,msg) {
	            fnLoading(0);
	        $prompt.html("无法连接网络");
	        $this.html("登录");
	        alert(msg);
	      }
	    });
	  }
	}
	function fnReg ($u,$p,$prompt,$this,$type) {
	  console.log('注册');
	  var uname = $u.val();
	  var pwd = $p.val();
	  var count = $type.length;
	  if (uname.length<1||pwd.length<1) {
	    $prompt.html("请将信息输入完整");
	  }else if (count==0) {
	      $prompt.html("请选择要注册的类型");
	  }else{
	    var type = $type.attr('value');
	    var data = {
	      "logname":uname,
	      "password":pwd,
	      "utype":type
	    };
	    $.ajax({
	      type: 'POST',
	      url: gloPath+'reg.php',
	      dataType: 'json',
	      data: data,
	      timeout: 5000,
	      beforeSend: function() {
	            fnLoading(1);
	        $this.html("注册中...");
	      },
	      success: function(data){
	            fnLoading(0);
	        if (data.status==200) {
	          alert('注册成功');
	          window.location.reload();
	        }else{
	          $prompt.html(data.status);
	          $(".pwd").focus().val('');
	          $this.html("注册");
	        }
	      },
	      error: function (hd,msg) {
	            fnLoading(0);
	        $prompt.html("无法连接网络");
	        $this.html("注册");
	        alert(msg);
	      }
	    });
	  }
	}
// 评论页
	$("#commentPage").on('click', function(e) {
	    var t = e.target;
	    var $t = $(t);
	    var $this = $(this);
	    fnHide();
	    fnDegray();
	    if ($t.is('.back')) {
	    	window.history.go(-1);
	    }else if ($t.is('.refresh')) {
	    	window.location.reload();
	    }else if ($t.is('.say')) {
	    	if ($t.parent().children('#reply_more').length<1) {
	    		$t.parent().append($("#reply_more").clone());
	    	}
	    	fnGray($t);
	    	fnShow($t.parent().children("#reply_more"),'fadeIn');
	    }else if ($t.is('.agree')) {
	    	var $wp = $t.closest('.wrap2');
	    	var comid = $wp.attr('data-comid');;
	    	var data = {'comid':comid};
	    	fnAddAgree(data,$wp);
	    }else if ($t.is('.reply')) {
	    	if(fnIsLogged()){
		    	var $wp = $('#wpComment');
	    		var $say = $wp.children('textarea');
		    	var $p = $wp.children('.prompt');
		    	$wp.children('.close').click(function() {
			    	$wp.fadeOut();
			    	$say.val('');
		    	});
		    	console.log($t.closest('.wrap2').find('.commenter>div:first-child').text());
		    	var commenter = $t.closest('.wrap2').find('.commenter .usr').text();
	    		$say.attr('placeholder', '回复'+commenter+'：');;
		    	$wp.children('.submitComment').attr('disabled', 'true');
				$wp.children('textarea').bind('input propertychange', function() {
					if ($(this).val().length<1) {
						$(this).parent().children('.submitComment').attr('disabled', 'true');
					}else{
						$(this).parent().children('.submitComment').removeAttr('disabled');
					}
				});
		    	$wp.children('.submitComment').click(function() {
			    	if ($say.val()=='') {
			    		$p.html('请输入评论'); 
			    	}else {
			    		var comid = $t.closest('.wrap2').attr('data-comid');;
			    		var data = {'say':$say.val(),'to_typ':'comment','to_id':comid};
			    		fnAddComment(data,$p,$t.closest('.wrap2'));
			    	}
		    	});
		    	$wp.fadeIn();
		    	$say.focus();
	    	}else {
				if (confirm("请先登录")) {
					gloPath = '../php/';
					fnPopLog();
				}
	    	}
	    }else if ($t.is('.report')) {
	    	if(fnIsLogged()){
		    	var $wp = $('#wpReport');
	    		var $say = $wp.children('textarea');
		    	var $p = $wp.children('.prompt');
		    	$wp.children('.close').click(function() {
			    	$wp.fadeOut();
			    	$say.val('');
		    	});
		    	$wp.children('.submitReport').attr('disabled', 'true');
		    	$wp.children('.submitReport').click(function() {
			    	if ($say.val()=='') {
			    		$p.html('请输入举报原因'); 
			    	}else {
			    		var comid = $t.attr('data-comid');;
			    		var data = {'reason':$say.val(),'on_type':'comment','on_id':comid};
			    		fnAddReport(data,$p);
			    	}
		    	});
		    	$wp.fadeIn();
		    	$say.focus();
	    	}else {
				if (confirm("请先登录")) {
					gloPath = '../php/';
					fnPopLog();
				}
	    	}
	    }else if ($t.is('.delete')) {
			if (confirm("删除评论？")) {
				var data = {
					'comid':$t.closest('.wrap2').attr('data-comid')
				}
				fnDeleteComment(data,$t.closest('.wrap2'));
			}
	    }
	});
	$('#wpReport').children('textarea').bind('input propertychange', function() {
		if ($(this).val().length<1) {
			$(this).parent().children('.submitReport').attr('disabled', 'true');
		}else{
			$(this).parent().children('.submitReport').removeAttr('disabled');
		}
	});
	var $lastGrayed;
	function fnGray($this){
		$this.css('background-color', '#eee');
		$lastGrayed = $this;
	}function fnDegray(){
		if ($lastGrayed!=null) {
			$lastGrayed.css('background-color', 'initial');
			$lastGrayed = null;
		}
	}

// 举报
	function fnAddReport(data,$p) {
	    $.ajax({
	        url: '../php/report_add.php',
	        type: 'POST',
	        dataType: 'json',
	        data: data,
	        beforeSend: function() {
	            fnLoading(1);
	        },
	        success: function(data) {
	            fnLoading(0);
	            if (data.status == 200) {
	            	alert("举报成功！");
	            	$p.parent().children('textarea').val('');
	            	$p.parent('.report_wrap').fadeOut();
	            } else {
	                $p.html(data.status);
	            }
	        },
	      error: function (hd,msg) {
	            fnLoading(0);
	        alert(msg);
	      },
	    });
	}
// 评论
	function fnAddComment(data,$p,$wp='') {
	    $.ajax({
	        url: '../php/comment_add.php',
	        type: 'POST',
	        dataType: 'json',
	        data: data,
	        beforeSend: function() {
	            fnLoading(1);
	        },
	        success: function(data) {
	            fnLoading(0);
	            if (data.status == 200) {
	            	if ($wp!='') {
	            		var wp = '<div class="wrap2" data-comid="'+data.comid+'">\
	            		<table>\
		                  <tr class="r1">\
		                      <td><div class="imgWrap"><img src="'+data.uimg+'" alt=""></div></td>\
		                      <td class="commenter"><div>'+data.usr+'</div><div class="time">'+data.tim+'</div></td>\
		                      <td class="agreeN">0</td>\
		                      <td><i class="icon-thumb_up agree"></i></td>\
		                  </tr>\
		                  <tr class="r2">\
		                    <td></td>\
		                    <td class="say" colspan="2">'+data.say+'</td>\
		                  </tr>\
		                  </table>\
		                </div>';
		                $wp.append(wp);
		                fnResizeImg($wp.children('.wrap2').first().find('img'));
	            	}
	            	// alert("举报成功！");
	            	$p.parent().children('textarea').val('');
	            	$p.parent('.report_wrap').fadeOut();
	            } else {
	                $p.html(data.status);
	            }
	        },
	      error: function (hd,msg) {
	            fnLoading(0);
	        alert(msg);
	      },
	    });
	}
	function fnDeleteComment(data,$wp='') {
	    $.ajax({
	        url: '../php/com_delete.php',
	        type: 'POST',
	        dataType: 'json',
	        data: data,
	        beforeSend: function() {
	            fnLoading(1);
	        },
	        success: function(data) {
	            fnLoading(0);
	            if (data.status == 200) {
	            	$wp.slideUp(function(){
	            		$(this).remove();
	            	});
	            }
	        },
	      error: function (hd,msg) {
	            fnLoading(0);
	        alert(msg);
	      },
	    });
	}
// 点赞
	function fnAddAgree(data,$wp) {
	    $.ajax({
	        url: '../php/com_add_agree.php',
	        type: 'POST',
	        dataType: 'json',
	        data: data,
	        beforeSend: function() {
	            fnLoading(1);
	        },
	        success: function(data) {
	            fnLoading(0);
	            if (data.status == 200) {
	            	$wp.find('.agreeN').text(data.agrN);
	            	$wp.find('.agree').addClass('on');
	            } else {
	                // $p.html(data.status);
	            }
	        },
	      error: function (hd,msg) {
	            fnLoading(0);
	        alert(msg);
	      },
	    });
	}
// 公用函数
	var $lastUnscroll = null;
	function fnUnscroll($this=$("body")){
		if ($this.is('body')) $this.css({'position':'fixed','overflow':'hidden'});
		else $this.css('overflow', 'hidden');
		$lastUnscroll = $this;
	}
	function fnRescroll(){
		// console.log('恢复滚动');
		if ($lastUnscroll!=null) {
			// console.log($lastUnscroll[0]);
			if ($lastUnscroll.is('body')) $lastUnscroll.css({'position':'relative','overflow':'auto'});
			else $lastUnscroll.css('overflow', 'auto');
			$lastUnscroll = null;
		}
	}
	var lastShow = {};
	function fnShow($this,action=''){
		switch (action) {
			case 'fadeIn':
				$this.fadeIn();
				break;
			case 'slideDown':
				$this.slideDown();
				break;
			default:
				$this.show();
				break;
		}
		lastShow = {'obj':$this,'action':action};
	}
	function fnHide(){
		if (lastShow['obj']!=null) {
			switch (lastShow['action']) {
				case 'slideDown':
					lastShow['obj'].slideUp();
					break;
				case 'fadeIn':
					lastShow['obj'].fadeOut();
					break;
				default:
					lastShow['obj'].hide();
					break;
			}
			lastShow = {};
		}
	}
	function fnIsPC() {
	    var userAgentInfo = navigator.userAgent;
	    var Agents = ["Android", "iPhone",
	                "SymbianOS", "Windows Phone",
	                "iPad", "iPod"];
	    var flag = true;
	    for (var v = 0; v < Agents.length; v++) {
	        if (userAgentInfo.indexOf(Agents[v]) > 0) {
	            flag = false;
	            break;
	        }
	    }
	    return flag;
	}
	function fnLoading (on) {
		if(on) $("#loading").fadeIn();
		else $("#loading").fadeOut();
	}
})

