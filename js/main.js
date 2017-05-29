// 个人中心导航
	// $(".nav_me .pub").each(function() {
	// 	$(this).click(function() {
	// 		fnSwitchOn($(this));
	//     	$("#userInfo .sec_publish").fadeIn();
	// 	});
	// });
// 直播页导航
	$("#userInfo .sec .fold").hide();
	// console.log($("#userInfo .sec_publish>.wp_uinfo").eq(0));
	// console.log($(".sec>div:first"));
	// $(".sec>div:first").children('.wp_list').show();
	// $("#userInfo .sec_publish>.wp_uinfo").eq(0).children('.wp_list').show();
	// $("#userInfo .sec_publish>.wp_uinfo").eq(0).find('.icon-angle-down').removeClass('icon-angle-down').addClass('icon-angle-up');
	var $lastSlideDown = $("#toggle_acti").next('.wp_list');
	var flagLikeLoaded = 0;
	var flagCommLoaded = 0;
	var flagActiLoaded = 0;
	var flagFocuLoaded = 0;
	$("#toggle_like").click(function() {
		fnUnfoldList($(this).next('.wp_list'));
		if (!flagLikeLoaded) fnLoadLike();
	});
	$("#toggle_comm").click(function() {
		fnUnfoldList($(this).next('.wp_list'));
		if (!flagCommLoaded) fnLoadComm();
	});
	$("#toggle_acti").click(function() {
		fnUnfoldList($(this).next('.wp_list'));
		if (!flagActiLoaded) fnLoadActi();
	});
	$("#toggle_focu").click(function() {
		fnUnfoldList($(this).next('.wp_list'));
		if (!flagFocuLoaded) fnLoadFocu();
	});
	$("#fix_nav_lab").click(function() {
		var headName = '#toggle_'+$(this).attr('data-name');
		fnUnfoldList($(headName).next('.wp_list'));
		// console.log($(headName).offsset().top);
		$(window).scrollTop($(headName).offset().top+70);
		$("#fix_nav_lab").hide();
	});
	$("header>.nav").hide();
	// $("#fix_nav_mes").hide();
	// $("#fix_nav_mes").hide();
// 页面滚动监视
	$(window).on('scroll', function() {
		// console.log($(this).scrollTop());
		if ($("#pag").val()==1) {
			if ($("#nav_uinfo").offset().top-$(window).scrollTop()<51) {
				$("#fix_nav_uinfo").show();
			}else {
				$("#fix_nav_uinfo").hide();
			}
			// console.log($("#nav_uinfo"));
			// console.log($("#nav_uinfo").offset());
			if ($(this).scrollTop()>300) {
				if (flagShowHeader) {
					// var opacity = $(this).scrollTop()-300;
					$("#nav_main").slideUp();
					flagShowHeader = 0;
				}
			}else {
				if (flagShowHeader == 0) {
					$("#nav_main").slideDown();
					flagShowHeader = 1;
				}
			}
			// var dTopScreen = $("#toggle_acti").offset().top-$(window).scrollTop();
			// 直播页
			switch (flagNavUinfo) {
				case 0:
					if ($("#toggle_acti").offset().top-$(window).scrollTop()<70) {
						$("#fix_nav_lab").find('label').text('我的直播');
						$("#fix_nav_lab").attr('data-name', 'acti');
						$("#fix_nav_lab").show();
					}else {
						$("#fix_nav_lab").hide();
					}
					break;
				case 1:
					if ($("#toggle_like").offset().top-$(window).scrollTop()<19) {
						$("#fix_nav_lab").find('label').text('我的喜欢');
						$("#fix_nav_lab").attr('data-name', 'like');
						$("#fix_nav_lab").show();
					}else if ($("#toggle_focu").offset().top-$(window).scrollTop()<19) {
						$("#fix_nav_lab").find('label').text('我的关注');
						$("#fix_nav_lab").attr('data-name', 'focu');
						$("#fix_nav_lab").show();
					}else if ($("#toggle_comm").offset().top-$(window).scrollTop()<19) {
						$("#fix_nav_lab").find('label').text('我的评论');
						$("#fix_nav_lab").attr('data-name', 'comm');
						$("#fix_nav_lab").show();
					}else {
						$("#fix_nav_lab").hide();
					}
					break;
				case 2:
					if ($("#nav_message").offset().top-$(window).scrollTop()<44) {
						$("#fix_nav_mes").show();
					}else {
						$("#fix_nav_mes").hide();
					}
					break;
				default:
					// statements_def
					break;
			}
		}else {
			$("#fix_nav_uinfo").hide();
		}
	});
