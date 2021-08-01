'use strict';

$(function(){
    //ページトップに自動スクロール
    function scrollToTop(){
        $('html,body').animate({ scrollTop : 0 }, { queue : false }); 
        return d.promise();
    };

    //特定のオブジェクトにスクロール
    function scrollToObject(object){
        //$(object).show();
        let selected_obj_top = $(object).offset().top;
        $('html, body').animate({ scrollTop : selected_obj_top }, 500);
    }

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
        $('.note_list .back_cover').animate({ height: '161px' }, 500, 'swing', function(){
            $('.note_list .note_base').animate({ width: '140px' }, 500, 'swing', function(){
                $('.note_list .note_title').show();
            })
        })
    };

/* ----------------------------------------------------------------------------- */
    
    //ノートリストを表示
    $('.selected, .modal_section, .note_modal, .chapter_modal').hide();
    hideNotes();
    showNotes();
    
/* ----------------------------------------------------------------------------- */

    //exist_notesのnote_listから選択されたら
    $(document).on("click", '.exist_notes .note', function(){
        //スクロール
        $('.chapter_list').css({ 'min-height' : '400px' });

        //chapter_listとpage_listのボタン一旦削除
        $('.chapter_list, .page_list').children('button').remove();

        //選択されたノート
        let selected_note_id = $(this).val(); //ノートID
        let selected_note_title = $(this).find('p').text(); //ノートタイトル
        let selected_note_color = $(this).attr('class').replace('note ', ''); //ノートカラー

        //selectedメニューに値をセット
        $('.set_note_id').val(selected_note_id);
        $('.selected').children('.note').attr({ class : `note ${selected_note_color}`});
        $('.selected').children('.chapter').attr({ class : `chapter ${selected_note_color}`});
        $('.page_list').attr({ class : `page_list ${selected_note_color}` });
        $('.set_note_color').val(selected_note_color);
        $('.selected .note_title > p').text(selected_note_title);
        $(`#${selected_note_color}`).prop({ checked : true }).parent().css({ opacity : 1 });
        $('.note_modal').find('.edit_title').text(selected_note_title);
        $('.selected_note').css({ display : 'flex' });
        scrollToObject('.selected_note');

        //選ばれたnote_idでchapter_list取得
        $.ajax({
            url  : '/controllers/chapter/get_chapter_list.php',
            type : 'post',
            data : { 'selected_note_id' : selected_note_id },
            dataType : 'json',
        }).done(function(chapter_list){
            //chapter_list  key:chapter_id / val:chapter_title,page_type
            $.each(chapter_list, function(key, val){
                //<buttoon>:chapter_id / <p>:chapter_title / <input>:page_type
                let chapter_btn = $('<button>').addClass(`chapter ${selected_note_color}`).val(key);
                let chapter_p = $('<p>');
                let page_type = $('<input>').attr({ type : 'hidden', name : 'page_type', value : val.page_type });

                //非表示にしたチャプターボタンをchapter_listに挿入
                chapter_btn.add(chapter_p).css({ width : '0px', padding : '0px'});
                chapter_btn.prepend(chapter_p, page_type);
                $('.chapter_list').prepend(chapter_btn);

                //chapter_list表示
                chapter_btn.animate({width: '200px'}, 400, 'swing', function(){
                    chapter_p.animate({width: '170px'}).text(val.chapter_title);
                });
            });

        }).fail(function(XMLHttpRequest, textStatus, errorThrown){
            console.log(XMLHttpRequest);
            console.log(textStatus);
            console.log(errorThrown);
            changeMsgDanger().then(scrollToTop());
        });
    });

    //chapter_listから選択されたら
    $(document).on("click", '.chapter_list > .chapter', function(){
        //スクロール
        $('.page_list').css({'min-height': '400px'});
        
        //選ばれたチャプターのpage_typeによって
        //page_listのaction属性値を変更
        let selected_page_type = $(this).find('[name="page_type"]').val();
        let action_to = 
            (selected_page_type == 1) ? "/views/page/page_a.php" 
            : (selected_page_type == 2) ? "/views/page/page_b.php" 
            : '';
            $('.page_list').attr({ action : action_to });
            
            //page_listからボタンを一旦削除
            $('.page_list').children('button').remove();
            
            //selectedメニューのチャプタータイトルと
            //ノート編集モーダルのtextareaを、選ばれたノートタイトルに書き換え
            let selected_chapter_title = $(this).find('p').text();
            $('.selected .chapter > p').text(selected_chapter_title);
            $('.chapter_modal').find('.edit_title').text(selected_chapter_title);
            
            //選択されたchapter_idでpage_list取得
            let selected_chapter_id = $(this).val();
            $('.set_chapter_id').val(selected_chapter_id);
            
            //selectedメニューにnote_id割り当て
            $('.set_note_id').val(selected_chapter_id);
            $('.selected_chapter').css({ display : 'flex' });
            scrollToObject('.selected_chapter');
            
            
        $.ajax({
            url:'/controllers/page/get_page_list.php',
            type:'post',
            data:{ 'selected_chapter_id': selected_chapter_id },
            dataType:'json'
        }).done(function(page_list){
            //page_list key:page_id / val:page_title
            $.each(page_list, function(key, val){
                //<button>:page_id / .wrapback:折り返し / <p>:page_title
                let page_btn   = $('<button>').addClass('page').attr({ name : 'page_id', value : key });
                let wrapback   = $('<div>').addClass('wrapback');
                let page_title = $('<p>');

                //width: 0にしておく
                page_btn.add(page_title).css({width: '0px', padding: '0px'});

                page_btn.prepend(wrapback, page_title);
                $('.page_list').prepend(page_btn);

                page_btn.animate({width: '218px'}, 500, 'swing', function(){
                    page_title.css({width: 'auto',padding: '16px'}).text(val.page_title);
                });
                
            });

        }).fail(function(XMLHttpRequest, textStatus, errorThrown){
            console.log(XMLHttpRequest);
            console.log(textStatus);
            console.log(errorThrown);
            changeMsgDanger().then(scrollToTop());
        });
    });

    //ノート編集のモーダル表示
    $('.selected_note .edit_btn').on("click", function(){
        $('.modal_back').css({ display : 'flex' });

        let selected_note_top = $('.selected_note').offset().top + 100;
        $('.note_modal').css({ top: selected_note_top });
        $('.modal_section, .note_modal').slideDown();
    });

    //異なるノートカラーが選択された時
    $('.color_lineup .note_icon').on("click", function(){
        $('.color_lineup .note_icon').css({ opacity : 0.5 });
        $(this).css({ opacity : 1 });
    })


    //チャプター編集のモーダル表示
    $('.selected_chapter .edit_btn').on("click", function(){
        $('.modal_back').css({ display : 'flex' });

        let selected_chapter_top = $('.selected_chapter').offset().top + 100;
        $('.chapter_modal').css({ top: selected_chapter_top });
        $('.modal_section, .chapter_modal').slideDown();
    });

    //モーダル非表示
    $('.close_icon').on("click", function(){
        $('.modal_back').hide();
        $('.modal_section, .note_modal, .chapter_modal').slideUp();
    });

})