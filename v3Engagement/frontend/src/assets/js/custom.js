$(window).load(function () {
    $(".VueTables__search").parent().remove();
});
$(document).ready(function () {
    $('.map_caption a').click(function (e) {
        e.preventDefault();
    });

    $(document).on("click", ".slogan .nav_opener", function () {
        $("#sidebar").toggleClass("toggle");
    });

    //TABBING ON CREATE CAMPAIGN PAGE
    $('.tabs a').click(function (e) {
        e.preventDefault();
        var current_tab = $(this).attr('href');
        $('.tabs li').removeClass('active');
        $(this).parent('li').addClass('active');
        $('#tab-content .tab').removeClass('active');
        $(current_tab).addClass('active');
        setTimeout(function () {
            var class_name = ".same-height";
            var max_height = 0;
            $(class_name).each(function (index, data) {
                if ($(data).height() > max_height) {
                    max_height = $(data).height();
                }
            });
            $(class_name).height(max_height);
        }, 1000);
    });

    $('.skip_step').click(function (e) {
        e.preventDefault();
        $('.modal, .modal-backdrop').removeClass('in');
        $('body').removeClass('modal-open');
    });
    $('.search_opener').click(function () {
        $(this).closest('.state_header').find('.table_search').toggleClass('active')
    });
    $('.login_form').addClass('active');
    //SAME HEIGHT BLOCKS
    // function same_height(){
    //     var class_name = ".same-height";
    //     var max_height = 0;
    //     $(class_name).each(function(index, data){
    //         if($(data).height() > max_height){
    //             max_height = $(data).height();
    //         }
    //     });
    //     $(class_name).height(max_height);
    // }
    //USER PROFILE DROPDOWN IN HEADER
    $(document).on("click", '.drop_opener', function (e) {
        e.preventDefault();
        $(this).toggleClass('active');
        $('.profile_dropdown').slideToggle('100');
    });
    $('.profile_dropdown a').click(function (e) {
        e.preventDefault();
        $('.profile_dropdown').slideUp('100');
    });
    //ON -s OFF BUTTON
    $(".an-switch").each(function (count) {
        var raw_checkbox = $(this);
        var existing_id = $(this).attr('id');
        if (existing_id === undefined)
            id = 'an-onoffswitch' + count;
        else
            id = existing_id;

        var addClass = $(this).addClass('an-onoffswitch-checkbox');
        var addId = $(addClass).attr('id', id);
        var checkBox = $(addId).wrap('<span>').parent().html();
        $(raw_checkbox).unwrap().replaceWith(
            "<div class='an-onoffswitchContainer'>" +
            checkBox +
            "<label class='an-onoffswitch-label' for='" + id + "'>" +
            "<span class='an-onoffswitch-inner'></span>" +
            "<span class='an-onoffswitch-switch'></span>" +
            "</label>" +
            "</div>"
        );
    });
    //SIDEBAR TOGGLING
    $('.nav_opener').click(function (e) {
        e.preventDefault();
        $('#sidebar, .page_content_holder').toggleClass('toggle');
    });
    //LEFT INNER SIDEBAR TAGS DROPDOWNS IN TABLE PAGES
    $(document).on("click", '.side_tags .opener', function (e) {
        e.preventDefault();
        $(this).closest('li').find('.inner_tags').slideToggle('100');
        $(this).find('.fa-sort-down').toggleClass('active');
    });
    $('.inner_tags li a').click(function (e) {
        e.preventDefault();
        $(this).closest('li').find('.inner_tags').slideUp('100');
    });
    //ACTIONS TOGGLER ON CAMPAIGNS PAGE
    $(document).on("click", '.actions_droper', function () {
        $(this).closest('div').find('.actions_drop').slideToggle('100');
    });

    $(document).on('click', '.inner_tabs a', function (e) {
        /*e.preventDefault();
        var current_tab = $(this).attr('href');
        $('.inner_tabs li').removeClass('active');
        $(this).parent('li').addClass('active');
        $('.inner_tab_content .inner_tab_text').removeClass('active');
        $(current_tab).addClass('active');*/
    });
    var table_tr_count = $('table tr').length;
    if (table_tr_count >= 3) {
        $('table tr:last-child, table tr:nth-last-child(2)').addClass('overflow_tr');
    }

    //EDIT/VIEW CAMPAIGN OPTIONS TOGGLE
    /*var trigger = $('.table_drop').closest('td');
    $(trigger).click(function () {
    //$(document).on("click", trigger, function () {
        // $('.table_drop').slideUp('100');
        $(this).closest('tr').nextAll('tr').find('.table_drop').slideUp();
        $(this).closest('tr').prevAll('tr').find('.table_drop').slideUp();
        $(this).find('.table_drop').slideToggle('100');
    });
    $(document).on("click", function (event) {
        if (trigger !== event.target && !trigger.has(event.target).length) {
            $(".table_drop").slideUp("fast");
        }
    });*/
    $('.location_droper').click(function () {
        $(this).closest('li').find('.second_level').slideToggle();
        $(this).closest('li').prevAll('li').find('.second_level').slideUp();
        $(this).closest('li').nextAll('li').find('.second_level').slideUp();
    });
    //SELECT TEMPLATE IN CREATE CAMPAIGN PAGE
    $('.templates_sample a').click(function (e) {
        e.preventDefault();
        $('.templates_sample li').removeClass('active');
        $(this).closest('li').addClass('active');
    });
    $('.actions_drop a').click(function () {
        $(this).closest('ul').slideUp();
    });
    //SAVED TEMPLATE SELECTION IN CREATE CAMPAIGN PAGE
    $('.saved_temps a').click(function (e) {
        e.preventDefault();
        $('.saved_temps li').removeClass('active');
        $(this).closest('li').addClass('active');
    });
    //DATE PICKER
    // $( function() {
    //     $( ".datepicker" ).datepicker();
    // });

    $(document).on("click", '#sidebar>.first_level>li>a', function (e) {
        e.preventDefault();
        if ($(this).parent('li').has('ul')) {
            $(this).closest('li').find('.left_inner_menu').slideToggle();
        }
        $(this).closest('li').nextAll('li').find('.left_inner_menu').slideUp();
        $(this).closest('li').prevAll('li').find('.left_inner_menu').slideUp();
    });

    $(document).on("click", ".left_inner_menu li", function () {
        $(".left_inner_menu").hide();
    });

    //$(".mCustomScrollbar").mCustomScrollbar();
}); //Document Ready Ends Here
/*************** CHARTS OPTIONS  ****************/

