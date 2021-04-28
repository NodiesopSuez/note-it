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
        let note = $(document).find('.note_section .note');
        $(note).find('.note_title').css('display', 'none');
        $(note).find('.back_cover').height('0px');
        $(note).find('.note_base').width('0px');
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
        let radio = $('<input>')
                            .attr({
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
    function createNoteIcon(class_name, title_p){
        let note = $('<div>').addClass(`note ${class_name}`);
        let note_base = $('<div>').addClass('note_base');
        let note_title = $('<div>').addClass('note_title');
        let note_title_p = $('<p>').text(title_p);
        $(note_title).append(note_title_p);
        let back_cover = $('<div>').addClass('back_cover');
        $(note).append(note_base, note_title, back_cover);
         
        return note;
    }

    //chapterアイコンを作る
    function createChapterIcon(class_name, title_p){
        let chapter = $('<div>').addClass(`chapter ${class_name}`);
        let chapter_title = $('<p>').text(title_p);
        $(chapter).append(chapter_title);

        return chapter;
    }


/* ------------------------------------------------------------------------------ */    
    
    //新規ノート選択ボタンと既存ノートリストを表示
        hideNotes()
        .then(showNotes());

    //新規ノート選択ボタンをクリックしたら
    $(document).on("click", 'label[for="new_note"], .change_color',function(){
        //note_section内の全要素削除
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
            let exist_note_set = labelConnectRadio('note_existence', 'exist', 'exist_note', 'basic', note_icon);

            $('.note_section').append(exist_note_set); //カラーリスト・既存ノート選択ボタンの順に挿入

            return defer.promise();
        }

        //一旦隠して表示
        createColorList().then(hideNotes()).then(showNotes());
    });


    //ノートのカラーが選択されたら、タイトル入力フォームを表示
    $(document).on("click", '.color_label', function(){
        function createElementsForSelectedColor(){
            //選択カラーのradioをcheckedにしておく
            let selected_id = $(this).attr('for');
            $(`#new_note, #${selected_id}`).prop('checked', true);
            let selected_color = $(`#${selected_id}`).val();


            /* #exist_note, [for="exist_note"] */
            //new_noteのradio・選択カラー・既存ノート選択以外のボタンを削除
            let leave_obj = $(`#new_note, #${selected_id}, [for="${selected_id}"]`); 
            let the_other_notes = $('.note_section').children().not(leave_obj);
            $(the_other_notes).remove();

            //既存ノートの選択ボタンを作成
            note_icon = createNoteIcon('basic', 'EXIST NOTE');
            let exist_note_set = labelConnectRadio('note_existence', 'exist', 'exist_note', 'basic', note_icon);
            $('.note_section').append(exist_note_set);
            
            //カラー変更ボタン追加
            let change_color = createNoteIcon('change_color', 'CHANGE COLOR');
            $(change_color).addClass('basic').insertBefore('[for="exist_note"]');

            //選択されたノートアイコンの<p>要素を<textarea>に変更
            let note_title_form = $('<textarea>').attr({name: "new_note_title", placeholder: "enter the note title"});
            $(this).find('.note_title > p').replaceWith(note_title_form);
            let note_title_form_icon = $(this).children().unwrap().addClass('note_title_form cassette');

            
            //チャプタータイトルの入力フォームを作成
            let new_chapter_radio = $('<input>')
                                            .attr({
                                                name:  "chapter_existence",
                                                type:  "radio",
                                                value: "new",
                                            })
                                            .prop({checked: true});
            let chapter_icon = createChapterIcon(`new_chapter_title ${selected_color}`, '');
            let chapter_title_form = $('<input>').attr({name: "new_chapter_title", type: "text", placeholder: "enter the chapter title"});
            $(chapter_icon).find('p').replaceWith(chapter_title_form);
            

            //.chapter_sectionの全要素削除して、作成した要素を挿入
            $('.chapter_section').children().remove();
            $('.chapter_section').prepend(new_chapter_radio, note_title_form_icon, chapter_icon);
            

            return defer.promise();
        }



        createElementsForSelectedColor().then(hideNotes()).then(showNotes());
    });

    //既存ノート選択ボタンがクリックされたら
    $(document).on("click", '[for="exist_note"]', function(){
        //phpからuser_idを取得
        var user_id = php.user_id;
        
        //note_section内の要素全削除
        $('.note_section').children().remove();

        //新規ノートの選択ボタンを作成して.note_sectionに挿入
        note_icon = createNoteIcon('basic', 'NEW NOTE');
        let new_note_set = labelConnectRadio('note_existence', 'new', 'new_note', 'basic', note_icon);
        $('.note_section').prepend(new_note_set);

        //ユーザーIDからノート一覧取得
        $.ajax({
            url : './get_note_list.php',
            type: 'post',
            data: { 'user_id': user_id },
            dataType: 'json',
        }).done(function(note_list){
            console.log(note_list);
            //既存ノートのアイコン作成して.note_sectionに挿入
            $.each(note_list, function(key,val){
                note_icon = createNoteIcon(val.color, val.note_title);
                let exist_note_listset = labelConnectRadio('note_id', key, `note_${key}`, "exist_note_list", note_icon);
                $('.note_section').append(exist_note_listset);
            })

            hideNotes().then(showNotes());
        }).fail(function(XMLHttpRequest, textStatus, errorThrown){
            console.log(XMLHttpRequest);
            console.log(textStatus);
            console.log(errorThrown);
            changeMsgDanger().then(scrollToTop());
        });
    });

    $(document).on("")
    
});