<div class="link-main">
    <p>
        Share this registration link to your friends.
        You will receive a commission for every lesson.
    </p>
    <p id="link-box" class="link-box"><?php echo $u_link ? $_SERVER['SERVER_NAME'] . '?promo='. $u_link : ' please refresh page ' ?></p>
    <a target="_blank" href="https://google.com"><button class="btn btn-success d-block mb-2" style="width: 250px">Share via email</button></a>
    <button onclick="copyToClipboard('#link-box')" class="btn btn-success d-block" style="width: 250px">Copy line</button>
</div>


<style>
    .link-main{
        margin: 40px;
        max-width: 500px;
    }
    .btn{
        background: #f9b73c;
        color: black;
        font-weight: 600;
    }
    p{
        font-weight: 600;
        font-size: 18px;
        color: black;
        line-height: 1.1;
    }
    .link-box{
        background: #fafafa;
        border: 1px solid #ece9e9;
        padding: 6px;
    }
    .active-link{
        background: #e2f3e2;
        border: 1px solid #71a871;
    }
</style>
<script>
    function copyToClipboard(element) {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($(element).html()).select();
        document.execCommand("copy");
        $temp.remove();
        $('#link-box').addClass('active-link');
    }
</script>
