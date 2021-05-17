'use strict';

$(function(){
    let defer = new $.Deferred;
    let selected_color;
    
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
    function createChapterIcon(class_name, chapter_title){
        let chapter = $('<div>').addClass(`chapter ${class_name}`);
        let chapter_p = $('<p>').text(chapter_title);
        $(chapter).append(chapter_p);

        return chapter;
    }

    //新規ノートの選択ボタン
    let new_note_icon = createNoteIcon('basic', 'NEW NOTE');
    new_note_icon = $(new_note_icon).wrapAll('<label>').parent().attr({ for : 'new_note' });
    
    //既存ノートの選択ボタン
    let exist_note_icon = createNoteIcon('basic', 'EXIST NOTE');
    exist_note_icon = $(exist_note_icon).wrapAll('<label>').parent().attr({ for : 'exist_note' });

    //ページタイプ選択ボタン
    //アイコン
    let page_wrapback = $('<div>').addClass('wrapback');
    //typeA
    let page_type_text_a = $('<p>').text('Type A');
    let page_type_a = $('<div>').addClass('page').prepend(page_wrapback.clone(), page_type_text_a).wrapAll('<label>').parent().attr({ for : 'page_a'});
    //typeB
    let page_type_text_b = $('<p>').text('Type B');
    let page_type_b = $('<div>').addClass('page').prepend(page_wrapback.clone(), page_type_text_b).wrapAll('<label>').parent().attr({ for : 'page_b'});
    $('.page_type').prepend(page_type_b,page_type_a);

    //ページコンテンツ入力フォーム
    //typeA
    let page_a_title     = $('<input>').addClass('page_title_a').attr({ type : 'text', name : 'page_title_a', placeholder : 'ページタイトル'});
    let a_meaning        = $('<input>').addClass('meaning').attr({ type : 'text', name : 'meaning', placeholder : '意味'});
    let a_syntax         = $('<input>').addClass('syntax').attr({ type : 'text', name : 'syntax', placeholder : '構文'});
    let a_syn_memo       = $('<textarea>').addClass('syn_memo').attr({ name : 'syn_memo', placeholder : '構文メモ'});
    let a_ex             = $('<textarea>').addClass('ex').attr({ name : 'example', placeholder : '例文'});
    let a_ex_memo        = $('<textarea>').addClass('ex_memo').attr({ name : 'ex_memo', placeholder : '例文メモ'});
    let a_example        = $('<div>').prepend(a_ex, a_ex_memo); //exとex_,ex_memoの塊
    let a_memo           = $('<textarea>').addClass('memo').attr({ name : 'memo', placeholder : 'メモ' });
    let page_a_form = $('<div>').addClass('page_base a')
                                .prepend(page_a_title, a_meaning, a_syntax, a_syn_memo, a_example, a_memo);
    //typeB
    let page_b_title     = $('<input>').addClass('page_title').attr({ type : 'text', name : 'page_title_b', placeholder : 'ページタイトル'});
    let contents         = $('<div>').addClass('contents text').attr({ id : 'contents_1', contentEditable : true });
    let hid_content      = $('<input>').attr({ id : 'hid_contents_1', type : 'hidden', name : 'contents_1', value : ''});
    let form_block       = $('<div>').addClass('form_block').attr({ id : 'form_block_1'});

    let add_text_btn     = $('<button>').addClass('btn').attr({ id : 'add_text', type : 'button'}).text('テキストを追加する');
    let add_img_btn      = $('<button>').addClass('btn').attr({ id : 'add_img', type : 'button'}).text('画像を追加する');
    let add_code_btn     = $('<button>').addClass('btn').attr({ id : 'add_code', type : 'button'}).text('コードを追加する');
    let add_quote_btn    = $('<button>').addClass('btn').attr({ id : 'add_quote', type : 'button'}).text('引用を追加する');
    let buttons_row      = $('<div>').addClass('buttons row')
                                    .prepend(add_text_btn, add_img_btn, add_code_btn, add_quote_btn);
    let page_b_form = $('<div>').addClass('page_base b')
                                .prepend(page_b_title, contents, hid_content, form_block, buttons_row);


    //ページトップに自動スクロール
    function scrollToTop(){
        $('html,body').animate({scrollTop: 0}, {queue: false}); 
        return defer.promise();
    };

    //特定のオブジェクトにスクロール
    function scrollToObject(object){
        $(object).show();
        let selected_obj_top = $(object).offset().top;
        $('html, body').animate({ scrollTop: selected_obj_top }, 500);
        return defer.promise();
    }

    //note_listを非表示にする
    function hideNotes(){
        $('.note_section .note_base').width('0px').hide();
        $('.note_section .back_cover').height('0px').hide();
        $('.note_section .note_title').css('display', 'none');
        return defer.promise();
    }

    //note_listをアニメーション表示する
    function showNotes(){
        $('.note_section .back_cover').show().animate({ height: '161px' }, 500, 'swing', function(){
            $('.note_section .note_base').show().delay(100).animate({ width: '140px' }, 500, 'swing', function(){
                $('.note_section .note_title').delay(100).css({ display: 'flex' });
            })
        });
        return defer.promise();
    };

    //新規ノート選択ボタンと既存ノートリストを表示
    function createExistNoteList(){
        var user_id = php.user_id;

        //note_existence=exist をcheckedにする
        $('#exist_note').prop({ checked: true });

        //各セクションの全子要素削除して　note_sectionに新規ノート選択ボタンを挿入
        $('.note_section, .chapter_section, .page_type, .contents_section').children().remove();
        $('.note_section').prepend(new_note_icon);

        //ユーザーIDからノート一覧取得
        $.ajax({
            url : './get_note_list.php',
            type: 'post',
            data: { 'user_id': user_id },
            dataType: 'json',
        }).done(function(note_list){
            //既存ノートリストのアイコン作成して.note_sectionに挿入
            $.each(note_list, function(key,val){
                let ex_note_label = createNoteIcon(val.color, val.note_title);
                ex_note_label = $(ex_note_label).wrapAll('<label>').parent().addClass('exist_note_list').attr({ for : `note_${key}` });
                let ex_note_radio = $('<input>').attr({
                                            name  : "note_id",
                                            value : key,
                                            type  : "radio",
                                            id    : `note_${key}`,
                                        });
                $('.note_section').append(ex_note_label, ex_note_radio);
            })
            hideNotes().then(showNotes());
        }).fail(function(XMLHttpRequest, textStatus, errorThrown){
            console.log(XMLHttpRequest);
            console.log(textStatus);
            console.log(errorThrown);
            changeMsgDanger().then(scrollToTop());
        });
    }

    
/* ページに入ったら----------------------------------------------------------------- */    
    
    //新規ノート選択ボタンと既存ノートリストを表示
    createExistNoteList();

    
/* 新規ノート選択------------------------------------------------------------------- */ 

    //新規ノート選択ボタン・カラー変更ボタンをクリックしたら
    $(document).on("click", 'label[for="new_note"], .change_color',function(){ 
        //note_exsistence=new をcheckedにする
        $('#new_note').prop({ checked : true });
        
        //カラーリスト
        let color_list = {
            'blue'   : 'ブルー',
            'pink'   : 'ピンク',
            'yellow' : 'イエロー',
            'green'  : 'グリーン',
            'purple' : 'パープル',
        };

        //color_listでノートアイコン・既存ノート選択ボタンを作成,note_sectionに挿入
        function createColorList(){
            //各セクションの子要素全削除
            $('.note_section, .chapter_section, .page_type, .contents_section').children().remove();
            $('.note_section').append(exist_note_icon);
            
            $.each(color_list, function(class_name, title_p){   
                let color_icon = createNoteIcon(class_name, title_p);　//class_name:配色クラス title_p:表示カラー名
                color_icon = $(color_icon).wrapAll('<label>').parent().addClass('color_label').attr({ for : `new_${class_name}` });
                $('.note_section').prepend(color_icon);
            });
            return defer.promise();
        }

        //一旦隠して表示
        createColorList().then(hideNotes()).then(showNotes());
    });


    //ノートのカラーが選択されたら、タイトル入力フォームを表示
    $(document).on("click", '.color_label', function(){
        //選ばれたnote_colorをcheckedにする
        let selected_id = $(this).attr('for');
        $(`#${selected_id}`).prop({ checked : true });
        
        //選択されたカラー名を取得
        selected_color = $(this).attr('for').replace("new_", "");

        //カラー変更ボタン作成
        let change_color = createNoteIcon('change_color', 'CHANGE COLOR').addClass('basic');

        //選択されたノートアイコンの<p>要素を<textarea>に変更
        let note_title_form = $('<textarea>').attr({name: "new_note_title", placeholder: "enter the note title"});
        $(this).find('.note_title > p').replaceWith(note_title_form);
        let note_title_form_icon = $(this).children().unwrap().addClass('note_title_form note cassette');

        //チャプタータイトルの入力フォームを作成
        let chapter_icon = createChapterIcon(`new_chapter_title chapter_cassette ${selected_color}`, '');
        let chapter_title_form = $('<input>').attr({name: "new_chapter_title", type: "text", placeholder: "enter the chapter title"});
        $(chapter_icon).find('p').replaceWith(chapter_title_form);

        //page_typeにノートタイトル・チャプタータイトルの入力フォームを挿入
        $('.page_type').children().remove();
        $('.page_type').prepend(note_title_form_icon, chapter_icon, page_type_a, page_type_b);

        //contents_sectionに一旦page_a_formを挿入
        $('.contents_section').prepend(page_a_form);

        //note_sectionにカラー変更ボタン、既存ノート選択ボタンを挿入
        $('.note_section').children().remove();
        $('.note_section').append(change_color, exist_note_icon);

        hideNotes().then(showNotes());
    });


/* 既存ノート選択時----------------------------------------------------------------- */ 

    //既存ノート選択ボタンがクリックされたら
    $(document).on("click", '[for="exist_note"]', function(){
        //note_existence = exist をcheckedにする
        $('#exist_note').prop('checked', true);
        //新規ノート選択ボタンと既存ノートリストを表示
        createExistNoteList();
    });

    //既存ノートリストのアイコンが選択されたら
    $('.note_section').on ("click", '.exist_note_list', function(){
        //クリックしたノートだけhideする
        $('.note_section').children().show();
        $(this).hide();

        //選択されたノートのカラーとタイトルでnote_cassetteを作成
        let selected_note_color = $(this).children('div').attr('class').replace("note", "");
        let selected_note_title = $(this).find('.note_title > p').text();
        let note_cassette = createNoteIcon(`cassette ${selected_note_color}`, selected_note_title);

        //ノートIDからチャプター一覧取得
        let selected_note_id = $(this).attr('for').replace("note_", "");
        console.log(`idは${selected_note_id}`);
        $.ajax({
            url : '../get_lists/get_chapter_list.php',
            type: 'post',
            data: { 'selected_note_id': selected_note_id },
            dataType: 'json',
        }).done(function(chapter_list){
            console.log(chapter_list);
            //新規チャプター選択ボタン
            let new_chapter_icon = createChapterIcon(selected_note_color, 'NEW');
            new_chapter_icon = $(new_chapter_icon).wrapAll('<label>').parent().attr({ for : 'new_chapter'});

            //chapter_section,apge_type,contents_sectionの子要素全削除
            $('.chapter_section, .page_type, .contents_section').children().remove();
            $('.chapter_section').prepend(note_cassette, new_chapter_icon,);

            //既存チャプターのアイコン作成して.chapter_sectionに挿入
            $.each(chapter_list, function(key,val){
                let ex_chapter_label = createChapterIcon(selected_note_color, val.chapter_title);
                ex_chapter_label = $(ex_chapter_label).wrapAll('<label>').parent().addClass('exist_chapter_list').attr({ for : `chapter_${key}` });
                let ex_chapter_radio = $('<input>').attr({
                                                        name  : "chapter_id",
                                                        value : key,
                                                        type  : "radio",
                                                        id    : `chapter_${key}`,
                                                    });
                $('.chapter_section').append(ex_chapter_label, ex_chapter_radio);
                //ローカルストレージに各チャプターidとpage_tyepを格納
                localStorage.setItem(`chapter_${key}`, val.page_type);
            })
            //exist_chapter_set.find('div, p').css({width: '0px', padding: '0px'});
            //chapter_list表示
            /* exist_chapter_set.find('div').animate({width: '200px'}, 400, 'swing', function(){
                exist_chapter_set.find('p').animate({width: '170px'}).text(val.chapter_title);
            }); */
            return defer.promise();
        }).fail(function(XMLHttpRequest, textStatus, errorThrown){
            console.log(XMLHttpRequest);
            console.log(textStatus);
            console.log(errorThrown);
            changeMsgDanger().then(scrollToTop());
        });

    });


/* チャプター選択時-------------------------------------------------------------- */ 
    //新規チャプター選択ボタンがクリックされたら
    $(document).on("click", '[for="new_chapter"]', function(){
        //選択したボタンだけ非表示
        $('.chapter_section').children().show();
        $(this).hide();

        //page_typeに選択済みノートアイコン・チャプタータイトルの入力フォームを挿入
        $('.page_type, .contents_section').children().remove();
        $('.chapter_section').find('.note.cassette').clone(false).prependTo('.page_type');

        selected_color = $(this).children().attr('class').replace("chapter", "");
        let chapter_icon = createChapterIcon(`new_chapter_title chapter_cassette ${selected_color}`, '');
        let chapter_title_form = $('<input>').attr({name: "new_chapter_title", type: "text", placeholder: "enter the chapter title"});
        $(chapter_icon).find('p').replaceWith(chapter_title_form);
        
        $('.page_type').append(chapter_icon, page_type_a, page_type_b);

        //contents_sectionに一旦page_a_formを挿入
        $('.contents_section').prepend(page_a_form);
    });

    //既存チャプターリストのアイコンがクリックされたら
    $(document).on("click", '.exist_chapter_list', function(){
        //chapter_existence=exist をcheckedにする
        $('#exist_chapter').prop('checked', true);

        //選択したボタンだけ非表示
        $('.chapter_section').children().show();
        $(this).hide();

        //note_cassetteとchapter_cassetteをpage_typeに挿入
        $('.page_type, .contents_section').children().remove();
        $('.chapter_section').find('.note.cassette').clone(false).prependTo('.page_type');
        $(this).clone(false).children().unwrap().addClass('chapter_cassette').appendTo('.page_type');

        let selected_chapter = $(this).attr('for');
        let page_type = localStorage.getItem(selected_chapter);

        //page_typeにより表示するフォームを分岐
        if(page_type == 1) {
            $('#page_a').prop('checked', true);
            $('.contents_section').prepend(page_a_form);
        }else{
            $('#page_b').prop('checked', true);
            $('.contents_section').prepend(page_b_form);
        }

    });


/* page_type_bのコンテンツ追加--------------------------------------------------- */ 

    
});