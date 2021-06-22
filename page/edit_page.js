'use strict';

$(function(){
    /* page_type_bのコンテンツ追加--------------------------------------------------- */ 
    //add_text_btnをクリック → textフォーム追加
    $(document).on("click", '#add_text', function(){
        let new_form_count = $('.form_block').length + 1; //新フォームブロックは何個目か
        
        //1個目のフォームブロックを複製して後ろに挿入
        let new_form_block = $('#form_block_1').clone().attr({ id : `form_block_${new_form_count}` }).insertBefore('.buttons.row'); 
        
        //フォームブロック内の要素のidとテキストを書き換え
        $(new_form_block).children('#contents_1').attr({ id : `contents_${new_form_count}`, name : `contents_${new_form_count}`}).text('');
        /* ¥¥$(new_form_block).children('#hid_contents_1').attr({ name : `contents_${new_form_count}`, id : `hid_contents_${new_form_count}`}); */
    });
    
    //add_img_btnをクリック → 画像選択ウィンドウ表示
    $(document).on("click", '#add_img', function(){
        let new_form_count = $('.form_block').length + 1; //新フォームブロックは何個目か
        
        //画像選択input
        let img_input = $('<input>').addClass('contents img').attr({
            name  : `contents_${new_form_count}`,
            type  : "file",
            id    : `contents_${new_form_count}`,
            accept: "image/*",
            style : 'display:none',   
        });
        
        //新しいフォームブロック
        $('<div>').addClass('form_block').attr({ id : `form_block_${new_form_count}`})
        .prepend(img_input).insertBefore('.buttons.row');
         
        //画像選択ウィンドウ表示
        $(`#contents_${new_form_count}`).trigger("click");
    });

    //選択する画像が切り替わったら
    $(document).on("change", '.img', function(){
        let selected_file = $(this).prop('files')[0]; //選ばれたファイル
        
        //ファイルサイズが１MB以下か
        if(selected_file.size > 1028576){
            alert('1MB以下のファイルを選んでください');
            return;
        }
        
        let set_id       = $(this).attr('id');
        let set_form_num = $(this).attr('id').replace('contents_', '');
        
        //FileReadeerに対応しているか
        if(window.FileReader){
            let fileReader = new FileReader();
            fileReader.onload = function(){
                //表示サムネイル,表示画像を選択した画像に切替
                let img_thumb = $('<img>').attr({ id : `thumb_${set_id}`, src : fileReader.result });
                //画像変更ボタン(画像選択inputのlabel)
                let change_img_btn = $('<label>').addClass('btn btn-secondary').text('change image')
                .attr({
                    for : `${set_id}`,
                    id  : `label_for_${set_form_num}`,
                });
                
                $(`#form_block_${set_form_num}`).append(img_thumb, change_img_btn);
            }
            fileReader.readAsDataURL(selected_file);
        }else{
            alert('アップロードエラー');
            return false;
        }
    }); 

/* contents_sectionの高さを自動調整------------------------------------ */ 
    //page_base a の場合
    $('textarea').each(function(index, element){
        console.log(element);
        let line_height = parseInt($(element).css('line-height'));
        let lines = ($(element).val() + '\n').match(/\n/g).length;
        $(element).css({ height : `${line_height * lines}px`});
    })

    $(document).on("keyup", '.page_base textarea', function(element){
        let line_height = parseInt($(element.target).css('line-height'));
        let lines = ($(element.target).val() + '\n').match(/\n/g).length;
        $(element.target).css({ height : `${line_height * lines}px`});
    });
    
});