<!--------vote-PopUp---------->
<div class="vote-bg">
    <div class="vote-container">
        <div class="vote-hed">
            <h3>Vote</h3>
            <span class="voteClose">X</span>
        </div>
        <div class="vote-body">
            <p id="vote_nm"></p>
            <span id="vote_pts"></span>
            <p id="vote_rnk"></p>
            <a href="javascript:void(0);" class="btn voteBtn">VOTE FOR ME</a>
            <a href="javascript:void(0);">LEGAL DISCLAIMER: </a>
        </div>
    </div>
</div>
<!--------vote-PopUp---------->
<script src="<?=base_url('assets/js/jquery.min.js')?>"></script>
<script src="<?=base_url('assets/js/sweetalert2.min.js')?>"></script>
<script src="<?=base_url('assets/js/jquery.mCustomScrollbar.concat.min.js')?>"></script>
<script src="<?=base_url('assets/js/jquery.multipurpose_tabcontent.js')?>"></script>

<script>
    $(document).ready(function() {
        $('.sidebar-menu > li > a').click(function() {
            $(this).next().slideToggle();
            $(this).parent().siblings().find('ul').slideUp();
        });
        $('.list').click(function() {
            $('.list-widget .col').removeClass('gridview').addClass('listview');
        });
        $('.grid').click(function() {
            $('.list-widget .col').removeClass('listview').addClass('gridview');
        });
        $('.logbtn').click(function() {
            $('.modal').addClass('open');
        });
        $('.modal .overlay').click(function() {
            $('.modal').removeClass('open');
        });
        $.mCustomScrollbar.defaults.scrollButtons.enable = true; //enable scrolling buttons by default
        //$.mCustomScrollbar.defaults.axis = "yx"; //enable 2 axis scrollbars by default
        $("#content-ltn").mCustomScrollbar({
            theme: "inset"
        });
        $(".chat-ul").mCustomScrollbar({
            theme: "inset"
        }).mCustomScrollbar("scrollTo", "bottom", {
            scrollInertia: 0
        });
        $(".second_tab").champ({
            plugin_type: "tab",
            side: "left",
            active_tab: "1",
            controllers: "false"
        });
        // $(".msg-container").mCustomScrollbar({
        // theme: "inset"
        // }).mCustomScrollbar("scrollTo", "top", {
        // scrollInertia: 0
        // }); 
    })


    // var objDiv = $(".chat-sec");
    // var h = objDiv.get(0).scrollHeight;
    // objDiv.animate({scrollTop: h});

    function updateScroll() {
        var element = document.getElementsByClassName(".chat-sec");
        element.scrollTop = element.scrollHeight;
    }

</script>
<script src="<?=base_url('assets/js/custom.js')?>"></script>
<script src="<?=base_url('assets/js/videoChat.js')?>"></script>
</body>

</html>
