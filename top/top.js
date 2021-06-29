'use strict';

$(function(){

    //ページトップに自動スクロール
    function scrollToTop(){
        $('html,body').animate({ scrollTop : 0 }, { queue : false }); 
    };

    //各セクションのトップ位置
    const news_top    = $('.news').offset().top;
    const story_top   = $('.story').offset().top;
    const feature_top = $('.feature').offset().top;

    //特定のオブジェクトにスクロール
    function scrollToObject(object){
        $('html, body').animate({ scrollTop : news_top - 20}, 500);
    }

    scrollToTop();

    //newsをクリックしたら
    $(document).on("click", '.note_nav > .blue, .nav_news', function(){
        $('html, body').animate({ scrollTop : news_top - 20}, 500);
    });
    //storyをクリックしたら
    $(document).on("click", '.note_nav > .pink, .nav_story', function(){
        $('html, body').animate({ scrollTop : story_top - 20}, 500);
    });
    //featureをクリックしたら
    $(document).on("click", '.note_nav > .yellow, .nav_feature', function(){
        $('html, body').animate({ scrollTop : feature_top - 40}, 500);
    });

    $(window).scroll(function(){
        let scroll_top = $(this).scrollTop();

        if(scroll_top < news_top - 25){
            $('header').attr({ class : 'basic' });
        }
        if(news_top - 26 < scroll_top && scroll_top < feature_top - 25){
            $('header').attr({ class : 'blue' });
        }
        if(feature_top - 26 < scroll_top && scroll_top < story_top - 25){
            $('header').attr({ class : 'yellow' });
        }
        if(story_top - 26 < scroll_top){
            $('header').attr({ class : 'pink' });
        }

    })
});