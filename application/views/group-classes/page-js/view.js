$("document").ready(function(){
    var key = $('.card-listing').attr('id');
    if(getCookie(key)=="true"){
        changeTz($('.card-listing').find('.statustab'));
    }
});

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