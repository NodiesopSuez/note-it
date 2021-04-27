'use strict';

$(function(){
    let defer = new $.Deferred;
    let note_icon;
    

    //ページトップに自動スクロール
    function scrollToTop(){
        $('html,body').animate({scrollTop: 0}, {queue: false}); 
        return defer.promise();
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

    //radioボタンとlabel要素を作成して繋ぐ
    function labelConnectRadio(set_name, set_value, set_id, set_class, set_icon){
        //radioボタン作成
        let radio = $('<input>').attr({
            name  : set_name,
            value : set_value,
            type  : 'radio',
            id    : set_id,
        });
        //label要素作成
        let label = $('<label>').attr({ for : set_id }).addClass(set_class);
        //labelに各種アイコンを挿入する(note_icon, chapter_icon)
        label = $(label).append(set_icon);

        let label_radio_set = [radio, label];

        return label_radio_set;
    }

    //noteアイコンを作る
    function createNoteIcon(class_name,title_p){
        let note = $('<div>').addClass('note ' + class_name);
        let note_base = $('<div>').addClass('note_base');
        let note_title = $('<div>').addClass('note_title');
        let note_title_p = $('<p>').text(title_p);
        $(note_title).append(note_title_p);
        let back_cover = $('<div>').addClass('back_cover');
        $(note).append(note_base, note_title, back_cover);
         
        return note;
    }


/* ------------------------------------------------------------------------------ */    
    
    //新規ノート選択ボタンと既存ノートリストを表示
        hideNotes()
        .then(showNotes());

    //新規ノート選択ボタンをクリックしたら
    $(document).on("click", 'label[for="new_note"], .change_color',function(){
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

        //color_listでノートアイコンを作成 class_name:配色クラス title_p:表示カラー名
        function createColorList(){
            $.each(color_list, function(class_name, title_p){
                //note_iconを作成
                note_icon = createNoteIcon(class_name, title_p);

                //radioボタンとlabel要素作成
                let color_choices = labelConnectRadio('note_color', class_name, 'new_'+class_name, 'color_label', note_icon);
                $('.note_section').append(color_choices);
            });

            //既存ノートの選択ボタンを作成
            note_icon = createNoteIcon('basic', 'EXIST NOTE');
            let exist_note_choice = labelConnectRadio('note_existence', 'exist', 'exist_note', 'basic', note_icon);

            $('.note_section').append(exist_note_choice); //カラーリスト・既存ノート選択ボタンの順に挿入

            return defer.promise();
        }

        //一旦隠して表示
        createColorList()
        .then(hideNotes())
        .then(showNotes());
    });

    //ノートのカラーが選択されたら、タイトル入力フォームを表示
    $(document).on("click", '.color_label', function(){
        let selected_id = $(this).attr('for');
        $(`#${selected_id}`).prop('checked', true);

        //選択カラー・既存ノート選択以外のボタンを削除
        let leave_obj = $(`#${selected_id}, [for="${selected_id}"], #exist_note, [for="exist_note"]`); 
        let the_other_notes = $('.note_section').children().not(leave_obj);
        $(the_other_notes).remove();

        //選択されたノートアイコンの<p>要素を<textarea>に変更
        let note_title_form = $('<textarea>').attr({name: "new_note_title"});
        $(this).find('.note_title > p').replaceWith(note_title_form);
        $(this).children('.note').addClass('note_title_form');
        $(this).children().unwrap();

        //カラー変更ボタン追加
        let change_color = createNoteIcon('change_color', 'CHANGE COLOR');
        $(change_color).insertBefore('[for="exist_note"]');
        $('.change_color').addClass('basic');
    });

    //既存ノート選択ボタンがクリックされたら
    $(document).on("click", '[for="exist_note"]', function(){
        //phpからuser_idを取得
        var user_id = php.user_id;
        
        //note_section内の要素全削除
        $('.note_section').children().remove();

        //新規ノートの選択ボタンを作成
        note_icon = createNoteIcon('basic', 'NEW NOTE');
        let exist_note_choice = labelConnectRadio('note_existence', 'new', 'new_note', 'basic', note_icon);

        
        //ユーザーIDからノート一覧取得
        $.ajax({
            url : './get_note_list.php',
            type: 'post',
            data: { 'user_id': user_id },
            dataType: 'json',
        }).done(function(note_list){
            console.log(note_list);
            //既存ノートのアイコン作成
            $.each(note_list, function(key,val){
                console.log(key)
                console.log(val)
                note_icon = createNoteIcon(val.color, val.note_title);

                
                labelConnectRadio(set_name, set_value, set_id, set_class, set_icon)    

            })

        }).fail(function(XMLHttpRequest, textStatus, errorThrown){
            console.log(errorThrown);
            changeMsgDanger().then(scrollToTop());
        });
        

        $('.note_section').append(exist_note_choice); //カラーリスト・既存ノート選択ボタンの順に挿入

        return defer.promise();

    });
    
});