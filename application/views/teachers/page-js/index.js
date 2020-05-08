var searchArr = [];
$("document").ready(function(){
	$('.btn--filters-js').click(function() {
		$(this).toggleClass("is-active");
		$('html').toggleClass("show-filters-js");
	});

	var frm = document.frmTeacherSrch;

	searchTeachers( frm );

	$("input[name='filterSpokenLanguage[]']").change(function(){
		var id = $(this).closest("label").attr('id');
		if($(this).is(":checked")){
			addFilter ( id, this );
		} else {
			removeFilter( id, this );
		}
		searchTeachers( frm );
	});

	$("input[name='filterPreferences[]']").change(function(){
		var id = $(this).closest("label").attr('id');
		if($(this).is(":checked")){
			addFilter ( id, this );
		} else {
			removeFilter( id, this );
		}
		searchTeachers( frm );
	});

	$("input[name='filterWeekDays[]']").change(function(){
		var id = $(this).closest("label").attr('id');
		if($(this).is(":checked")){
			addFilter ( id, this );
		} else {
			removeFilter( id, this );
		}
		searchTeachers( frm );
	});
	var priceFilterMinValue = 	$("input[name='priceFilterMinValue']").val();
	var priceFilterMaxValue = 	$("input[name='priceFilterMaxValue']").val();
	$("input[name='priceFilterMinValue'], input[name='priceFilterMaxValue']").focus(function(){
		 priceFilterMinValue = 	$("input[name='priceFilterMinValue']").val();
		 priceFilterMaxValue = 	$("input[name='priceFilterMaxValue']").val();
			$(this).val('');
	}).blur(function(){
		if(	$(this).val() == "") {
			$("input[name='priceFilterMinValue']").val(priceFilterMinValue);
			$("input[name='priceFilterMaxValue']").val(priceFilterMaxValue);
		}
			// $(this).parent('li').find('.rsText').show(500);
	})

	$("input[name='filterTimeSlots[]']").change(function(){
		var id = $(this).closest("label").attr('id');
		if($(this).is(":checked")){
			addFilter ( id, this );
		} else {
			removeFilter( id, this );
		}
		searchTeachers( frm );
	});

	$("input[name='filterFromCountry[]']").change(function(){
		var id = $(this).closest("label").attr('id');
		if($(this).is(":checked")){
			addFilter ( id, this );
		} else {
			removeFilter( id, this );
		}
		searchTeachers( frm );
	});

	$("input[name='filterGender[]']").change(function(){
		var id = $(this).closest("label").attr('id');
		if($(this).is(":checked")){
			addFilter ( id, this );
		} else {
			removeFilter( id, this );
		}
		searchTeachers( frm );
	});

	$("select[name='filterSortBy']").change(function(){
		searchTeachers( frm );
	});

	$("input[name='priceFilterMinValue']").keyup(function(e){
		var code = e.which;
		if( code == 13 ) {
			e.preventDefault();
			addPricefilter();
			//searchTeachers( frm );
		}
	});

	$("input[name='priceFilterMaxValue']").keyup(function(e){
		var code = e.which;
		if( code == 13 ) {
			e.preventDefault();
			addPricefilter();
			//searchTeachers( frm );
		}
	});

	$("input[name='teach_language_name']").autocomplete({
        'minLength': 0,
		'source': function(request, response) {
			$.ajax({
				url: fcom.makeUrl('Teachers', 'teachLanguagesAutoCompleteJson'),
				data: {keyword: request.term, fIsAjax:1},
				dataType: 'json',
				type: 'post',
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item['name'],
							value: item['id'],
							name: item['name']
						};
					}));
				},
			});
		},
		'select': function( event, ui) {
			event.preventDefault();
			$('input[name=\'teach_language_name\']').val( ui.item.label );
			$('input[name=\'teach_lang_keyword\']').val( ui.item.label );
			$('#frm_fat_id_frmTeacherSrch').submit();
			$('.language_keyword').parent("li").remove();
			$('#searched-filters').append("<li><a href='javascript:void(0);' class= 'language_keyword tag__clickable' onclick='removeFilterCustom(\"language_keyword\",this)' >Language: " + ui.item.label +"</a></li>");

			//addFilter ( 'language_keyword', this );
			//window.location.href = "/teachers/index/" + ui.item.value;
		}
	}).bind('focus', function () {
        $(this).autocomplete("search");
    });

	$('input[name=\'teach_language_name\']').click(function(){
		$('input[name=\'teach_language_id\']').val('');
	});

	$('.form__input-js').click(function() {
		$(this).toggleClass("is-active");
		$('.section--listing-js').toggleClass("section-invisible");
		$('.form__element-js').toggleClass("form-target-visible");
	});

    $('html').click(function(){
        if($('.section--listing-js').hasClass('section-invisible')){
          $('.section--listing-js').removeClass('section-invisible');
        }
        if($('.form__element-js').hasClass('form-target-visible')){
          $('.form__element-js').removeClass('form-target-visible');
        }
    });
    $('.form-filters').click(function(e){
        e.stopPropagation();
    });

});

