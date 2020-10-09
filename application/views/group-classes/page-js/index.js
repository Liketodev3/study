$("document").ready(function(){
	var frm = document.frmTeacherSrch;
	search( frm );
    
    $(document).on('change', '[name=language],[name=custom_filter],[name=status]', function(){
        search( frm );
    })
});

(function() {
	search = function(frm){
		var data = fcom.frmData(frm);
		//alert( data );

		var dv = $("#listingContainer");
		$(dv).html(fcom.getLoader());
		
		fcom.ajax( fcom.makeUrl('GroupClasses','search'), data,function(t){
			$(dv).html(t);
		});
	};

	goToSearchPage = function(page) {
		if(typeof page == undefined || page == null){
			page = 1;
		}
		var frm = document.frmSearchPaging;
		$(frm.page).val(page);
		search(frm);
	};

	resetSearchFilters = function(){
		searchArr = [];
		document.frmSrch.reset();
		document.frmSrch.reset();
		search(document.frmSrch);
	};
    
    showInterestList = function(grpcls_id){
        if( isUserLogged() == 0 ){
			logInFormPopUp();
			return false;
		}
        fcom.getLoader();
        fcom.ajax(fcom.makeUrl('GroupClasses', 'InterestList'), {grpcls_id:grpcls_id}, function(res){
            fcom.updateFaceboxContent(res);
            jQuery('#time').datetimepicker();
        });
    };
    
    setupInterestList = function(frm){
        if (!$(frm).validate()) return false;
		var data = fcom.frmData(frm);
		if( isUserLogged() == 0 ){
			logInFormPopUp();
			return false;
		}
        fcom.updateWithAjax( fcom.makeUrl('GroupClasses', 'setupInterestList'), data, function (data) {
            $(document).trigger('close.facebox');
		});
    };
    
    followInterest = function(id){
        if (!id) {
            alert('Invalid Request');
            return false;
        }
		
		fcom.updateWithAjax( fcom.makeUrl('GroupClasses', 'followInterest'), {id:id}, function(data){
            $(document).trigger('close.facebox');
        });
    };

})();

function changeTz(el){
    var key = $(el).closest('.card-listing').attr('id');
    setCookie(key, $(el).hasClass('inactive'));
    var date_fld = $(el).closest('.card-listing').find('.cls_date');
    var tz1_date = date_fld.text();
    var tz2_date = date_fld.attr('rev');
    date_fld.text(tz2_date);
    date_fld.attr('rev', tz1_date);
    var time_fld = $(el).closest('.card-listing').find('.cls_time');
    var tz1_time = time_fld.text();
    var tz2_time = time_fld.attr('rev');
    time_fld.text(tz2_time);
    time_fld.attr('rev', tz1_time);   
    $(el).toggleClass('active');
    $(el).toggleClass('inactive');
}