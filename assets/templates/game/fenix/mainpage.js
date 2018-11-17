var $ = jQuery.noConflict();
$(document).ready(function ($) {
    "use strict";

    $('#showResendPassForm').click(function() {
        $('#resendPassForm').slideToggle(1000);
    });

    window.setTimeout(function(){
        $('.messages').hide(1000);
    },5000);

});