window.onload = function () {

    var options = {
        animationEnabled: true,
        exportEnabled: true,
        theme: "light1", // "light1", "light2", "dark1", "dark2"
        title: {
            text: ""
        },
        dataPointWidth: 25,
        data: [{
            type: "column", //change type to bar, line, area, pie, etc
            //indexLabel: "{y}", //Shows y value on all Data Points
            indexLabelFontColor: "#5A5757",
            indexLabelPlacement: "outside",
            dataPoints: [
                {x: 10, y: 71, color: "#2a8689"},
                {x: 13, y: 55, color: "#ff2c4d"},
                {x: 16, y: 50, color: "#f4d63a"},

                {x: 25, y: 65, color: "#2a8689"},
                {x: 28, y: 92, indexLabel: "Highest", color: "#ff2c4d"},
                {x: 31, y: 68, color: "#f4d63a"},

                {x: 40, y: 38, color: "#2a8689"},
                {x: 43, y: 71, color: "#ff2c4d"},
                {x: 46, y: 54, color: "#f4d63a"},

                {x: 55, y: 60, color: "#2a8689"},
                {x: 58, y: 36, color: "#ff2c4d"},
                {x: 61, y: 49, color: "#f4d63a"},

                {x: 70, y: 60, color: "#2a8689"},
                {x: 73, y: 36, color: "#ff2c4d"},
                {x: 76, y: 49, color: "#f4d63a"},

                {x: 85, y: 60, color: "#2a8689"},
                {x: 88, y: 36, color: "#ff2c4d"},
                {x: 91, y: 21, indexLabel: "Lowest", color: "#f4d63a"},

                {x: 100, y: 60, color: "#2a8689"},
                {x: 103, y: 36, color: "#ff2c4d"},
                {x: 106, y: 21, indexLabel: "Lowest", color: "#f4d63a"}
            ]
        }]
    }
    var options2 = {
        animationEnabled: true,
        exportEnabled: true,
        theme: "light1", // "light1", "light2", "dark1", "dark2"
        title: {
            text: ""
        },
        dataPointWidth: 25,
        data: [{
            type: "column", //change type to bar, line, area, pie, etc
            //indexLabel: "{y}", //Shows y value on all Data Points
            indexLabelFontColor: "#5A5757",
            indexLabelPlacement: "outside",
            dataPoints: [
                {x: 10, y: 71, color: "#00a6d0"},
                {x: 13, y: 51, color: "#fd8642"},

                {x: 25, y: 65, color: "#00a6d0"},
                {x: 28, y: 25, color: "#fd8642"},

                {x: 40, y: 69, color: "#00a6d0"},
                {x: 43, y: 47, color: "#fd8642"},

                {x: 55, y: 63, color: "#00a6d0"},
                {x: 58, y: 32, color: "#fd8642"},

                {x: 70, y: 25, color: "#00a6d0"},
                {x: 73, y: 85, color: "#fd8642"},

                {x: 85, y: 66, color: "#00a6d0"},
                {x: 88, y: 74, color: "#fd8642"},

                {x: 100, y: 58, color: "#00a6d0"},
                {x: 103, y: 30, color: "#fd8642"}
            ]
        }]
    }
    setTimeout(function () {
        // $("#chart1").CanvasJSChart(options);
        // $("#chart2").CanvasJSChart(options2);
    }, 2000)
}


