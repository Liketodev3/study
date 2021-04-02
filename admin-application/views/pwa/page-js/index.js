function validateFrm () {
    if(!$(this).validate()){
        return false;
    }
}

$(document).ready(function () {
    $(document.pwaFrm[0]).focus();
    $(document.pwaFrm).submit(validateFrm);
});

// $('input.color').colorpicker({align: 'left'}).on('changeColor', update);

//       window.manifest = {
//         language: "en"
//       }

//       $('#form input').on('keyup', update);
//       $('#form select').on('change', update);
//       function update() {
//         $('input.color').each(function(i, el) {
//           $(el).css({
//             'border-bottom-color': el.value,
//             'box-shadow': '0 1px 0 0 ' + el.value,
//             'color': el.value
//           });
//           if ($(el).attr('name') === 'theme_color') {
//             $('nav').css('background-color', el.value);
//           }
//         });
//         window.manifest = $('#form').serializeArray().reduce(function(obj, item) {
//           if (item.value === "") { return obj; }
//           obj[item.name] = item.value;
//           return obj;
//         }, {});
//         $('#manifest').val(JSON.stringify(window.manifest, null, 2));
//         render();
//       }

//       function render() {
//         $('#output').text(JSON.stringify(manifest, null, 2)).each(function(i, block) {
//           hljs.highlightBlock(block);
//         });
//       }
//       update();

      