var $ = jQuery.noConflict();



// Progress Bar

$(document).ready(function ($) {
    "use strict";

    $('#showResendPassForm').click(function() {
       $('#resendPassForm').slideToggle(1000);
    });
    
    $('.skill-shortcode').appear(function () {
        $('.progress').each(function () {
            $('.progress-bar').css('width',  function () { return ($(this).attr('data-percentage') + '%')});
        });
    }, {accY: -100});

    window.setTimeout(function(){
        $('.messages').hide(1000);
    },5000);
        
});