// 消息栏
	$("#com").fadeIn();
	$("#messagePage .head").on('click', function(e) {
	    var t = e.target;
	    var $t = $(t);
	    var $this = $(this);
	    if ($t.is('.back')) {
	    	window.history.go(-1);
	    }else if ($t.is('.refresh')) {
	    	window.location.reload();
	    }
	});
	var flagMesComLoaded = 0;
	var flagMesArgLoaded = 0;
	var flagMesSysLoaded = 0;
	$("#navCom").on('click', function(e) {
    	fnSwitchOn($(this));
    	if (!flagMesComLoaded) {fnMesCom();}
    	fnHideSec(1);
    	$("#com").fadeIn();
    	console.log($("#com .replyMes"));
	});
// 评论
	// function fnAddComment(data,$p,$wp='') {
	//     $.ajax({
	//         url: 'php/comment_add.php',
	//         type: 'POST',
	//         dataType: 'json',
	//         data: data,
	//         beforeSend: function() {
	//             fnLoading(1);
	//         },
	//         success: function(data) {
	//             fnLoading(0);
	//             if (data.status == 200) {
	//             	if ($wp!='') {
	//             		var wp = '<table class="wrap2">\
	// 	                <input type="hidden" value="'+data.comid+'">\
	// 	                  <tr class="r1">\
	// 	                      <td><div class="imgWrap"><img src="'+data.uimg+'" alt=""></div></td>\
	// 	                      <td class="commenter"><div>'+data.usr+'</div><div class="time">'+data.tim+'</div></td>\
	// 	                      <td class="agreeN">0</td>\
	// 	                      <td><i class="icon-thumb_up agree"></i></td>\
	// 	                  </tr>\
	// 	                  <tr class="r2">\
	// 	                    <td></td>\
	// 	                    <td class="say" colspan="2">'+data.say+'</td>\
	// 	                  </tr>\
	// 	                </table>';
	// 	                $wp.append(wp);
	// 					console.log($wp);
	// 					fnResizeImg($wp.children('.wp_message').last().find('img'));
	//             	}
	//             	// alert("举报成功！");
	//             	$p.parent().children('textarea').val('');
	//             	$p.parent('.report_wrap').fadeOut();
	//             } else {
	//                 $p.html(data.status);
	//             }
	//         },
	//       error: function (hd,msg) {
	//             fnLoading(0);
	//         alert(msg);
	//       },
	//     });
	// }
	$("#navAgr").on('click', function(e) {
    	fnSwitchOn($(this));
    	if (!flagMesArgLoaded) {fnMesArg();}
    	fnHideSec(1);
    	$("#agr").fadeIn();
	});
	$("#navSys").on('click', function(e) {
    	fnSwitchOn($(this));
    	if (!flagMesSysLoaded) {fnMesSys();}
    	fnHideSec(1);
    	$("#sys").fadeIn();
	});
	var flagShowHeader = 1;
	var flagNavUinfo = 0;  //记录当前显示哪个sec
	$(".nav_me .pub").click(function() {
		fnSwitchOn($(".nav_me .pub"));
    	fnHideSec(0);
    	flagNavUinfo = 0;
    	$("#userInfo .sec_publish").fadeIn();
	});
	$(".nav_me .act").click(function() {
		fnSwitchOn($(".nav_me .act"));
    	fnHideSec(0);
    	$("#toggle_comm").click();
    	flagNavUinfo = 1;
    	$("#userInfo .sec_action").fadeIn();
	});
	$(".nav_me .mes").click(function() {
		fnSwitchOn($(".nav_me .mes"));
    	fnHideSec(0);
    	$("#navCom").click();
    	flagNavUinfo = 2;
    	$("#userInfo .sec_message").fadeIn();
	});
	$(".nav_me .about_me").click(function() {
		fnSwitchOn($(".nav_me .about_me"));
    	fnHideSec(0);
    	flagNavUinfo = 3;
    	$("#userInfo .sec_uinfo").fadeIn();
	});
