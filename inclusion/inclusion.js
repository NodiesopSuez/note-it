'use strict';

$(function(){
    $('nav').on({
        "mouseenter" : function(){
            $('header').find('button').slideDown();},
        "mouseleave"  : function(){
            $('header').find('button').slideUp();}
    })
})