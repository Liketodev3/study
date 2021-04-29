        <div class="page__footer align-center">
            <p class="small">Copyright © 2021 Yo!Coach Developed by <a href="#" class="underline color-primary">FATbit Technologies</a> . </p>
        </div>
    </div>
</main>
    <!-- ] -->
</div>
<!-- Custom Loader -->
<div class="loading-wrapper" style="display: none;">
    <div class="loading">
        <div class="inner rotate-one"></div>
        <div class="inner rotate-two"></div>
        <div class="inner rotate-three"></div>
    </div>
</div>
<script>
    $(".expand-js").click(function() {
        $(".expand-target-js").slideToggle();
    });

    $(".slide-toggle-js").click(function() {
        $(".slide-target-js").slideToggle();
    });


    /******** TABS SCROLL FUNCTION  ****************/
    moveToTargetDiv('.tabs-scrollable-js li.is-active', '.tabs-scrollable-js ul');
    $('.tabs-scrollable-js li').click(function() {
        $('.tabs-scrollable-js li').removeClass('is-active');
        $(this).addClass('is-active');
        moveToTargetDiv('.tabs-scrollable-js li.is-active', '.tabs-scrollable-js ul');
    });

    function moveToTargetDiv(target, outer) {
        var out = $(outer);
        var tar = $(target);
        var x = out.width();
        var y = tar.outerWidth(true);
        var z = tar.index();
        var q = 0;
        var m = out.find('li');

        for (var i = 0; i < z; i++) {
            q += $(m[i]).outerWidth(true);
        }

        out.animate({
            scrollLeft: Math.max(0, q)

        }, 800);
        return false;
    }

    $('.list-inline li').click(function() {
        $('.list-inline li').removeClass('is-active');
        $(this).addClass('is-active');
    });


    $(document).ready(function() {

        /* SIDE BAR SCROLL DYNAMIC HEIGHT */
        $('.sidebar__body').css('height', 'calc(100% - ' + $('.sidebar__head').innerHeight() + 'px');

        $(window).resize(function() {
            $('.sidebar__body').css('height', 'calc(100% - ' + $('.sidebar__head').innerHeight() + 'px');
        });



        /* COMMON TOGGLES */
        var _body = $('html');
        var _toggle = $('.trigger-js');
        _toggle.each(function() {
            var _this = $(this),
                _target = $(_this.attr('href'));

            _this.on('click', function(e) {
                e.preventDefault();
                _target.toggleClass('is-visible');
                _this.toggleClass('is-active');
                _body.toggleClass('is-toggle');
            });
        });


        /* FOR FULL SCREEN TOGGLE */
        var _body = $('html');
        var _toggle = $('.fullview-js');
        _toggle.each(function() {
            var _this = $(this),
                _target = $(_this.attr('href'));

            _this.on('click', function(e) {
                e.preventDefault();
                _target.toggleClass('is-visible');
                _this.toggleClass('is-active');
                _body.toggleClass('is-fullview');
            });
        });


    });
</script>


</body>

</html>