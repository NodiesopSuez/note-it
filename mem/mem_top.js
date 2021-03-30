$(function(){
    var d = new $.Deferred();

    //ページトップに自動スクロール
    function scrollToTop(){
        $("html,body").animate({scrollTop: 0},{queue: false}); 
        return d.promise();
    };

    //note_listを非表示にする
    function hideNotes(){
        $('.note_list > .note').each(function(){
            $(this).children('.back_cover').height('0px');
            $(this).children('.note_base').width('0px');
            $(this).find('.note_title').hide();
            $(this).hide();
        });
    }

    //note_listを表示する
    function showNotes(){
        $('.note_list > .note').show();
        $('.note_list .back_cover').animate({height: '161px'}, 500, 'swing', function(){
            $('.note_list .note_base').animate({width: '140px'}, 500, 'swing', function(){
                $('.note_list .note_title').show();
            })
        })
    };


    hideNotes();
    showNotes();



})