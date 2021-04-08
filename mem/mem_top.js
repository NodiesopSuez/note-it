$(function(){
    var d = new $.Deferred();

    //ページトップに自動スクロール
    function scrollToTop(){
        $('html,body').animate({scrollTop: 0}, {queue: false}); 
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

    //note_listのnoteがクリックされたら
    $('.note_list .note').on("click", function(){
        //selectedメニューのノートタイトルを、選ばれたノートタイトルに書き換え
        let selected_note_title = $(this).find('p').text();
        $('.selected .note_title > p').text(selected_note_title);

        //選ばれたノートのidでチャプター情報検索
        let selected_note_id = $(this).attr('value');
        console.log(selected_note_id);

        $.ajax({
            url:'./get_chapter_list.php',
            type:'post',
            data: { 'selected_note_id': selected_note_id },
            dataType:'json',
        }).done(function(chapter_list){
console.log(chapter_list);
            $('.chapter_list, .page_list').children('button').remove();
            //chapter  key:chapter_id / val:chapter_title,page_type
            $.each(chapter_list, function(key, val){
                let chapter_btn = $('<button>').addClass('chapter').attr('value', key);
                let chapter_p = $('<p>').text(val.chapter_title);
                let page_type = $('<input>').attr({type: 'hidden', name: 'page_type', value: val.page_type})
                chapter_btn.prepend(chapter_p, page_type);
                $('.chapter_list').prepend(chapter_btn);
            });
        }).fail(function(XMLHttpRequest, textStatus, errorThrown){
            console.log(errorThrown);
            changeMsgDanger().then(scrollToTop());
        });
    });

    //chapter_listのchapterがクリックされたら
    $(document).on("click", '.chapter_list > .chapter', function(){
        let selected_chapter_title = $(this).find('p').text();
        let selected_chapter_id = $(this).attr('value');

        $('.page_list').children('button').remove();
        
        $('.selected .chapter > p').text(selected_chapter_title);
        
        $.ajax({
            url:'./get_page_list.php',
            type:'post',
            data:{ 'selected_chapter_id': selected_chapter_id },
            dataType:'json'
        }).done(function(){
            
        })

    })
})