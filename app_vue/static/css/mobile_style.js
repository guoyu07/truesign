$(function() {
    // rem改变字体
    var wWidth = $(window).width();
    var scale = 100*wWidth/750;
    $("html").css("font-size",scale);

    $('#menu').click(function () {
        $('#mask').show('fast',function () {
            $('#mask li').css('margin-left',0);
        });
    });
    $('#mask').click(function () {
        $(this).hide();
        $('#mask li').css('margin-left','-175px');
    })

    $("#backtop").click(function(){
        $("html,body").animate({scrollTop:0},800);
    });
});