function viewCalendar( teacherId,action ){
	fcom.ajax(fcom.makeUrl('Teachers', 'viewCalendar',[teacherId]), 'action='+action, function(t) {
		$.facebox( t,'facebox-medium');
	});
}

function htmlEncode(value){
  return $('<div/>').text(value).html();
}

(function() {
	updateRange = function (from,to) {
		range.update({
			from: from,
			to: to
		});
	};

	searchTeachers = function(frm){
		var data = fcom.frmData(frm);
		//alert( data );

		var dv = $("#teachersListingContainer");
		$(dv).html(fcom.getLoader());

		/* spoken language filters[ */
		var spokenLanguages = [];
		$.each($("input[name='filterSpokenLanguage[]']:checked"), function(){
			var id = $(this).closest("label").attr('id');
			addFilter ( id, this );
			spokenLanguages.push( $(this).val() );
		});
		if ( spokenLanguages.length ){
			data=data+"&spokenLanguage="+[spokenLanguages];
		}
		/* ] */

		/* preference filters[ */
		var preferenceFilters = [];
		$.each($("input[name='filterPreferences[]']:checked"), function(){
			var id = $(this).closest("label").attr('id');
			addFilter ( id, this );
			preferenceFilters.push( $(this).val() );
		});
		if ( preferenceFilters.length ){
			data=data+"&preferenceFilter="+[preferenceFilters];
		}
		/* ] */

		/* from country filter[ */
		var fromCountry = [];
		$.each($("input[name='filterFromCountry[]']:checked"), function(){
			var id = $(this).closest("label").attr('id');
			addFilter ( id, this );
			fromCountry.push( $(this).val() );
		});
		if ( fromCountry.length ){
			data=data+"&fromCountry="+[fromCountry];
		}
		/* ] */

		/* gender filter[ */
		var gender = [];
		$.each($("input[name='filterGender[]']:checked"), function(){
			var id = $(this).closest("label").attr('id');
			addFilter ( id, this );
			gender.push( $(this).val() );
		});
		if ( gender.length ){
			data=data+"&gender="+[gender];
		}
		/* ] */

		/* price filter value pickup[ */
		if(typeof $("input[name=priceFilterMinValue]").val() != "undefined"){
			data = data+"&minPriceRange="+$("input[name=priceFilterMinValue]").val();
		}
		if(typeof $("input[name=priceFilterMaxValue]").val() != "undefined"){
			data = data+"&maxPriceRange="+$("input[name=priceFilterMaxValue]").val();
		}

		/* sort by[ */
		var sortBy = $("select[name='filterSortBy'] option:selected").val();
		if( sortBy != '' ){
			data = data + "&sortBy="+sortBy;
		}
		/* ] */


		fcom.updateWithAjax( fcom.makeUrl('Teachers','teachersList'), data,function(ans){
			$.mbsmessage.close();
			if( $('#total_records').length > 0  ){
				$('#total_records').html(ans.totalRecords);
			}
			if( $('#start_record').length > 0  ){
				$('#start_record').html(ans.startRecord);
			}
			if( $('#end_record').length > 0  ){
				$('#end_record').html(ans.endRecord);
			}
			$(dv).html( ans.html );
		});
	};

	goToSearchPage = function(page) {
		if(typeof page == undefined || page == null){
			page = 1;
		}
		var frm = document.frmTeacherSearchPaging;
		$(frm.page).val(page);
		searchTeachers(frm);
	};

	resetSearchFilters = function(){
		searchArr = [];
		document.frmTeacherSrch.reset();
		document.frmTeacherSrch.reset();

		/* $('#filters a').each(function(){
			id = $(this).attr('class');
			clearFilters(id,this);
		});
		updatePriceFilter(); */
		searchTeachers(document.frmTeacherSrch);
	};

	addFilter = function( id, obj ){
		var click = "onclick=removeFilter('"+id+"',this)";
		var filter = htmlEncode($(obj).closest("div.block__body-target-js").siblings("div.block__head-trigger-js").text());
		$filterVal = htmlEncode($(obj).parent().text());

		if(!$('#searched-filters').find('a').hasClass(id)){
			id += ' tag__clickable';
			$('#searched-filters').append("<li><a href='javascript:void(0);' class=\' " + id + " \'" + click + ">"+ filter + ": " + $filterVal+"</a></li>");
		}
	};

	removeFilter = function( id, obj ){
		$('.'+id).parent("li").remove();
		$('#'+id).find('input[type=\'checkbox\']').attr('checked', false);
		searchTeachers(document.frmTeacherSrch);
	}

	removeFilterCustom = function( id, obj ){
		$('.'+id).parent("li").remove();
		$('input[name=\'teach_lang_keyword\']').val('');
		$('input[name=\'teach_language_name\']').val('');
		searchTeachers(document.frmTeacherSrch);
	}

	removeFilterUser = function( id, obj ){
		$('.'+id).parent("li").remove();
		$('input[name=\'keyword\']').val('');
		searchTeachers(document.frmTeacherSrch);
	}

	addPricefilter = function(){
		$('.price').parent("li").remove();
		if( !$('#searched-filters').find('a').hasClass('price') ){
			var filterCaption = htmlEncode($("#price_range").closest("div.block__body-target-js").siblings("div.block__head-trigger-js").text());

			$('#searched-filters').append('<li><a href="javascript:void(0)" class="price tag__clickable" onclick="removePriceFilter(this)" >'+ filterCaption + ': ' +currencySymbolLeft+$("input[name=priceFilterMinValue]").val()+currencySymbolRight+' - '+currencySymbolLeft+$("input[name=priceFilterMaxValue]").val()+currencySymbolRight+'</a></li>');
		}
		/* searchArr['price_min_range'] = $("input[name=priceFilterMinValue]").val();
		searchArr['price_max_range'] = $("input[name=priceFilterMaxValue]").val();
		searchArr['currency'] = langLbl.siteCurrencyId; */
		var frm = document.frmTeacherSrch;
		searchTeachers(frm);
	}

	removePriceFilter = function(){
		updatePriceFilter();
		/* delete searchArr['price_min_range'];
		delete searchArr['price_max_range'];
		delete searchArr['currency'];
 */		searchTeachers(document.frmTeacherSrch);
		$('.price').parent("li").remove();

	}
	removePriceFilterCustom = function(e, minPrice,maxPrice){
		//updatePriceFilter();
		/* delete searchArr['price_min_range'];
		delete searchArr['price_max_range'];
		delete searchArr['currency'];
 */		$('input[name="priceFilterMinValue"]').val(minPrice);
		$('input[name="priceFilterMaxValue"]').val(maxPrice);
		var $range = $("#price_range");
		range = $range.data("ionRangeSlider");
		updateRange(minPrice,maxPrice);
		range.reset();
		$('.price').parent("li").remove();
		searchTeachers(document.frmTeacherSrch);
	}


	updatePriceFilter = function(minPrice,maxPrice){
		if( typeof minPrice == 'undefined' || typeof maxPrice == 'undefined' ){
			minPrice = $("#filterDefaultMinValue").val();
			maxPrice = $("#filterDefaultMaxValue").val();
		} else {
			addPricefilter();
		}

		$('input[name="priceFilterMinValue"]').val(minPrice);
		$('input[name="priceFilterMaxValue"]').val(maxPrice);
		var $range = $("#price_range");
		range = $range.data("ionRangeSlider");
		updateRange(minPrice,maxPrice);
		range.reset();
	}

})();
