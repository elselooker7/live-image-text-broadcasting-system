//使图片cover显示
	function fnResizeImg(obj) {
		// console.log(obj);
	    // alert("fnResizeImg");
	    var imgWrapW = 0,
	        imgWrapH = 0;
	    imgWrapW = obj.parent().width();
	    imgWrapH = obj.parent().height();
	    var wrapWHRatio = imgWrapW / imgWrapH;
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