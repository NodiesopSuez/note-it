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

    let msg_text = $('.balloon').text();

    $('.balloon').attr('msg', msg_text);

    $('.selected').hide();
    hideNotes();
    showNotes();


    //note_listから選択されたら
    $('.note_list .note').on("click", function(){
        //スクロール
        $('.chapter_list').css({'min-height': '400px'});
        scrollToObject('.selected_note');

        //chapter_listとpage_listのボタン一旦削除
        $('.chapter_list, .page_list').children('button').remove();

        //selectedメニューのノートタイトルを、選ばれたノートタイトルに書き換え
        let selected_note_title = $(this).find('p').text();
        $('.selected .note_title > p').text(selected_note_title);

        //選ばれたnote_idでchapter_list取得
        let selected_note_id = $(this).attr('value');
        console.log(selected_note_id);

        $.ajax({
            url:'./get_chapter_list.php',
            type:'post',
            data: { 'selected_note_id': selected_note_id },
            dataType:'json',
        }).done(function(chapter_list){

            //デバック用に出力
            console.log(chapter_list);

            //chapter_list  key:chapter_id / val:chapter_title,page_type
            $.each(chapter_list, function(key, val){
                //<buttoon>:chapter_id / <p>:chapter_title / <input>:page_type
                let chapter_btn = $('<button>').addClass('chapter').attr('value', key);
                let chapter_p = $('<p>');
                let page_type = $('<input>').attr({type: 'hidden', name: 'page_type', value: val.page_type});

                //width:0,padding:0にしておく
                chapter_btn.add(chapter_p).css({width: '0px', padding: '0px'});
                //chapter_list組み立て
                chapter_btn.prepend(chapter_p, page_type);
                $('.chapter_list').prepend(chapter_btn);

                //chapter_list表示
                chapter_btn.animate({width: '200px'}, 400, 'swing', function(){
                    chapter_p.animate({width: '170px'}).text(val.chapter_title);
                });
            });

        }).fail(function(XMLHttpRequest, textStatus, errorThrown){
            console.log(errorThrown);
            changeMsgDanger().then(scrollToTop());
        });
    });

    //chapter_listから選択されたら
    $(document).on("click", '.chapter_list > .chapter', function(){
        //スクロール
        $('.page_list').css({'min-height': '400px'});
        scrollToObject('.selected_chapter');

        //選ばれたチャプターのpage_typeによって
        //page_listのaction属性値を変更
        let selected_page_type = $(this).find('[name="page_type"]').attr('value');
        let action_to = 
        (selected_page_type == 1) ? "../page/page_a.php" 
        : (selected_page_type == 2) ? "../page/page_b.php" 
        : '';
        $('.page_list').attr('action', action_to);

        //page_listからボタンを一旦削除
        $('.page_list').children('button').remove();

        //selectedメニューのチャプタータイトルを。選ばれたチャプタータイトルに書き換え
        let selected_chapter_title = $(this).find('p').text();
        $('.selected .chapter > p').text(selected_chapter_title);
        
        //選択されたchapter_idでpage_list取得
        let selected_chapter_id = $(this).attr('value');
        
        $.ajax({
            url:'./get_page_list.php',
            type:'post',
            data:{ 'selected_chapter_id': selected_chapter_id },
            dataType:'json'
        }).done(function(page_list){
            
            //デバック用に出力
            console.log(page_list);

            //page_list key:page_id / val:page_title
            $.each(page_list, function(key, val){
                //<button>:page_id / .wrapback:折り返し / <p>:page_title
                let page_btn = $('<button>').addClass('page').attr('value', key);
                let wrapback = $('<div>').addClass('wrapback');
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
            console.log(errorThrown);
            changeMsgDanger().then(scrollToTop());
        });
    });
})