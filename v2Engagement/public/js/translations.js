// preparing language file
var aLangKeys = [];
$.getScript( window.location.hostname + "/js/lang/en.js" );
$.getScript( window.location.hostname + "/js/lang/ko.js" );
$.getScript( window.location.hostname + "/js/lang/ja.js" );
$.getScript( window.location.hostname + "/js/lang/tw.js" );
$.getScript( window.location.hostname + "/js/lang/ch.js" );
$.getScript( window.location.hostname + "/js/lang/ru.js" );

var lang_att = 'en';
$(document).ready(function() {
    $('.count_list li a').click( function() {
        var lang = $(this).attr('id'); // obtain language id
        if(lang != lang_att ) {
            $('html').attr("lang", lang);
            lang_att = lang;
            // translate all translatable elements
            $("#lang_Flag").val(lang);
            $(".count_selected img").attr('src', 'images/flags/' + lang + "_flag.png");
            $(".count_selected span").text($(this).find('img').attr('alt').toUpperCase());
            $('.tr').each(function (i) {
                $(this).text(aLangKeys[lang][$(this).attr('key')]);
            });
            $("#user_email").attr("placeholder", aLangKeys[lang][$("#user_email").attr('key')]);
        }
    } );
});