// 固定导航
	$("#fix_nav_mes .nav_com").click(function() {
		fnSwitchOn($(this));
		$("#navCom").click();
	});
	$("#fix_nav_mes .nav_agr").click(function() {
		fnSwitchOn($(this));
		$("#navAgr").click();
	});
	$("#fix_nav_mes .nav_sys").click(function() {
		fnSwitchOn($(this));
		$("#navSys").click();
	});
	function fnSwitchOn(obj){
		obj.closest('tr').find('a').each(function() {
    		$(this).removeClass('on');
    	});
    	obj.addClass('on');
	}
	function fnHideSec (sec) {
		switch (sec) {
			case 1:
		    	$("#userInfo>.sec_message>.sec").each(function() {
		    		// console.log($(this));
		    		$(this).hide();
		    	});
				break;
			case 0:
		    	$("#userInfo>.sec").each(function() {
		    		$(this).hide();
		    	});
				$("#fix_nav_lab").hide();
				$("#fix_nav_mes").hide();
				break;
			default:
				$("#fix_nav_lab").hide();
				$("#fix_nav_mes").hide();
				break;
		}
	}
// 左侧菜单滚动
// $("#sideMenu").on('scroll', function(e) {
// 	e.preventDefault();
// 	e.stopPropagation();
// });

