'use strict';

$(function(){
    /* page_type_bのコンテンツ追加--------------------------------------------------- */ 
    //編集ボタン
    let edit_btn  = $('<label>').attr({ class : 'change_img_btn'}); //val,id属性は後付け
    let edit_icon = $('<img>').attr({ class : 'edit_icon', src : '../page/img/edit_btn.svg'});
    $(edit_btn).prepend(edit_icon);
    
    //削除ボタン
    let delete_btn  = $('<button>').attr({ class : 'delete_btn', role : 'button' }); //val,id属性は後付け
    let delete_icon = $('<img>').attr({ class : 'delete_icon', src : '../page/img/delete_btn.svg'});
    $(delete_btn).prepend(delete_icon);

    //add_text_btnをクリック → textフォーム追加
    $(document).on("click", '#add_text', function(){
        //let new_form_count = $('.form_block').length + 1; //新フォームブロックは何個目か
        let new_form_count = $('.page_base.b').children().eq(-2).attr('id').replace('form_block_', '');
        console.log(new_form_count);

        //1個目のフォームブロックを複製して後ろに挿入
        //let new_form_block = $('#form_block_1').clone().attr({ id : `form_block_${new_form_count}` }).insertBefore('.add_contents.row'); 
        let new_form_block = $('.text').parent();
        console.log(new_form_block);
        //let new_form_block = $('.text').parent().clone().attr({ id : `form_block_${new_form_count}` }).insertBefore('.add_contents.row'); 
        
        //フォームブロック内の要素のidとテキストを書き換え
        $(new_form_block).children('#contents_1').attr({ id : `contents_${new_form_count}`, name : `contents_${new_form_count}`}).text('');
        $(new_form_block).append(delete_btn);
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
        .prepend(img_input).insertBefore('.add_contents.row');
         
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
            $(`#form_block_${set_form_num}`).children().remove();
            let fileReader = new FileReader();
            fileReader.onload = function(){
                //表示サムネイル,表示画像を選択した画像に切替
                let img_thumb = $('<img>').attr({ id : `thumb_${set_id}`, src : fileReader.result });
                $(`#form_block_${set_form_num}`).prepend(img_thumb, edit_btn, delete_btn);
            }
            fileReader.readAsDataURL(selected_file);
        }else{
            alert('アップロードエラー');
            return false;
        }
    }); 

    //削除ボタンがクリックされたら
    /* $(document).on("click", '.delete_btn', function(){
        
    }) */

/* contents_sectionの高さを自動調整------------------------------------ */ 
    //page_base a の場合
    $('textarea').each(function(index, element){
        console.log(element);
        let line_height = parseInt($(element).css('line-height'));
        let lines = ($(element).val() + '\n').match(/\n/g).length;
        $(element).css({ height : `${line_height * lines}px`});
    })

    $(document).on("keyup", '.page_base.a textarea', function(element){
        let line_height = parseInt($(element.target).css('line-height'));
        let lines = ($(element.target).val() + '\n').match(/\n/g).length;
        $(element.target).css({ height : `${line_height * lines}px`});
    });
    
});