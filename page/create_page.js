'use strict';

$(function(){
    //ページトップに自動スクロール
    function scrollToTop(){
        $('html,body').animate({scrollTop: 0}, {queue: false}); 
        return d.promise();
    };

    //特定のオブジェクトにスクロール
    function scrollToObject(object){
        $(object).show();
        let selected_obj_top = $(object).offset().top;
        $('html, body').animate({scrollTop: selected_obj_top}, 500);
    }

    //note_listを非表示にする
    function hideNotes(){
        $('.note_section .note').each(function(){
            $(this).children('.back_cover').height('0px');
            $(this).children('.note_base').width('0px');
            $(this).find('.note_title').hide();
        });
    }

    var deffered = new $.Deferred();

    //note_listを表示する
    function showNotes(){
        $('.note_section .note').find('.back_cover').animate({height: '161px'}, 500, 'swing', function(){
            $('.note_section .note').find('.note_base').animate({width: '140px'}, 500, 'swing', function(){
                $('.note_section .note').find('.note_title').show();
            })
        })
    };
    
    //新規ノート選択ボタンと既存ノートリストを表示
    hideNotes();
    showNotes();
    

});