function fnUnfoldList(obj){
	// console.log($lastSlideDown!=null&&$lastSlideDown['prevObject'][0]==obj['prevObject'][0]);
	if (obj.is('.unfold')) {
		obj.slideUp();
		obj.prev('.wp_head').find('.icon-angle-up').removeClass('icon-angle-up').addClass('icon-angle-down');
		obj.removeClass('unfold').addClass('fold');
	} else {
		// console.log('展开');
		fnFoldList();
		obj.slideDown();
		obj.prev('.wp_head').find('.icon-angle-down').removeClass('icon-angle-down').addClass('icon-angle-up');
		obj.removeClass('fold').addClass('unfold');
		$lastSlideDown = obj;
		// console.log($lastSlideDown);
		// console.log($lastSlideDown['prevObject'][0]);
	}
}
function fnFoldList(){
	// if ($lastSlideDown!=null) {
	// 	$lastSlideDown.slideUp();
	// 	$lastSlideDown.removeClass('unfold').addClass('fold');
	// 	$lastSlideDown.prev('.wp_head').find('.icon-angle-up').removeClass('icon-angle-up').addClass('icon-angle-down');
	// 	$lastSlideDown = null;
	// }
	// $("#fix_nav_lab").hide();
}
// 局部刷新函数
if (fnIsLogged()) {fnLoadActi ();}
	function fnLoadActi () {
	    $.ajax({
	      url: 'php/my_acti.php',
	      type: 'POST',
	      dataType: 'json',
	      beforeSend: function() {
	            fnLoading(1);
	      },
	      success: function (data) {
	            fnLoading(0);
	            // console.log(data);
	            // console.log(data.length);
	            data.forEach( function(element, i) {
	            	// console.log(data[i]);
	            	var list_acti = 
		            	'<div class="list_acti" data-aid='+data[i]["aid"]+'>\
							<div><div class="imgWrap"><img src="'+data[i]["csrc"]+'" alt=""></div></div>\
							<a href="'+data[i]["page"]+'" class="tit_abs"><div>'+data[i]["title"]+'</div><div>'+data[i]["abstract"]+'</div></a>\
							<div class="tim"><div>'+data[i]["ctime"]+'</div><i class="icon-bin del_acti"></i></div>\
						</div>';
					$("#toggle_acti").next(".wp_list").prepend(list_acti);
					fnResizeImg($("#toggle_acti").next(".wp_list").children('.list_acti').first().find('img'));
	            });
			    flagActiLoaded = 1;
	      },
	      error: function (hd,msg) {
	            fnLoading(0);
		        alert(msg);
	      },
	    });
	}
	function fnLoadComm () {
	    $.ajax({
	      url: 'php/my_comm.php',
	      type: 'POST',
	      dataType: 'json',
	      beforeSend: function() {
	            fnLoading(1);
	      },
	      success: function (data) {
	            fnLoading(0);
	            data.forEach( function(element, i) {
		      		if (data[i]["tar"]=='com') {
	            	var list_comm = 
					    '<a class="wp_message" href="activity/comment.php?type=comment&id='+data[i]["comid"]+'">\
		      		        <div class="comment"><div>'+data[i]["say"]+'</div><span class="time">'+data[i]["tim"]+'</span></div>';
		      			list_comm += '<div class="who"><div class="imgWrap uimg"><img src="'+data[i]["img"]+'" alt=""></div><div>';
		      		}else if (data[i]["tar"]=='cont'){
	            	var list_comm = 
					    '<a class="wp_message" href="activity/comment.php?type=content&id='+data[i]["cid"]+'#com'+data[i]["comid"]+'">\
		      		        <div class="comment"><div>'+data[i]["say"]+'</div><span class="time">'+data[i]["tim"]+'</span></div>';
		      			list_comm += '<div class="who acti"><div class="imgWrap uimg"><img src="'+data[i]["img"]+'" alt=""></div><div>';
		      		}else{
	            	var list_comm = 
					    '<a class="wp_message" href="'+data[i]["pag"]+'">\
		      		        <div class="comment"><div>'+data[i]["say"]+'</div><span class="time">'+data[i]["tim"]+'</span></div>';
		      			list_comm += '<div class="who acti"><div class="imgWrap uimg"><img src="'+data[i]["img"]+'" alt=""></div><div>';
		      		}
		      		list_comm += '<div class="name">'+data[i]["tit"]+'</div><div class="say">'+data[i]["des"]+'</div></div>\
		      		        </div>\
		      		    </a>';
					$("#toggle_comm").next(".wp_list").prepend(list_comm);
					fnResizeImg($("#toggle_comm").next(".wp_list").children('.wp_message').first().find('img'));
	            });
			    flagCommLoaded = 1;
	      },
	      error: function (hd,msg) {
	            fnLoading(0);
		        alert(msg);
	      },
	    });
	}
	function fnLoadLike () {
	    $.ajax({
	      url: 'php/my_like.php',
	      type: 'POST',
	      dataType: 'json',
	      beforeSend: function() {
	            fnLoading(1);
	      },
	      success: function (data) {
	            fnLoading(0);
	            console.log(data);
	            console.log(data.length);
	            data.forEach( function(element, i) {
	            	console.log(data[i]);
	            	var list_like = 
					    '<a href="'+data[i]["pag"]+'#cont'+data[i]["cid"]+'" class="wp_message like">\
		      		        <div class="who acti">\
			      		        <div class="imgWrap"><img src="'+data[i]["img"]+'" alt=""></div>\
			      		        <div><div class="name">'+data[i]["des"]+'</div><div class="say">'+data[i]["tit"]+'</div></div>\
			      		        <span class="time">'+data[i]["tim"]+'</span>\
		      		        </div>\
		      		    </a>';
					$("#toggle_like").next(".wp_list").prepend(list_like);
					fnResizeImg($("#toggle_like").next(".wp_list").children('.wp_message').first().find('img'));
	            });
			    flagLikeLoaded = 1;
	      },
	      error: function (hd,msg) {
	            fnLoading(0);
		        alert(msg);
	      },
	    });
	}
	function fnLoadFocu () {
	    $.ajax({
	      url: 'php/my_focu.php',
	      type: 'POST',
	      dataType: 'json',
	      beforeSend: function() {
	            fnLoading(1);
	      },
	      success: function (data) {
	            fnLoading(0);
	            data.forEach( function(element, i) {
	            	var list_focu = 
		            	'<div class="list_acti" data-aid='+data[i]["aid"]+'>\
							<div><div class="imgWrap"><img src="'+data[i]["img"]+'" alt=""></div></div>\
							<a href="'+data[i]["pag"]+'" class="tit_abs"><div>'+data[i]["tit"]+'</div><div>'+data[i]["des"]+'</div></a>\
							<div class="tim"><div>'+data[i]["tim"]+'</div><i class="icon-bin cancel_focus"></i></div>\
						</div>';
					$("#toggle_focu").next(".wp_list").prepend(list_focu);
					fnResizeImg($("#toggle_focu").next(".wp_list").children('.list_acti').first().find('img'));
	            });
			    flagFocuLoaded = 1;
	      },
	      error: function (hd,msg) {
	            fnLoading(0);
		        alert(msg);
	      },
	    });
	}
	function fnMesCom () {
	    $.ajax({
	      url: 'php/mes_com.php',
	      type: 'POST',
	      dataType: 'json',
	      beforeSend: function() {
	            fnLoading(1);
	      },
	      success: function (data) {
	            fnLoading(0);
	            data.forEach( function(element, i) {
	            	var mes = 
					    '<div class="wp_message" data-comid="'+data[i]['comid']+'">\
				            <div class="who">\
				                <div class="imgWrap"><img src="'+data[i]['img']+'" alt=""></div>\
					                <div><div class="name">'+data[i]['nam']+'</div><span class="time">'+data[i]['tim']+'</span>评论了你</div>\
					                <a class="btn replyMes">回复</a>\
						            </div>\
					            <div class="comment">'+data[i]['com']+'</div>\
				            <div class="say">'+data[i]['say']+'</div>\
			            </div>';
					$("#com").prepend(mes);
					fnResizeImg($("#com").children('.wp_message').first().find('img'));
	            });
			    flagMesComLoaded = 1;
				$("#com").find('.replyMes').on('click', function() {
					var $wp = $('#wpComment');
					var $say = $wp.children('textarea');
			    	var $p = $wp.children('.prompt');
			    	$wp.children('.close').click(function() {
				    	$wp.fadeOut();
				    	$say.val('');
			    	});
			    	console.log($t.closest('.wp_message').find('.name').text());
			    	var commenter = $t.closest('.wp_message').find('.name').text();
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
				    		var comid = $t.closest('.wp_message').attr('data-comid');;
				    		var data = {'say':$say.val(),'to_typ':'comment','to_id':comid};
				    		// fnAddComment(data,$p,$t.closest('.wrap2'));
				    	}
			    	});
			    	$wp.fadeIn();
			    	$say.focus();
				});
	      },
	      error: function (hd,msg) {
	            fnLoading(0);
		        alert(msg);
	      },
	    });
	}
	function fnMesArg () {
	    $.ajax({
	      url: 'php/mes_agr.php',
	      type: 'POST',
	      dataType: 'json',
	      beforeSend: function() {
	            fnLoading(1);
	      },
	      success: function (data) {
	            fnLoading(0);
	            data.forEach( function(element, i) {
	            	// console.log(data[i]['said']);
	            	var mes = 
					    '<div class="wp_message">\
				            <div class="who">\
				                <div class="imgWrap"><img src="'+data[i]['img']+'" alt=""></div>\
				                <div><div class="name">'+data[i]['nam']+'</div><span class="time">'+data[i]['tim']+'</span>赞了你</div>\
				            </div>\
				            <div class="say">'+data[i]['said']+'</div>\
			            </div>';
					$("#agr").prepend(mes);
					fnResizeImg($("#agr").children('.wp_message').first().find('img'));
	            });
			    flagMesArgLoaded = 1;
	      },
	      error: function (hd,msg) {
	            fnLoading(0);
		        alert(msg);
	      },
	    });
	}
	function fnMesSys () {
	    $.ajax({
	      url: 'php/mes_sys.php',
	      type: 'POST',
	      dataType: 'json',
	      beforeSend: function() {
	            fnLoading(1);
	      },
	      success: function (data) {
	            fnLoading(0);
	            data.forEach( function(element, i) {
	            	var mes = 
					    '<div class="wp_message">\
				            <div class="who">\
				                <div class="imgWrap"><img src="'+data[i]['img']+'" alt=""></div>\
				                <div><div class="name">'+data[i]['nam']+'</div><span class="time">'+data[i]['tim']+'</span>'+data[i]['beh']+'</div>\
				            </div>\
				            <div class="say">'+data[i]['rea']+'</div>\
			            </div>';
					$("#sys").prepend(mes);
					fnResizeImg($("#sys").children('.wp_message').first().find('img'));
	            });
			    flagMesSysLoaded = 1;
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
	function fnIsLogged () {
		// console.log($("#userInfo")[0]);
		// console.log($("#usr").val());
		return ($("#usr").val()=='')?false:true;
	}
	function fnPopLog(){
		fnUnscroll($("body"));
		fnShow($("#sep_reg_log"),'fadeIn');
	}