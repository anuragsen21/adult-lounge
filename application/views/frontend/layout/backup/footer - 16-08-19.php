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
        $.mCustomScrollbar.defaults.axis = "yx"; //enable 2 axis scrollbars by default
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
    })
    base_url = '<?=base_url()?>';

</script>
<script src="<?=base_url('assets/js/custom.js')?>"></script>
<!--<script src="<?=base_url('assets/js/video-chat.js')?>"></script>-->
</body>

</html>
