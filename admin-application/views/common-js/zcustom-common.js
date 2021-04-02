(function($){
	var screenHeight = $(window).height() - 100;
	window.onresize = function(event) { 
		var screenHeight = $(window).height() - 100;		
	};
	
	$.extend(fcom, {
	
		waitAndRedirect: function (msg, url, time){
			var time = time || 3000;
			var url = url || fcom.makeUrl();
			$.systemMessage(msg);
			setTimeout(function(){
				location.href = url;
			}, time);
		},
		
		scrollToTop: function(obj){
			if(typeof obj == undefined || obj == null){
				$('html, body').animate({scrollTop: $('html, body').offset().top -100 }, 'slow');
			}else{
				$('html, body').animate({scrollTop: $(obj).offset().top -100 }, 'slow');
			}
		},
		
		resetEditorInstance: function(){
			if(typeof oUtil!= 'undefined' ){
				
				var editors = oUtil.arrEditor;
	
				for (x in editors){
					eval('delete window.' + editors[x]);							
				}			
				oUtil.arrEditor = [];
			}		
		},
		
		setEditorLayout:function(lang_id){
			var editors = oUtil.arrEditor;			
			layout = langLbl['language'+lang_id];									
			for (x in editors){					
				$('#idContent'+editors[x]).contents().find("body").css('direction',layout);				
			}	
		},	
		
		resetFaceboxHeight:function(){
			$('html').css('overflow','hidden');
			facebocxHeight  = screenHeight;
			$('#facebox .content').css('max-height', facebocxHeight-50 + 'px');
			if($('#facebox .content').height()+100 >= screenHeight){			
			
				$('#facebox .content').css('overflow-y', 'scroll');
				$('#facebox .content').css('display', 'block');
			}else{
				
				$('#facebox .content').css('max-height', '');
				$('#facebox .content').css('overflow', '');			
			}
		},
		
		getLoader: function(){
			return '<div class="circularLoader"><svg class="circular" height="30" width="30"><circle class="path" cx="25" cy="25.2" r="19.9" fill="none" stroke-width="6" stroke-miterlimit="10"></circle> </svg> </div>';
		},
		
		updateFaceboxContent:function(t,cls){
			if(typeof cls == 'undefined' || cls == 'undefined'){
				cls = '';
			}
			$.facebox(t,cls);
			$.systemMessage.close();
			fcom.resetFaceboxHeight();
		},
		displayProcessing: function(msg,cls,autoclose){			
			if(typeof msg == 'undefined' || msg == 'undefined'){
				msg = langLbl.processing;
			}
			$.systemMessage(msg,'alert--process',autoclose);
		},
		displaySuccessMessage: function(msg,cls,autoclose){
			if(typeof cls == 'undefined' || cls == 'undefined'){
				cls = 'alert--success';
			}	
			$.systemMessage(msg,cls,autoclose);
		},
		displayErrorMessage: function(msg,cls,autoclose){			
			if(typeof cls == 'undefined' || cls == 'undefined'){
				cls = 'alert--danger';
			}
			$.systemMessage(msg,cls,autoclose);
		}
	});
	
	$(document).bind('reveal.facebox', function() {	
		fcom.resetFaceboxHeight();		
	});
	
	$(window).on("orientationchange",function(){
		fcom.resetFaceboxHeight();
	});
 	
	$(document).bind('loading.facebox', function() {
		
		$('#facebox .content').addClass('fbminwidth');		
	});
	
	$(document).bind('afterClose.facebox', fcom.resetEditorInstance);
	$(document).bind('afterClose.facebox', function() { $('html').css('overflow','') } );
	
	$.systemMessage = function(data, cls, autoClose){
		if(typeof autoClose == 'undefined' || autoClose == 'undefined'){
			autoClose = false;
		}else{
			autoClose = true;
		}
		initialize();
		$.systemMessage.loading();
		$.systemMessage.fillSysMessage(data, cls, autoClose);
	}
	$.extend($.systemMessage, {
		settings:{
			closeimage:siteConstants.webroot + 'images/facebox/close.gif',
		},
		loading: function(){
			$('.alert').show();
		},
		fillSysMessage:function(data, cls,autoClose){
			$('.alert').removeClass('alert--success');
			$('.alert').removeClass('alert--danger');
			$('.alert').removeClass('alert--process');
			if(cls) $('.alert').addClass(cls);
			
			$('.alert .sysmsgcontent').html(data);
			$('.alert').fadeIn();
			
			if(!autoClose && CONF_AUTO_CLOSE_SYSTEM_MESSAGES == 1 ){
				var time = CONF_TIME_AUTO_CLOSE_SYSTEM_MESSAGES * 1000;
				setTimeout(function(){
					$.systemMessage.close();
				}, time);
			}
			/* setTimeout(function() {
				$('.system_message').hide('fade', {}, 500)
			}, 5000); */
		},
		close:function(){
			$(document).trigger('close.sysmsgcontent');
		},
	});
	
	function initialize(){
		$('.alert .close').click($.systemMessage.close);
	}
	
	$(document).bind('close.sysmsgcontent', function() {
		$('.alert').fadeOut();
	});
	
	$.facebox.settings.loadingImage = SITE_ROOT_URL+'img/facebox/loading.gif';
	$.facebox.settings.closeImage   = SITE_ROOT_URL+'img/facebox/closelabel.png';
	
	if($.datepicker){
		
		var old_goToToday = $.datepicker._gotoToday
		$.datepicker._gotoToday = function(id) {
			old_goToToday.call(this,id);
			this._selectDate(id);
			$(id).blur();
			return;
		}
	}
	
	
	refreshCaptcha = function (elem){
		$(elem).attr('src', siteConstants.webroot + 'helper/captcha?sid=' + Math.random());
	}
	
	clearCache = function(){
		$.systemMessage(langLbl.processing,'alert--process');
		fcom.ajax(fcom.makeUrl('Home', 'clearCache'), '', function(t) {
			window.location.reload();
		});
	}
	
	SelectText = function(element) {
		var doc = document
			, text = doc.getElementById(element)
			, range, selection
		;    
		if (doc.body.createTextRange) {
			range = document.body.createTextRange();
			range.moveToElementText(text);
			range.select();
		} else if (window.getSelection) {
			selection = window.getSelection();        
			range = document.createRange();
			range.selectNodeContents(text);
			selection.removeAllRanges();
			selection.addRange(range);
		}
	}
	getSlugUrl = function(obj,str,extra,pos){
		if(pos==undefined)
			pos ='pre' ;
		var str = str.toString().toLowerCase()
		.replace(/\s+/g, '-')           // Replace spaces with -
		.replace(/[^\w\-\/]+/g, '')       // Remove all non-word chars
		.replace(/\-\-+/g, '-')         // Replace multiple - with single -
		.replace(/^-+/, '')             // Trim - from start of text
		.replace(/-+$/, '');   
		 if(extra && pos =='pre'){
			str = extra+'/'+str;
		} if(extra && pos=='post'){
			str = str +'/'+extra;
		} 
		
		$(obj).next().html(SITE_ROOT_URL+str);
		
	};
	redirectfunc = function(url,id,nid){
		if(nid>0)
		{
			$.systemMessage(langLbl.processing,'alert--process');
			markRead(nid,url,id);
		}else{
			var form = '<input type="hidden" name="id" value="'+id+'">';
			$('<form action="' + url + '" method="POST">' + form + '</form>').appendTo($(document.body)).submit();	
		}
	};
	markRead = function(nid,url,id){	
		if(nid.length < 1){
			return false;
		}	
		var data = 'record_ids='+nid+'&status='+1+'&markread=1';
		fcom.updateWithAjax(fcom.makeUrl('Notifications', 'changeStatus'), data, function(t) {	
			var form = '<input type="hidden" name="id" value="'+id+'">';
			$('<form action="' + url + '" method="POST">' + form + '</form>').appendTo($(document.body)).submit();	
		});	
	};

	/* $(document).click(function(event) {
		$('ul.dropdown-menu').hide();
	}); */
})(jQuery);
	