/*$(document).ready(function() {
    var table = $('#example').DataTable();

    // Event listener to the two range filtering inputs to redraw on input
    $('input').change( function() {
        table.draw();
    });
});*/

$(document).click(function (e) {
    var variant_droper = $('.variant_droper');
    if (!variant_droper.is(e.target) && variant_droper.has(e.target).length === 0) {
        variant_droper.removeClass('active');
        variant_droper.closest('div').find('ul').slideUp();
    }
});
$(document).click(function (e) {
    var language_droper = $('.languages_holder strong');
    if (!language_droper.is(e.target) && language_droper.has(e.target).length === 0) {
        language_droper.removeClass('active');
        language_droper.closest('div').find('.flags.dropdown').slideUp();
    }
});
$(document).click(function (e) {
    var drop_opener = $('.drop_opener');
    if (!drop_opener.is(e.target) && drop_opener.has(e.target).length === 0) {
        drop_opener.removeClass('active');
        drop_opener.closest('div').find('.profile_dropdown').slideUp();
    }
});
$(document).click(function (e) {
    var actions_droper = $('.actions_droper');
    if (!actions_droper.is(e.target) && actions_droper.has(e.target).length === 0) {
        actions_droper.removeClass('active');
        actions_droper.closest('div').find('.actions_drop').slideUp();
    }
});
$(document).click(function (e) {
    var table_drop = $('td:last-child').find('.fa-cog');
    if (!table_drop.is(e.target) && table_drop.has(e.target).length === 0) {
        table_drop.removeClass('active');
        table_drop.closest('td').find('.table_drop').slideUp();
    }
});
// $(document).on("click", function(event){
//     if(trigger !== event.target && !trigger.has(event.target).length){
//         $(".table_drop").slideUp("fast");
//     }
// });
$(document).click(function (e) {
    var inner_drop_opener = $('.inner_drop_opener');
    if (!inner_drop_opener.is(e.target) && inner_drop_opener.has(e.target).length === 0) {
        inner_drop_opener.removeClass('active');
        inner_drop_opener.closest('li').find('.left_inner_menu, .groups_menu').slideUp();
    }
});


