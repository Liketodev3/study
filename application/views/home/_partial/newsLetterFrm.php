        <section class="section footer-head">
            <div class="container container--fixed">
                <div class="footer-subscribe">
                    <div class="section-title">
                        <h2>We’d love to send you our Newsletter</h2>
                    </div>
                    <form name="frmNewsLetter"  method="post" class="form footer-subscribeForm" onsubmit="setUpNewsLetter(this); return false;" >
                        <input placeholder="Enter Your Email" type="text">
                        <input value="Subscribe" type="submit">
                    </form>
                </div>
            </div>
        </section>
        

<script type = "text/javascript" >
    (function() {
        setUpNewsLetter = function(frm) { 
            var data = fcom.frmData(frm);
            fcom.updateWithAjax(fcom.makeUrl('MyApp', 'setUpNewsLetter'), data, function(t) {
                if(t.status){
                    document.frmNewsLetter.reset();
                }
            });
        };
    })();
</script>