function getSlickSliderSettings( slidesToShow, slidesToScroll,layoutDirection ){
	slidesToShow = (typeof slidesToShow != "undefined" ) ? parseInt(slidesToShow) : 4;
	slidesToScroll = (typeof slidesToScroll != "undefined" ) ? parseInt(slidesToScroll) : 1;
	layoutDirection = (typeof layoutDirection != "undefined" ) ? layoutDirection : 'ltr';
	
	if(layoutDirection == 'rtl'){
		return {
			slidesToShow: slidesToShow,
			slidesToScroll: slidesToScroll,     
			infinite: false, 
			arrows: true, 
			rtl:true,
			prevArrow: '<a data-role="none" class="slick-prev" aria-label="previous"></a>',
			nextArrow: '<a data-role="none" class="slick-next" aria-label="next"></a>',    
			responsive: [{
				breakpoint:1050,
				settings: {
					slidesToShow: slidesToShow - 1,
				}
				},
				{
					breakpoint:990,
					settings: {
						slidesToShow: 3,
					}
				},
				{
					breakpoint:767,
					settings: {
						slidesToShow: 2,
					}
				} ,
				{
					breakpoint:400,
					settings: {
						slidesToShow: 1,
					}
				} 
				]
		}
	}else{
		return {
			slidesToShow: slidesToShow,
			slidesToScroll: slidesToScroll,     
			infinite: false, 
			arrows: true,					
			prevArrow: '<a data-role="none" class="slick-prev" aria-label="previous"></a>',
			nextArrow: '<a data-role="none" class="slick-next" aria-label="next"></a>',    
			responsive: [{
				breakpoint:1050,
				settings: {
					slidesToShow: slidesToShow - 1,
				}
				},
				{
					breakpoint:990,
					settings: {
						slidesToShow: 3,
					}
				},
				{
					breakpoint:767,
					settings: {
						slidesToShow: 2,
					}
				} ,
				{
					breakpoint:400,
					settings: {
						slidesToShow: 1,
					}
				} 
				]
		}
	}
}
(function() {
	
	Slugify = function(str,str_val_id,is_slugify){
		var str = str.toString().toLowerCase()
		.replace(/\s+/g, '-')           // Replace spaces with -
		.replace(/[^\w\-]+/g, '')       // Remove all non-word chars
		.replace(/\-\-+/g, '-')         // Replace multiple - with single -
		.replace(/^-+/, '')             // Trim - from start of text
		.replace(/-+$/, '');   
		if ( $("#"+is_slugify).val()==0 )
			$("#"+str_val_id).val(str);
	};
	
	callChart = function(dv,$labels,$series,$position){
		
		
		new Chartist.Bar('#'+dv, {

		  labels: $labels,

		  series: [$series],



		}, {

		  stackBars: false,

		  axisY: {
			   position: $position, 
			labelInterpolationFnc: function(value) {
                return value;
			  //return (value / 1000) + 'k';

			}

		  }

		}).on('draw', function(data) {

		  if(data.type === 'bar') {

			data.element.attr({

			  style: 'stroke-width: 25px'

			});

		  }

		});

	}

		
	
})();

