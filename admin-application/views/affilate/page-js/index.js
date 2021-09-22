$(document).ready(function(){
    search();
});

(function() {

    var dv = '#listing';

    // reloadList = function() {
    //     var frm = document.frmCurrencySearch;
    //     search(frm);
    // };

    search = function(form){

        var data = '';
        if (form) {
            data = fcom.frmData(form);
        }
        $(dv).html(fcom.getLoader());


        fcom.ajax(fcom.makeUrl('Affilate','search'),data,function(res){
            $(dv).html(res);
        });
    };

    updateAffilate = function(data){
        let name = $(data).attr('name');
        let val = $(data).val();
        fcom.ajax(fcom.makeUrl('Affilate', 'updateAffilate', [name,val]), '', function(t) {

        });
    };


})();