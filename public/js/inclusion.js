'use strict';

$(function(){
    $('nav').on({
        "mouseenter" : function(){
            $('header').find('button').slideDown(200);},
        "mouseleave"  : function(){
            $('header').find('button').slideUp(200);}
    })
})