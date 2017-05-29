$(function(){
// 顶部导航
	var lastShow = {};
	var flagNowPage = $("#pag").val();
	var gloPath = 'php/';
	$("#nav_main .user").on('click', function() {
		if (fnIsLogged()) {
			fnShowUser();
			flagNowPage = 1;
		    fnSwitchPage();
	  	}else {
	  		gloPath='php/';
	  		fnPopLog();
	  	}
	});
	$("#nav_main .logo").on('click', function() {
		// console.log(flagMainImgResized);
	    fnShowMain();
	    $("#main").animate({'left':'0vw','opacity':1},200,function () {
		    $("#userInfo").hide();
	    });
	    flagNowPage = 0;
	    fnSwitchPage();
	});
	// 侧菜单显示搜索
	var flagLastBlur;
	function fnShowSearchResult(acti,cont){
		if (cont=='') {
			$("#contResult").parent('.list_result').css('display', 'none');
		}else{
			$("#contResult").parent('.list_result').css('display', 'block');
		}
		$("#sideMenu .result").show().animate({'left': '0'});
		$("#sideMenu .choose").animate({'left': '-100vw','opacity': '0'},function(){
			$("#sideMenu .close").removeClass('icon-angle-up').addClass('icon-angle-left').off('click').on('click', function(e) {
				e.preventDefault();
				fnReturnSearch();
			});
		});
	}
	function fnReturnSearch(){
		$("#sideMenu .choose").animate({'left': '0','opacity': '1'});
		$("#sideMenu .result").animate({'left': '100vw'},function(){$(this).hide();},function(){
			$("#sideMenu .close").removeClass('icon-angle-left').addClass('icon-angle-up').off('click').on('click', function(e) {
				$("#sideMenu").slideUp();
				fnDeBlur();
			});
		});
	}
	function fnSearch(data){
    	$.ajax({
	      type: 'POST',
	      url: 'php/search.php',
	      dataType: 'json',
	      data: data,
	      timeout: 5000,
	      beforeSend: function() {
	      	fnLoading(1);
	      },
	      success: function(data){
	      	var kw = data.kw;
	      	fnLoading(0);
			$("#actiResult").empty();
			acti = data['acti'];
            acti.forEach( function(element, i) {
            	var list_acti = 
	            	'<a href="'+acti[i]['pag']+'">\
						<div class="col-xs-2">\
							<div class="imgWrap"><img src="'+acti[i]['cover']+'"></div>\
						</div>\
						<div class="col-xs-10">\
							<div class="r1">'+acti[i]['title']+'</div>\
							<div class="r2">'+acti[i]['abstract']+'</div>\
						</div>\
					</a>';
					kw.forEach(function(element, i){
						list_acti = list_acti.replace(kw[i],'<em>'+kw[i]+'</em>');
					});
				$("#actiResult").prepend(list_acti);
            });
			if (data['cont']!=null) {
				$("#contResult").empty();
				cont = data['cont'];
	            cont.forEach( function(element, i) {
	            	var list_cont = 
		            	'<a href="'+cont[i]['pag']+'">\
							<div class="col-xs-2">\
								<div class="imgWrap"><img src="'+cont[i]['img']+'"></div>\
							</div>\
							<div class="col-xs-10">\
								<div class="r1">'+cont[i]['des']+'</div>\
								<div class="r2">'+cont[i]['tit']+'</div>\
							</div>\
						</a>';
					kw.forEach(function(element, i){
						list_cont = list_cont.replace(kw[i],'<em>'+kw[i]+'</em>');
					});
					$("#contResult").prepend(list_cont);
	            });
	            fnShowSearchResult('acti','cont');
			}else fnShowSearchResult('acti','');
	      },
	      error: function (hd,msg) {
            fnLoading(0);
	      }
    	});
	}
	function fnKwSearch(){
	    var data = {
	    	'stype':'keyword',
	    	'kw':$("#input_kw").val()
	    }
	    fnSearch(data);
	}
	$("#nav_main .search").on('click', function() {
		$("#sideMenu").slideDown();
		$("#sideMenu .close").on('click', function() {
			$("#sideMenu").slideUp();
			fnDeBlur();
		});
		if (flagNowPage==0) {
			fnBlurUnderLayer($("#main"));
		}else {
			fnBlurUnderLayer($("#userInfo"));
		}
		$("#sideMenu .type").on('click', function(e) {
			e.stopPropagation();
			$t = $(e.target);
			if ($t.is('a')) {
				console.log($t.text());
				// $("#input_kw").val($t.text());
				var data = {
					'stype':'type',
					'tname':$t.text()
				};
				fnSearch(data);
			}
		});
		$("#sideMenu .history").on('click', function(e) {
			e.stopPropagation();
			$t = $(e.target);
			if ($t.is('a')) {
				console.log($t.text());
				$("#input_kw").val($t.text());
	            fnKwSearch();
			}
		});
	// 搜索
		$("#input_kw").on('keypress',function(e) {  
	        var keycode = e.keyCode;  
	        var searchName = $(this).val();  
	        if(keycode=='13') {  
	            e.preventDefault();
	            // alert("搜索");
	            //请求搜索接口
	            fnKwSearch();
	        }  
	    });
	    $("#sideMenu .btn_search").click(function(e) {
	    	fnKwSearch();
	    });
	    $("#sideMenu .cancel").click(function(e) {
	    	$("#input_kw").val('');
	    	fnReturnSearch();
	    });
	});

	// $("#sideMenu").on('scroll', function(e) {
	// 	e.preventDefault();
	// 	e.stopPropagation();
	// 	$("html").css('overflow-y', 'hidden');
	// 	/* Act on the event */
	// });
	function fnBlurUnderLayer (obj) {
		obj.css({
			'position': 'fixed',
			'-webkit-filter':'blur(5px)',
			'-mos-filter':'blur(5px)',
			'-ms-filter':'blur(5px)',
			'-o-filter':'blur(5px)',
			'filter':'blur(5px)'
		});
		flagLastBlur = obj;
	}
	function fnDeBlur () {
		if (flagLastBlur!=null) {
			flagLastBlur.css({
				'position': 'absolute',
				'-webkit-filter':'none',
				'-mos-filter':'none',
				'-ms-filter':'none',
				'-o-filter':'none',
				'filter':'none'
			});
			flagLastBlur = null;
		}
	}
	function fnShowMain () {
		$("#main").show();
	    $("#publish_trigger").fadeIn();
  		if (!flagMainImgResized) {
			$("#main img").each(function() {
			    fnResizeImg($(this));
			});
  			flagMainImgResized == true;
  		}
	}
	function fnShowUser () {
  		// console.log('准备显示userInfo');
  		if (!flagHeadImgResized) {
	  		// console.log('准备自适应头像');
  			var $headImg = $("#userInfo").find('.headImg').children('img');
  			// console.log($headImg.get(0).complete);
  			if ($headImg.get(0).complete) {fnResizeImg($headImg);}
  			$headImg.get(0).onload = function(){
	  		// console.log('准备fnResizeImg');
	  		fnResizeImg($headImg);}
  			flagHeadImgResized == true;
  		}
	    $("#userInfo").fadeIn();
	    $("#main").animate({'left':'-100vw','opacity':0},500,function () {
	    	$("#main").fadeOut();
	    });
	    console.log();
	    if ($(".nav_me .pub")[0]==null) {$(".nav_me .act").click();}
	    else {
	    	$(".nav_me .pub").click();
	    }
	    // $("#userInfo .sec_publish").show();
	    $("#publish_trigger").fadeOut();
	}
	function fnIsLogged () {
		// console.log($("#userInfo")[0]);
		// console.log($("#usr").val());
		return ($("#usr").val()=='')?false:true;
	}
	function fnPopLog(){
		fnUnscroll($("body"));
		fnShow($("#sep_reg_log"),'fadeIn');
	}
	// 记录停留页面
	function fnSwitchPage(){
		var data = {'page':flagNowPage};
    	$.ajax({
	      type: 'POST',
	      url: 'php/page_log.php',
	      dataType: 'json',
	      data: data,
	      timeout: 5000,
	      beforeSend: function() {
	      },
	      success: function(data){
	      	$("#pag").val(data.page);
	      },
	      error: function (hd,msg) {
            fnLoading(0);
	      }
    	});
    }
// 个人中心
	var $showImg;
    	// $("#inputImg").on('click', function(event) {
				 //    	// alert('预览头像');
    	// 	// event.preventDefault();
				 //    	fnUnscroll($("body"));
				 //    	$("#showImg").fadeIn();
    	// });
	$("#editInfo").on('click', function(e) {
	    e.preventDefault();
	    e.stopPropagation();
	    var $t = $(e.target);
	    fnShow($t.next('.edit_list'), 'fadeIn');
	    // $showImg = $("#showImg");
	});
    $("#changeHead").on('click', function() {
        // alert('预览头像2');
        $("#changeHead").off('change').on('change', function() {
            $("#showImg").fadeIn();
            // alert('预览头像1');
            fnPreviewImgFile(this.files, $("#showImg").find('.imgWrap'), 1);
        });
        $("#reInputImg").off('change').on('change', function() {
            fnPreviewImgFile(this.files, $("#showImg").find('.imgWrap'), 1);
            // $("#showImg").fadeIn();
        });
    });
	$("#editInfo").next('.edit_list').on('click', function(e) {
	    // e.stopPropagation();
	    var $t = $(e.target);
        if ($t.is('.change_headImg')) {
            $("#changeHead").click();
            // $("#showImg").fadeIn();
        } else if ($t.is('.change_name')) {
            $uname = $("#userInfo").find('.uname');
            $("#editUser").find('input[name="uname"]').val($uname.text());
            fnShowEdit('uname');
        } else if ($t.is('.change_school')) {
            $school = $(this).parent('.sec_uinfo').find('.school');
            console.log($school.text());
            console.log($("#editUser").find('input[name="school"]'));
            $("#editUser").find('input[name="school"]').val($school.text());
            fnShowEdit('school');
        } else if ($t.is('.change_intro')) {
            $intro = $(this).parent('.sec_uinfo').find('.intro');
            $("#editUser").find('input[name="intro"]').val($intro.text());
            fnShowEdit('intro');
        }
        /* Act on the event */
    });
	$("#userInfo").on('click', function(e) {
	    // e.preventDefault();
	    e.stopPropagation();
	    t = e.target;
	    $t = $(t);
	    $this = $(this);
	    // console.log(lastShow);
	    fnHide();
	    console.log($t.text());
	    if ($t.is('.btn_logout')) {
	        $.ajax({
	            type: 'POST',
	            url: 'php/logout.php',
	            dataType: 'json',
	            data: {},
	            timeout: 5000,
	            beforeSend: function() {
	                fnLoading(1);
	            },
	            success: function(data) {
	                fnLoading(0);
	                alert(data.status);
	                window.location.reload();
	            },
	            error: function(hd, msg) {
	                fnLoading(0);
	                alert('注销失败');
	            }
	        });
	    } else if ($t.is('.del_acti')) {
	        if (confirm('删除活动直播')) {
	            gloPath = 'php/';
	            var aid = $t.closest('.list_acti').attr('data-aid');;
	            var data = { 'aid': aid };
	            fnDelActi(data, 'index', $t);
	        }
	    } else if ($t.is('.cancel_focus')) {
	        if (confirm('取消关注')) {
	            gloPath = 'php/';
	            var aid = $t.closest('.list_acti').attr('data-aid');;
	            var data = { 'aid': aid };
	            fnCancelFocus(data,$t,'index');
	        }
	    }
	});
    $('#userInfo').find('.headImg').click(function(e) {
    	e.stopPropagation();
    	$("#changeHead").click();
    });

	function fnShowEdit (type) {
		$("#editUser").fadeIn();
    	$("#editUser>.edit_"+type).show();
	}
	$("#showImg").on('click', function(e) {
	    t = e.target;
	    $t = $(t);
	    $this = $(this);
	    console.log($t.text());
	    if ($t.is('.close')) {
	    	fnRescroll();
		    $this.fadeOut();
		} else if ($t.is('.reInput')) {
			$("#reInputImg").click();
		}
	});
	var flagHeadImgResized = false;
	var flagMainImgResized = false;
	// console.log($("#pag").val());
	if ($("#pag").val()==1) {
		$("#main").hide();
		$(".nav_me .pub").click();
		fnShowUser();
	}else{
		$("#userInfo").hide();
		fnShowMain();
	}
	var $uname,$school,$intro;
// 修改个人信息 昵称、密码、头像
	$("#editUser").on('click', function(e) {
		// event.preventDefault();
	    t = e.target;
	    $t = $(t);
	    $this = $(this);
	    // console.log(lastShow);
	    if ($t.is('.close')) {
	    	fnRescroll();
	    	$this.children().hide();
		    $this.fadeOut();
	    }else if ($t.is('.submitName')) {
	    	// 确认修改
	    	var data = {'type':'uname','uname':$this.find('input[name="uname"]').val()};
	    	// console.log(data);
	    	fnEditUser(data,$uname);
	    }else if ($t.is('.submitSchool')) {
	    	// 确认修改
	    	var data = {'type':'school','school':$this.find('input[name="school"]').val()};
	    	// console.log(data);
	    	fnEditUser(data,$school);
	    }else if ($t.is('.submitIntro')) {
	    	// 确认修改
	    	var data = {'type':'intro','intro':$this.find('textarea[name="intro"]').val()};
	    	// console.log(data);
	    	fnEditUser(data,$intro);
	    }else if (t==this){
	    	$this.children().hide();
		    $this.fadeOut();
	    }
	});
	function fnEditUser(data,$info){
	    $.ajax({
	      url: 'php/user_edit.php',
	      type: 'POST',
	      dataType: 'json',
	      data: data,
	      beforeSend: function() {
	            fnLoading(1);
	      },
	      success: function (data) {
	            fnLoading(0);
	        if (data.status == 200) {
	        	switch (data.type) {
	        		case 'uname':
				        alert('成功修改名字');
				    	$info.html(data.new);
	        			break;
	        		case 'school':
				    	$info.html(data.new);
	        			break;
	        		case 'intro':
				    	$info.html(data.new);
	        			break;
	        		default:
	        			break;
	        	}
			    fnRescroll();
		    	$this.children().hide();
			    $this.fadeOut();
	        }else alert(data.status);
	      },
	      error: function (hd,msg) {
	            fnLoading(0);
		        alert(msg);
	      },
	    });
	}

// 登录注册
	$("#sep_reg_log").on('click', function(e) {
		e.preventDefault();
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
	    $prompt = $this.find('.reg_p');
	    fnReg($this,$prompt,$t);
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
	        	// if (data.type==1) {
	        	// 	// location.assign(gloPath+"../../lit_gzt/php/login-successful.php");
	        	// }else {
	        	// 	window.location.reload();
	        	// }
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
	function fnReg($reg, $prompt, $btn) {
	    console.log('注册');
	    var logname = $reg.find("input[name='rname']").val();
	    var $pwd = $reg.find("input[name='rpwd']");
	    var pwd = $pwd.val();
	    var school = $reg.find("input[name='school']").val();
	    var s_q = $reg.find("input[name='s_q']").val();
	    var s_a = $reg.find("input[name='s_a']").val();
	    var type = $reg.find("select[name='rtype'] option:selected").val();
	    // var count = $type.length;
	    if (logname.length < 1 || pwd.length < 1) {
	        $prompt.html("请将信息输入完整");
	    } else {
	        // var type = $type.attr('value');
	        var data = {
	            "logname": logname,
	            "password": pwd,
	            "utype": type,
	            "school": school,
	            "s_q": s_q,
	            "s_a": s_a
	        };
	        $.ajax({
	            type: 'POST',
	            url: gloPath + 'reg.php',
	            dataType: 'json',
	            data: data,
	            timeout: 5000,
	            beforeSend: function() {
	                fnLoading(1);
	                $btn.html("注册中...");
	            },
	            success: function(data) {
	                fnLoading(0);
	                if (data.status == 200) {
	                    // alert('注册成功');
		        		window.location.reload();
			        	// if (data.type==1) {
			        	// 	location.assign(gloPath+"../../lit_gzt/php/login-successful.php");
			        	// }else {
			        	// 	window.location.reload();
			        	// }
	                } else {
	                    $prompt.html(data.status);
	                    $pwd.focus().val('');
	                    $btn.html("注册");
	                }
	            },
	            error: function(hd, msg) {
	                fnLoading(0);
	                $prompt.html("无法连接网络");
	                $btn.html("注册");
	                alert(msg);
	            }
	        });
	    }
	}
// 活动页
	var flagResizeImg = true;
	var $tit,$abs,$typ,$dat;
	$("#activity").on('click', function(e) {
	    var t = e.target;
	    var $t = $(t);
	    var $this = $(this);
	    var is_modify = false;
	    // console.log(t);
	    if (t.nodeName!='INPUT'&&t.nodeName!='TEXTAREA') {fnHide();}
	    $tit = $this.find('.top-text');
	    $abs = $this.find('.abs');
	    $typ = $this.find('.type');
	    $dat = $this.find('.date');
	    // console.log($typ);
	    if ($t.is('.leave')) {
	    	location.assign("../index.php");
	        console.log(t.nodeValue + ' ' + t.nodeType);
	    } else if ($t.is('.moreEdit')) {
	    	fnShow($t.next(),'fadeIn');
	    } else if ($t.is('.logo')) {
	    	console.log('logo');
	    	location.assign("../index.php");
	    } else if ($t.is('.delete')) {
	    	if(confirm('确定删除活动？')){
				gloPath = '../php/';
	    		var aid = $this.children('input').val();
		    	var data = {'aid':aid};
		    	fnDelActi(data,'activity','');
		    	// console.log(gloDelActiStatus);
		    	// if (gloDelActiStatus) {
		    	// }else {
		    	// 	alert("活动未完全删除");
		    	// }
	    	}
	    } else if ($t.is('.end')) {
	    	if(confirm('确定关闭活动直播？')){
	    		var data = {'aid':$this.children('input').val()};
			    $.ajax({
			        url: '../php/acti_close_live.php',
			        type: 'POST',
			        dataType: 'json',
			        data: data,
			        beforeSend: function() {
			            fnLoading(1);
			        },
			        success: function(data) {
			            fnLoading(0);
			            if (data.status == 200) {
			            	alert("直播关闭成功");
			            	location.reload();
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
	    } else if ($t.is('.start')) {
	    	if(confirm('确定开启活动直播？')){
	    		var data = {'aid':$this.children('input').val()};
			    $.ajax({
			        url: '../php/acti_start_live.php',
			        type: 'POST',
			        dataType: 'json',
			        data: data,
			        beforeSend: function() {
			            fnLoading(1);
			        },
			        success: function(data) {
			            fnLoading(0);
			            if (data.status == 200) {
			            	alert("直播已成功开启，将于1天后关闭，届时请手动重新开启");
			            	location.reload();
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
	    } else if ($t.is('.edit')) {
	    	var $edi = $("#editActivity");
	    	var tit = $tit.html();
	    	var abs = $abs.html();
	    	var typ = $typ.html();
	    	var dat = $dat.html();
	    	// console.log($typ.html());
	    	var cov = $this.find('.top').css('background-image');
		    // 获取背景url路径
		    // if (fnIsPC()) {cov = cov.split('"')[1].split('"')[0];}
		    // else cov = cov.split('(')[1].split(')')[0];
		    $edi.find('input[name="tit"]').val(tit);
		    var abs_text = abs.replace(/<br>/ig,"\r\n").replace(/&nbsp;/g," ");
		    $edi.find('textarea[name="abs"]').val(abs_text);
		    $edi.find('input[name="typ"]').val(typ);
		    $edi.find('input[name="dat"]').val(dat);
		    console.log(cov);
		    $edi.find('.cov').css('background-image',cov);
		    console.log($edi.find('.cov').css('background-image'));
		    // if (flagResizeImg) {console.log('自适应cover'); fnResizeImg($edi.find('img'));flagResizeImg=false;}
			fnUnscroll($("body"));
	    	$edi.fadeIn();
	    } else if ($t.is('.add')) {
	        $("#inputContImg").click();
	    } else if ($t.is('.refresh')) {
	        window.location.reload();
	    } else if ($t.is('.add_focus')) {
	    	if (fnIsLogged()) {
		    	if ($t.is('.on')) {
		    		var aid = $t.closest('#activity').children('input').val();
		    		$focN = $t.prev('sup');
		    		var data = {'aid':aid};
		    		fnCancelFocus(data,$t,'activity');
		    	} else {
		    		var aid = $t.closest('#activity').children('input').val();
		    		$focN = $t.prev('sup');
		    		var data = {'aid':aid};
		    		fnAddFocus(data,$t);
		    	}
	    	}else {
				if (confirm("请先登录")) {
					gloPath = '../php/';
					fnPopLog();
				}
	    	}
	    } else if ($t.is('.more')) {
	    	fnShow($t.next(),'fadeIn');
	    } else if ($t.is('.like')) {
	    	if(fnIsLogged()){
			    $likN = $t.next('sup');
		    	if ($t.is('.on')) {
			    	var cid = $t.closest('.cont-body').children('input').val();
			    	var data = {'cid':cid};
		    		fnCancelLike(data,$t);
		    	} else {
			    	var cid = $t.closest('.cont-body').children('input').val();
			    	var data = {'cid':cid};
		    		fnAddLike(data,$t);
		    	}
	    	}else {
				if (confirm("请先登录")) {
					gloPath = '../php/';
					fnPopLog();
				}
	    	}
	    } else if ($t.is('.comment')) {
	    	if(fnIsLogged()){
		    	// 发表评论：1.跳转并获取全部评论；2.输入评论
		    	// 简化：直接评论
			    $comN = $t.next('sup');
			    $t.closest('.interact-icons').next().find('input').val('');
			    fnShow($t.closest('.interact-icons').next(),'slideDown');
	    	}else {
				if (confirm("请先登录")) {
					gloPath = '../php/';
					fnPopLog();
				}
	    	}
	    } else if ($t.is('.submitCom')) {
	    	var cid = $t.closest('.cont-body').children('input').val();
	    	var aid = $('#activity').children('input').val();
	    	var say = $t.parent().prev().children('input').val();
	    	say = say.replace(/(\r)*\n/g,"<br>").replace(/\s/g,"&nbsp;");
	    	var data = {'cid':cid,'say':say};
	    	fnAddCom(data,$t.closest('.cont-body'));
	    }else if ($t.text()=='修改') {
	      if (!is_modify) {
	        var cid = $t.closest('.cont-body').children('input').val();
	        console.log('需修改的内容id为：'+cid);
	        var $p = $t.closest('.cont-body').children('.guts').children('p');
	        var h = $p.height()+30;
	        oriCap = $p.html();  //original caption
	        console.log('修改的原内容为：'+oriCap);
	        // var c = content.replace(new RegExp("<br>","g"),"\n")  oninput="this.style.height=this.scrollHeight + \'px\'"
	        var oriCap_text = oriCap.replace(/<br>/ig,"\r")
	        // <a id="cancelModify" href="javascript:void(0);">取消</a>
	        var editCap = '<textarea style="overflow-y:auto;height: '+h+'px;">'+oriCap_text+'</textarea>\
	          <div id="btn-modifyCont"><a href="javascript:void(0);" class="btn">确定</a><div>';
	        $p.html(editCap);
	        $p.children('textarea').focus();
	        var $btn = $('#btn-modifyCont').children('a');
	        //确认更新文字描述
	        $btn.click(function() {
	          var newTex = $btn.parent().prev('textarea').val();
	          var newTex_html = newTex.replace(/(\r)*\n/g,"<br>").replace(/\s/g,"&nbsp;");
	          console.log(newTex);
	          var data = {
	            'cid':cid,
	            'newTex':newTex_html
	          };
	          fnEditCont(data,$p);
	        });
	        is_modify = true;
	      }
	      // 取消更改
	      // $p.html(oriCap);
    } else if ($t.text() == '删除') {
	    	if(confirm('确定删除图文？')){
	    		var cid = $t.closest('.cont-body').children('input').val();
	    		var data = {'cid':cid};
	    		fnDelCont(data,$t);
	    	}
	    } else if ($t.text() == '举报') {
	    	if(fnIsLogged()){
		    	var $report = $t.closest('.time-location').next('.report_wrap');
	    		var $rea = $report.children('textarea');
		    	var $p = $report.children('.prompt');
		    	$report.children('.submitReport').attr('disabled', 'true');
		    	$report.children('.close').click(function() {
			    	$report.fadeOut();
		    	});
		    	$report.children('textarea').bind('input propertychange', function() {
		    		if ($(this).val().length<1) {
		    			$report.children('.submitReport').attr('disabled', 'true');
		    		}else{
		    			$report.children('.submitReport').removeAttr('disabled');
		    		}
		    	});
		    	$report.children('.submitReport').click(function() {
			    	if ($rea.val()=='') {
			    		$p.html('请输入举报原因'); 
			    	}else {
			    		var cid = $t.closest('.cont-body').children('input').val();
			    		var data = {'reason':$rea.val(),'on_type':'content','on_id':cid};
			    		fnAddReport(data,$p);
			    	}
		    	});
		    	$report.fadeIn();
		    	$rea.focus();
	    	}else {
				if (confirm("请先登录")) {
					gloPath = '../php/';
					fnPopLog();
				}
	    	}
	    } else if ($t.is('.moreCom')) {
	    } else if ($t.is('img')) {
	        // console.log($t.attr('src'));
	        var src = $t.attr('src');
	        $lastEnlargeImg = $t;
		    $prev = $t.parent().prev().children('img');
	    	$next = $t.parent().next().children('img');
	        $('#largeImg>img').attr('src', src);
	        // fnUnscroll($("body"));
	        $('#largeWrap').fadeIn();
    		$("#largeWrap").find('.prev').show();
    		$("#largeWrap").find('.next').show();
	    	if ($prev.length == 0) {
	    		$("#largeWrap").find('.prev').hide();
	    	}
	    	if ($next.length == 0) {
	    		$("#largeWrap").find('.next').hide();
	    	}
	    } else {
	        // console.log(e.target.nodeName);
	    }
	});
	$("#addCont").click(function(e) {
		e.stopPropagation();
	    var t = e.target;
	    var $t = $(t);
	    var $this = $(this);
	    if ($t.is('.close')) {
	    	fnRescroll();
		    $this.fadeOut();
		    $("#addCont_imgsWrap").empty();
		}
	});
	$("#editActivity").click(function(e) {
		e.stopPropagation();
		$("#editActivity").find('.cov').on('click', function() {
			var aid = $("#activity").children('input').val();
			$("#inputCover").prev('input').val(aid);
			$("#inputCover").click();
			$("#inputCover").off('change').on('change',function() {
				// $("#editActivity").find('.cov').css('background-image', 'value');
				$wrap = $("#editActivity").find('.imgWrap');
				console.log($wrap.css('background-image'));
		    	fnPreviewImgFile(this.files,$wrap,1);
			});
		});
	    var t = e.target;
	    var $t = $(t);
	    var $this = $(this);
	    if ($t.is('.close')) {
	    	fnRescroll();
		    $this.fadeOut();
		}else if ($t.is('.btn')) {
		    var aid = $("#addCont>input[name='aid']").val();
		    var tit = $this.find("input[name='tit']").val();
		    var abs = $this.find("textarea[name='abs']").val();
		    var tid = $this.find("select[name='tid']").val();
		    var dat = $this.find("input[name='dat']").val();
		    var abs_html = abs.replace(/(\r)*\n/g,"<br>").replace(/\s/g,"&nbsp;");
		    var data = {
		      'aid':aid,
		      'tit':tit,
		      'abs':abs_html,
		      'tid':tid,
		      'dat':dat
		    };
		    $.ajax({
		      url: '../php/acti_edit.php',
		      type: 'POST',
		      dataType: 'json',
		      data: data,
		      beforeSend: function() {
		            fnLoading(1);
		      },
		      success: function (data) {
		            fnLoading(0);
		        if (data.status == 200) {
		          alert('成功修改直播主题');
		          $tit.text(data.updTit);
		          var updAbs_html = data.updAbs.replace(/(\r)*\n/g,"<br>").replace(/\s/g,"&nbsp;");
		          $abs.html(updAbs_html);
		          $typ.html(data.updTyp);
			       $this.fadeOut();
			       fnRescroll();
			       console.log($("#inputCover").val());
			       if ($("#inputCover").val()!='') {
			       	$("#submitCover").click();
			       }
		        }else alert(data.status);
		      },
		      error: function (hd,msg) {
		            fnLoading(0);
		        alert(msg);
		      },
		    });
		}
	});
	$("#editActivity").children('form').hide();
	function fnEditCont (data,$p) {
          $.ajax({
            url: '../php/cont_edit.php',
            type: 'POST',
            dataType: 'json',
            data: data,
            timeout: 5000,
            beforeSend: function() {
            	fnLoading(1);
            },
            success: function (data) {
            	fnLoading(0);
              if (data.status == 200) {
                $p.html(data.updTex);
              }else{data.status};
            },
            error: function (hd,msg) {
            	fnLoading(0);
              alert(msg);
            },
          });
	}
	var $lastEnlargeImg,$prev,$next;
	$("#largeWrap").on('click', function(e) {
	    var t = e.target;
	    var $t = $(t);
	    var $this = $(this);
	    if ($t.is('.prev')) {
    		var prev = $prev.attr('src');
	    	$this.find('img').animate({opacity: '0'}, 200, function () {
	    		$(this).attr('src', prev);
	    		$(this).animate({opacity: '1'},200);
	    		$lastEnlargeImg = $prev;
	    		fnUpdateChevron();
	    	})
	    }else if ($t.is('.next')) {
    		var next = $next.attr('src');
	    	$this.find('img').animate({opacity: '0'}, 200, function () {
	    		$(this).attr('src', next);
	    		$(this).animate({opacity: '1'},200);
	    		$lastEnlargeImg = $next;
	    		fnUpdateChevron();
	    	})
	    }else{
			fnRescroll();
			$lastEnlargeImg = null;
		    $(this).fadeOut();
	    }
	    function fnUpdateChevron () {
	    	$prev = $lastEnlargeImg.parent().prev().children('img');
	    	$next = $lastEnlargeImg.parent().next().children('img');
    		$this.find('.prev').show();
    		$this.find('.next').show();
	    	if ($prev.length == 0) {
	    		$this.find('.prev').hide();
	    	}
	    	if ($next.length == 0) {
	    		$this.find('.next').hide();
	    	}
	    }
	});
// 限制图文上传数量
	function fnCountFile (form) {
		if (window.File && window.FileList) {
			var count = form["img[]"].files.length;
			if (count > 4) {
				alert("请选择4张图以内，你选择了"+count+"个");
			}
		}else {
			alert("抱歉，你的浏览器不支持FileAPI，请升级浏览器！");
		}
		return false;
	}
// 活动所需函数
	var gloDelActiStatus;
	function fnDelActi(data,page,$t){
		var path;
		switch (page) {
			case 'index':
				path = 'php/';
				break;
			case 'activity':
				path = '../php/';
				break;
		}
	    $.ajax({
	        url: path+'acti_delete.php',
	        type: 'POST',
	        dataType: 'json',
	        data: data,
	        beforeSend: function() {
	            fnLoading(1);
	        },
	        success: function(data) {
	            fnLoading(0);
	            if (data.status == 200) {
	            	if (page=='activity') {
			    		alert('活动删除成功！');
				    	location.replace(document.referrer);
			    		// window.history.back();
	            	}else {
			    		$t.closest('.list_acti').slideUp(function () {
			              $(this).remove();
			            });
	            	}
	            } else {
	                alert(data.status);
	            }
	        },
	      error: function (hd,msg) {
	            fnLoading(0);
	        alert(msg);
	      },
	    });
	}
	function fnAjaxStatus(status){
		console.log('Ajax调用函数')
		return status?gloDelActiStatus = true:gloDelActiStatus = false;
	}
	function fnDelCont (data,$t) {
	    $.ajax({
	        url: '../php/cont_delete.php',
	        type: 'POST',
	        dataType: 'json',
	        data: data,
	        beforeSend: function() {
	            fnLoading(1);
	        },
	        success: function(data) {
	            fnLoading(0);
	            if (data.status == 200) {
		            $t.closest('.cont-body').slideUp(function () {
		              $(this).remove();
		            });
	            } else {
	                alert(data.status);
	            }
	        },
	        error: function (hd,msg) {
	            fnLoading(0);
	        alert(msg);
	        },
	    });
	}
	var $lastUnscroll = null;
	function fnUnscroll($this){
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
	function fnShow($this,action){
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
	var $comN;
	function fnAddCom(data, $cont) {
	    $.ajax({
	        url: '../php/com_add.php',
	        type: 'POST',
	        dataType: 'json',
	        data: data,
	        beforeSend: function() {
	            fnLoading(1);
	        },
	        success: function(data) {
	            fnLoading(0);
	            if (data.status == 200) {
	                var $comWrap = $cont.children('.guts').children('.interaction-details');
	                var com = '<div class="reply-detail"><input type="hidden" value="' + data.comID + '"><span class="commenter">' + data.usr + '</span> <span class="conmment">' + data.say + '</span></div>';
	                alert('评论成功');
	                $comWrap.prepend(com);
	                $comN.text(data.comN);
	                $cont.find('.addCom').slideUp();
	            } else {
	                alert(data.status);
	            }
	        },
	      error: function (hd,msg) {
	            fnLoading(0);
	        alert(msg);
	      },
	    });
	}
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
	var $likN;
	function fnAddLike(data, $t) {
	    $.ajax({
	        url: '../php/cont_add_like.php',
	        type: 'POST',
	        dataType: 'json',
	        data: data,
	        beforeSend: function() {
	            fnLoading(1);
	        },
	        success: function(data) {
	            fnLoading(0);
	            if (data.status == 200) {
	                $likN.text(data.likN);
			    	$t.addClass('on');
	            } else {
	                alert(data.status);
	            }
	        },
	      error: function (hd,msg) {
	            fnLoading(0);
	        alert(msg);
	      },
	    });
	}
	function fnCancelLike(data, $t) {
	    $.ajax({
	        url: '../php/cont_cancel_like.php',
	        type: 'POST',
	        dataType: 'json',
	        data: data,
	        beforeSend: function() {
	            fnLoading(1);
	        },
	        success: function(data) {
	            fnLoading(0);
	            if (data.status == 200) {
	                $likN.text(data.likN);
			    	$t.removeClass('on');
	            } else {
	                alert(data.status);
	            }
	        },
	      error: function (hd,msg) {
	            fnLoading(0);
	        alert(msg);
	      },
	    });
	}
	var $focN;
	function fnAddFocus(data, $t){
		$.ajax({
	        url: '../php/acti_add_focus.php',
	        type: 'POST',
	        dataType: 'json',
	        data: data,
	        beforeSend: function() {
	            fnLoading(1);
	        },
	        success: function(data) {
	            fnLoading(0);
	            if (data.status == 200) {
	                $focN.text(data.focN);
			    	$t.addClass('on');
			    	$t.children('i').removeClass('icon-plus').addClass('icon-checkmark');
			    	$focN.addClass('on');
	            } else {
	                alert(data.status);
	            }
	        },
	      error: function (hd,msg) {
	            fnLoading(0);
	        alert(msg);
	      },
	    });
	}
	function fnCancelFocus(data, $t,page){
		var path;
		switch (page) {
			case 'index':
				path = 'php/';
				break;
			case 'activity':
				path = '../php/';
				break;
		}
	    $.ajax({
	        url: path+'acti_cancel_focus.php',
	        type: 'POST',
	        dataType: 'json',
	        data: data,
	        beforeSend: function() {
	            fnLoading(1);
	        },
	        success: function(data) {
	            fnLoading(0);
	            if (data.status == 200) {
	            	switch (page) {
						case 'activity':
			                $focN.text(data.focN);
				    		$t.removeClass('on');
					    	$focN.removeClass('on');
					    	$t.children('i').removeClass('icon-checkmark').addClass('icon-plus');
							break;
						case 'index':
				    		$t.closest('.list_acti').slideUp(function () {
				              $(this).remove();
				            });
							break;
	            	}
	            } else {
	                alert(data.status);
	            }
	        },
	      error: function (hd,msg) {
	            fnLoading(0);
	        alert(msg);
	      },
	    });
	}
	function fnLoading (on) {
		if(on) $("#loading").fadeIn();
		else $("#loading").fadeOut();
	}
// 创建活动
	if (($("#activity").length>0)&&($("#activity").children('input').val()!='')) {fnResizeActiImg();}
	function fnResizeActiImg() {
		console.log("活动长宽自适应");

	    // 使图片长宽自适应
	    fnResizeImg($(".cont-head img"));
	    $("#update img").each(function() {
			// console.log($(this));
	    	fnResizeImg($(this));
	    });
	}
	$("#publish_trigger").click(function() {

	    // fnPreventScroll($("body"));
	    // var $c = $("#contentForm input[type='submit']");
	    // $c.hide();
	    // $('.newlive').parent().hide();
	    // $("#img-wrap img").each(function() {
	    //   var obj = $(this);
	    //   fnResizeImg(obj);
	    //  })
	    fnBlurUnderLayer($("#main"));
	    $("#fillActivity").show().animate({left:"0vw"},800,'easeOutExpo');
	    // 显示上传的封图
	    $("#inputCover").off('change').on('change', function() {
	    	console.log('正在预览图片');
	    	fnPreviewImgFile(this.files,$("#coverWrap"),0);
	    });
	    $("#leaveEdit").click(function() {
	    	fnDeBlur();
	    	$("#fillActivity").fadeOut();
	    });
	});
	function fnResetAfterSubmit(){
		submit(reset);
	}
	function submit (callback) {
		$("#createActivity").get(0).submit();
	    callback();
	}
	function reset (argument) {
		$("#createActivity").get(0).reset();
	}
	// $("#createActivity").children('input[type="submit"]').click(function() {
	// 	console.log('221');
	// 	$("#createActivity").get(0).reset();
	// });
	function fnPreviewImgFile (files,$wrap,isbg) {
		var file = files[0];
    	var imgType = /image.*/;
    	if (file.type.match(imgType)) {
    		var reader = new FileReader();
    		reader.readAsDataURL(file);
    		reader.onload = function () {
    			var img = new Image();
    			img.src = reader.result;
    			if (isbg) {
    				console.log($wrap);
    				$wrap.css('background-image','url('+img.src+')');
    				console.log($wrap.css("background-image"));
    			}else {
	    			var w,h;
	    			img.onload = function(){
	    				if (img.width>img.height) {h = "100%",w = "auto";}
	    				else{w = "100%",h = "auto";}
		    			var imgHtml = '<img src="'+img.src+'" style="width: '+w+';height: '+h+';">';
		    			// var $wrap = $("#coverWrap");
		    			if ($wrap.children('img').length<1) {
				          	$wrap.append(imgHtml);
				        }else {
				          	$wrap.children('img').remove();
				          	$wrap.append(imgHtml);
				        }
	    			}
    			}
    		}
    	}
	}
	function fnPreviewImg (files,$wrap,left) {
		console.log(files.length);
		var count = files.length;
        var file;
        var imageType = /image.*/;
		var reader = new Array(count);
		if (count>left) {
			if (left==4) {
				alert("一次最多配图"+left+"张");
				return;
			}else {
				alert("还能添加"+left+"张图片");
				return;
			}
		}else if (count==left) {
			$('#addContImg').hide();
		}else {
			$('#addContImg').show();
		}
    	for(var i=0;i<count;i++){
    		file = files[i];
	        if (file.type.match(imageType)) {
	            reader[i] = new FileReader();
	            reader[i].readAsDataURL(file);
	            reader[i].onload = function() {
	                var img = new Image();
	                img.src = this.result;
			        var w = '100%',
			            h = 'auto';
	                img.onload = function () {
	    				if (img.width>img.height) {h = "100%",w = "auto";}
	    				else{w = "100%",h = "auto";}
		                var imgWrap = '<div class="imgWrap">';
		                imgWrap += '<img src="' + img.src + '"style="width: ' + w + ';height: ' + h + ';"></div>';
		    			var imgHtml = '<img src="'+img.src+'" style="width: '+w+';height: '+h+';">';
		                $wrap.prepend(imgWrap);
	                }
	            }
	        }
    	}
		left = left-count;
		$("#addContImg").off('click').click(function() {console.log('添加3');
			$("#addContImg"+left).click();
			$("#addContImg"+left).off('change').on('change', function() {
			console.log('添加');
				fnPreviewImg(this.files,$("#addCont_imgsWrap"),left);
			});
		});
		$("#addCont").fadeIn();
	}
// 添加图文
	$("#inputContImg").click(function() {
		fnUnscroll($("body"));
	    $(this).off('change').on('change', function() {
	        fnPreviewImg(this.files,$("#addCont_imgsWrap"),4);
	    });
	});
})
