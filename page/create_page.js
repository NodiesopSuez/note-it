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
            $(this).find('.note_title, p').hide();
        });
    }

    //note_listを表示する
    function showNotes(){
        $('.note_section .note').find('.back_cover').animate({height: '161px'}, 500, 'swing', function(){
            $('.note_section .note').find('.note_base').animate({width: '140px'}, 500, 'swing', function(){
                $('.note_section .note').find('.note_title, p').show();
            })
        })
    };

    //noteアイコンを作る
    function createNoteIcon(en_color,jp_color){
        let note = $('<div>').addClass('note ' + en_color);
        let note_base = $('<div>').addClass('note_base');
        let note_title = $('<div>').addClass('note_title');
        let note_title_p = $('<p>').text(jp_color);
        $(note_title).append(note_title_p);
        let back_cover = $('<div>').addClass('back_cover');
        $(note).append(note_base, note_title, back_cover);
        return note;
    }
    
    //新規ノート選択ボタンと既存ノートリストを表示
    hideNotes();
    showNotes();

    //新規ノート選択ボタンをクリックしたら
    $(document).on("click", 'label[for="new_note"]',function(){
        //note_section内の要素全削除
        $('.note_section').children().remove();

        //カラーリスト
        let color_list = {
            'blue'   : 'ブルー',
            'pink'   : 'ピンク',
            'yellow' : 'イエロー',
            'green'  : 'グリーン',
            'purple' : 'パープル',
        };
        let color_choices = [];

        $.each(color_list, function(key, val){
            let color_radio = $('<input>').attr({
                name  : 'note_color',
                value : key,
                type  : 'radio',
                id    : 'new_'+key,
            });
            let color_label = $('<labal>').addClass('color_label').attr({ for : 'new_'+key });
            let note_icon = createNoteIcon(key,val);
            color_label = $(color_label).append(note_icon);
            $('.note_section').append(color_radio, color_label);
        });
        
        hideNotes();
    })
});