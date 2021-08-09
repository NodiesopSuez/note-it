'use strict';

$(function(){
    localStorage.clear();

    let defer = new $.Deferred;
    let selected_color, title, attribute_for;
    
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

    //新規ノートもしくは既存ノートの選択ボタン
    function createNoteButton(title, attribute_for){
        let note_button = createNoteIcon('basic', title).wrapAll('<label>').parent().attr({ for : attribute_for });
        return note_button;
    }

    //ページタイプ選択ボタン
    function createPageTypeButton(){
        //アイコン
        let page_wrapback = $('<div>').addClass('wrapback');

        let types = {'a': {'text' : 'Type A', 'attribute_for' : 'page_a'},
                     'b': {'text' : 'Type B', 'attribute_for' : 'page_b'}};

        $.each(types, function(key, val){
            let page_type_text = $('<p>').text(val.text);
            let page_type = $('<div>').addClass('page').prepend(page_wrapback.clone(), page_type_text).wrapAll('<label>').parent().attr({ for : val.attribute_for});

            $('.page_type').append(page_type);
        })
    }

    const submit_btn = $('<button>').addClass('submit').attr({ role : 'submit'}).text('submit'); //送信ボタン
    
    //ページコンテンツ入力フォーム
    //typeA
    const page_a_title     = $('<input>').addClass('page_title').attr({ type : 'text', name : 'page_title', placeholder : 'ページタイトル'});
    const a_meaning        = $('<input>').addClass('meaning').attr({ type : 'text', name : 'meaning', placeholder : '意味'});
    const a_syntax         = $('<input>').addClass('syntax').attr({ type : 'text', name : 'syntax', placeholder : '構文'});
    const a_syn_memo       = $('<textarea>').addClass('syn_memo').attr({ name : 'syn_memo', placeholder : '構文メモ'});
    const a_ex             = $('<textarea>').addClass('ex').attr({ name : 'example', placeholder : '例文'});
    const a_ex_memo        = $('<textarea>').addClass('ex_memo').attr({ name : 'ex_memo', placeholder : '例文メモ'});
    const a_example        = $('<div>').addClass('example').prepend(a_ex, a_ex_memo); //exとex_,ex_memoの塊
    const a_memo           = $('<textarea>').addClass('memo').attr({ name : 'memo', placeholder : 'メモ' });
    const page_a_form = $('<div>').attr({ class : 'page_base a' })
                                .prepend(page_a_title, a_meaning, a_syntax, a_syn_memo, a_example, a_memo);
    //typeB
    const page_b_title     = $('<input>').addClass('page_title').attr({ type : 'text', name : 'page_title', placeholder : 'ページタイトル'});
    const contents         = $('<textarea>').addClass('contents text').attr({ name : 'contents_1'});

    //削除ボタン
    let delete_btn  = $('<button>').attr({ class : 'delete_btn', role : 'button' }); //val,id属性は後付け
    let delete_icon = $('<img>').attr({ class : 'delete_icon', src : '/public/img/delete_btn.svg'});
    $(delete_btn).prepend(delete_icon);

    const form_block       = $('<div>').addClass('form_block').attr({ id : '1_form_block'}).prepend(contents, delete_btn);

    const add_text_btn     = $('<button>').addClass('btn').attr({ id : 'add_text', type : 'button'}).text('+ text');
    const add_img_btn      = $('<button>').addClass('btn').attr({ id : 'add_img', type : 'button'}).text('+ image');
    const buttons_row      = $('<div>').addClass('add_contents row')
                                    .prepend(add_text_btn, add_img_btn, /* add_code_btn, add_quote_btn */);
    const page_b_form = $('<div>').attr({ class : 'page_base b' })
                                .prepend(page_b_title, form_block, buttons_row);

                                
    /* メソッド ----------------------------------------------------------------------- */ 

    //ページトップに自動スクロール
    function scrollToTop(){
        $('html,body').animate({scrollTop: 0}, {queue: false}); 
        return defer.promise();
    };

    //特定のオブジェクトにスクロール
    function scrollToObject(object){
        let container_height = $('.container').height();
        let note_chapter_section_height = $('.note_section, .chapter_section').height();
        let height_differ = container_height - note_chapter_section_height;
        if(height_differ <= 800){
            $('.container').height(container_height + 550);
        }
        let selected_obj_top = $(object).offset().top - 110;
        $('html, body').animate({ scrollTop: selected_obj_top }, 500);
    }

    //新規ノート選択ボタンと既存ノートリストを表示
    function createExistNoteList(){
        let user_id = php.user_id;

        //note_existence=exist をcheckedにする
        $('#exist_note').prop({ checked : true });

        //各セクションの全子要素削除して　note_sectionに新規ノート選択ボタンを挿入
        $('.note_section, .chapter_section, .page_type, .contents_section').children().remove();
        $('.note_section').prepend(createNoteButton('NEW NOTE', 'new_note'));

        //ユーザーIDからノート一覧取得
        $.ajax({
            url : '/controllers/note/get_note_list_controller.php',
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
    scrollToTop();


/* 新規ノート選択------------------------------------------------------------------- */ 

    //新規ノート選択ボタン・カラー変更ボタンをクリックしたら
    $(document).on("click", 'label[for="new_note"], .change_color',function(){ 
        //note_exsistence=new をcheckedにする
        $('#new_note').prop('checked', true );
        
        //カラーリスト
        let color_list = {
            'purple' : 'パープル',
            'green'  : 'グリーン',
            'yellow' : 'イエロー',
            'pink'   : 'ピンク',
            'blue'   : 'ブルー',
        };

        //color_listでノートアイコン・既存ノート選択ボタンを作成,note_sectionに挿入
        function createColorList(){
            let exist_note_count = $('.exist_note_list').length;

            //各セクションの子要素全削除
            $('.note_section, .chapter_section, .page_type, .contents_section').children().remove();
            
            if(exist_note_count > 0){
                $('.note_section').append(createNoteButton('EXIST NOTE', 'exist_note'));}
            
            $.each(color_list, function(class_name, title_p){   
                let color_icon = createNoteIcon(class_name, title_p);　//class_name:配色クラス title_p:表示カラー名
                color_icon = $(color_icon).wrapAll('<label>').parent().addClass('color_label').attr({ for : `new_${class_name}` });
                $('.note_section').prepend(color_icon);
            });
        }
        createColorList();
        $('.balloon').children('.msg').text('ノートカラーはどれにしますか?');
        scrollToTop();
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

        //ノートタイトルの入力フォーム
        let note_title_form = $('<textarea>').attr({name: "new_note_title", placeholder: "enter the note title"});
        let note_title_form_icon = $(this).children().unwrap();
        note_title_form_icon = $(note_title_form_icon).attr({ class : `note_title_form note cassette ${selected_color}`});

        //チャプタータイトルの入力フォームを作成
        let chapter_icon = createChapterIcon(`new_chapter_title chapter_cassette ${selected_color}`, '');
        let chapter_title_form = $('<input>').attr({name: "new_chapter_title", type: "text", placeholder: "enter the chapter title"});
        $(chapter_icon).find('p').replaceWith(chapter_title_form);

        //page_typeにノートタイトル・チャプタータイトルの入力フォームを挿入
        $('.page_type').children().remove();
        $('.page_type').prepend(note_title_form_icon, chapter_icon, createPageTypeButton());
        $('.page_type').find('.note_title > p').replaceWith(note_title_form); //挿入したノートアイコンのpをtextareaに差し替え
        $('.page_type').find('.page').attr({ class : `page ${selected_color}`});

        //contents_sectionに一旦page_a_formを挿入
        $(page_a_form).prependTo('.contents_section').attr({ class : `page_base a ${selected_color}`});
        $(submit_btn).insertAfter('.contents_section').attr({ class : `submit ${selected_color}`}); 
        

        //note_sectionにカラー変更ボタン、既存ノート選択ボタンを挿入
        let exist_note_count = $('.exist_note_list').length;

        $('.note_section').children().remove();

        if(exist_note_count > 0){
            $('.note_section').append(change_color, createNoteButton('EXIST NOTE', 'exist_note'));
        }else{
            $('.note_section').append(change_color);
        }

        //chapter_existence="new"と page_type="1(a)"のcheckedをtrueにしておく
        $('#new_chapter, #page_a').prop({ checked : true });

        $('.balloon').children('.msg').html('ノートとチャプターのタイトルを<br/>入力してください');
        scrollToObject($('.page_type'));

        //hideNotes().then(showNotes());
    });


/* 既存ノート選択時----------------------------------------------------------------- */ 

    //既存ノート選択ボタンがクリックされたら
    $(document).on("click", '[for="exist_note"]', function(){
        //note_existence = exist をcheckedにする
        $('#exist_note').prop({ checked : true });
        //新規ノート選択ボタンと既存ノートリストを表示
         
        createExistNoteList();
        $('.balloon').children('.msg').text('どのノートに追加しますか?');
        scrollToTop();
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
        $.ajax({
            url : '/controllers/chapter/get_chapter_list_controller.php',
            type: 'post',
            data: { 'selected_note_id': selected_note_id },
            dataType: 'json',
        }).done(function(chapter_list){
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
            
            $('.balloon').children('.msg').text('どのチャプターに追加しますか?');
            scrollToObject($('.chapter_section'));
            //exist_chapter_set.find('div, p').css({width: '0px', padding: '0px'});
            //chapter_list表示
            /* exist_chapter_set.find('div').animate({width: '200px'}, 400, 'swing', function(){
                exist_chapter_set.find('p').animate({width: '170px'}).text(val.chapter_title);
            }); */
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
        
        $('.page_type').children('.note').after(chapter_icon, createPageTypeButton());
        $('.page_type').find('.page').attr({ class : `page ${selected_color}` });

        //contents_sectionに一旦page_a_formを挿入してradioの選択もtypeAにpropしておく
        $('.contents_section').prepend(page_a_form);
        $('.page_base').attr({ class : `page_base a ${selected_color}` });
        $(submit_btn).addClass(selected_color).insertAfter('.contents_section'); 
        $('#page_a').prop({ checked : true });
        
        $('.balloon').children('.msg').text('チャプタータイトルを入力してください');
        scrollToObject($('.page_type'));
    });

    //新規チャプターのタイトルが入力されたら
    $(document).on("keyup", 'input[name="new_chapter_title"]', function(){
        $('.balloon').children('.msg').html('好きなタイプのページに<br/>コンテンツを追加しましょう！');
    })

    //既存チャプターリストのアイコンがクリックされたら
    $(document).on("click", '.exist_chapter_list', function(){
        //chapter_existence=exist をcheckedにする
        $('#exist_chapter').prop({ checked : true });

        //選択したボタンだけ非表示
        $('.chapter_section').children().show();
        $(this).hide();
        
        let selected_chapter = $(this).attr('for');
        selected_color = $('.chapter_section').find('.note.cassette').attr('class').replace('note cassette', '');
        
        //note_cassetteとchapter_cassetteをpage_typeに挿入
        $('.page_type, .contents_section').children().remove();
        $('.chapter_section').find('.note.cassette').clone(false).prependTo('.page_type');
        $(this).clone(false).children().unwrap().addClass('chapter_cassette').appendTo('.page_type');
        
        //page_typeにより表示するフォームを分岐
        let page_type = localStorage.getItem(selected_chapter);

        if(page_type == 1) {
            $('.contents_section').prepend(page_a_form).children('.page_base').addClass(selected_color);
            $(submit_btn).addClass(selected_color).insertAfter('.contents_section'); 
            $('#page_a').prop({ checked : true });
        }else{
            $('.contents_section').prepend(page_b_form).children('.page_base').addClass(selected_color);
            $(submit_btn).addClass(selected_color).insertAfter('.contents_section'); 
            $('#page_b').prop({ checked : true });
        }
        $('.balloon').children('.msg').text('ページの内容を入力しましょう！');
        scrollToObject($('.page_type'));
    });


/* page_type選択時------------------------------------------------------------- */ 
    //page_type aを選択したら
    $(document).on("click", '[for="page_a"]', function(){
        selected_color = $('.page_type').find('.note.cassette').attr('class').replace('note cassette', '');
        $('#page_a').prop({ checked : true });
        $('.contents_section').children().remove();
        $('.contents_section').prepend(page_a_form);
        $('.page_base').attr({ class : `page_base a ${selected_color}`});
        $(submit_btn).insertAfter('.contents_section').addClass(selected_color);

        $('.balloon').children('.msg').text('こちらはAタイプ');
        scrollToObject($('.page_type'));
    });
    
    //page_type bを選択したら
    $(document).on("click", '[for="page_b"]', function(){
        selected_color = $('.page_type').find('.note.cassette').attr('class').replace('note cassette', '');
        $('#page_b').prop({ checked : true });
        $('.contents_section').children().remove();
        $('.contents_section').prepend(page_b_form);
        $('.page_base').attr({ class : `page_base b ${selected_color}`});
        $(submit_btn).insertAfter('.contents_section').addClass(selected_color);
        
        $('.balloon').children('.msg').text('こちらはBタイプ');
        scrollToObject($('.page_type'));
    });

});