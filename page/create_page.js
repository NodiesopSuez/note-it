'use strict';

$(function(){
    let defer = new $.Deferred;

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
        return defer.promise();
    }

    //note_listを非表示にする
    function hideNotes(){
        let note = $('.note_section .note');
        $(note).children('.note_title').css('display', 'none');
        $(note).children('.back_cover').height('0px');
        $(note).children('.note_base').width('0px');
        return defer.promise();
    }

    //note_listを表示する
    function showNotes(){
        let note = $('.note_section .note');
        $(note).find('.back_cover').animate({height: '161px'}, 500, 'swing', function(){
            $(note).find('.note_base').animate({width: '140px'}, 500, 'swing', function(){
                $(note).find('.note_title').css({display: 'flex'});
            })
        });
        return defer.promise();
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
        hideNotes()
        .then(showNotes());

    

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

        //color_listでノートアイコンを作成
        function createColorList(){
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
            return defer.promise();
        }

        //一旦隠して表示
        createColorList()
        .then(hideNotes())
        .then(showNotes());
    });

    
});