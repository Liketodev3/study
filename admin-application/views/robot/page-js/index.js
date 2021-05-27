
(function () {
    setup = function (frm) {
        if (!$(frm).validate()) return;
        var data = fcom.frmData(frm);
        fcom.updateWithAjax(fcom.makeUrl('Robot', 'setup'), data, function (t) {
            $.facebox.close();
        });
    };
})();
