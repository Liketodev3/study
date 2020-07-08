$(function() {
	var dv = '#listItems';
	searchGroupClasses = function(frm){
		var data = fcom.frmData(frm);
        $(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('TeacherGroupClasses','search'),data,function(t){
			$(dv).html(t);
		});
	};
	
	form = function(id){	
		$(dv).html(fcom.getLoader());
		fcom.ajax(fcom.makeUrl('TeacherGroupClasses','form',[id]),'',function(t){
			$(dv).html(t);
			jQuery('#grpcls_start_datetime,#grpcls_end_datetime').each(function(){
                $(this).datetimepicker({
                    format: 'Y-m-d H:i'
                });
            });
		}); 
	};

	removeClass = function(elem,id){
		if(confirm(langLbl.confirmRemove))
		{
			$(elem).closest('tr').remove();
			$(dv).html(fcom.getLoader());
			fcom.ajax(fcom.makeUrl('TeacherGroupClasses','removeClass',[id]),'',function(t){
				searchGroupClasses(document.frmSrch);
			});
		}
	};

	cancelClass = function(id){
		if(confirm(langLbl.confirmCancel))
		{
			$(dv).html(fcom.getLoader());
			fcom.ajax(fcom.makeUrl('TeacherGroupClasses','cancelClass',[id]),'',function(t){
				searchGroupClasses(document.frmSrch);
			});
		}
	};
	
	setup = function(frm){
		if (!$(frm).validate()) return false;
		var formData = new FormData(frm); 
		$.ajax({
			url: fcom.makeUrl('TeacherGroupClasses', 'setup'),
			type: 'POST',
			data: formData,
			mimeType: "multipart/form-data",
			contentType: false,
			processData: false,
			success: function (data, textStatus, jqXHR) {
				var data=JSON.parse(data);
				if(data.status==0)
				{
					//$.systemMessage(data.msg);
					$.mbsmessage(data.msg,true, 'alert alert--danger');
					return false;
				} else{
                    // $.systemMessage(data.msg,false);
                    $.mbsmessage(data.msg,true, 'alert alert--success');
                }
                searchGroupClasses(document.frmSrch);
                setTimeout(function(){
                    $.systemMessage.close();
                },2000);
			},
			error: function (jqXHR, textStatus, errorThrown) {
				$.systemMessage(jqXHR.msg, true);
			}
		});
	};

	clearSearch = function(){
		document.frmSrch.reset();
		searchGroupClasses( document.frmSrch );
	};

	goToSearchPage = function(page) {
		if(typeof page == undefined || page == null){
			page = 1;
		}
		var frm = document.frmSearchPaging;
		$(frm.page).val(page);
		searchGroupClasses(frm);
	};
    
    showInterestList = function(grpcls_id){
        if( isUserLogged() == 0 ){
			logInFormPopUp();
			return false;
		}
        fcom.getLoader();
        fcom.ajax(fcom.makeUrl('TeacherGroupClasses', 'InterestList'), {grpcls_id:grpcls_id}, function(res){
            fcom.updateFaceboxContent(res);
            jQuery('#time').datetimepicker();
        });
    };

	searchGroupClasses(document.frmSrch);
});

function changeInterstListStatus(el, id){
    fcom.updateWithAjax( fcom.makeUrl('TeacherGroupClasses', 'changeInterstListStatus'), {id: id, status: Number($(el).hasClass('inactive'))}, function(res){
        $(el).toggleClass('active');
        $(el).toggleClass('inactive');
    });        
}